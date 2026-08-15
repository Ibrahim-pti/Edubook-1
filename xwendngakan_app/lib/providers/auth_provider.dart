import 'package:flutter/material.dart';
import 'package:firebase_auth/firebase_auth.dart' as fb;
import 'package:shared_preferences/shared_preferences.dart';
import '../core/constants/app_constants.dart';
import '../core/services/notification_service.dart';
import '../data/models/user_model.dart';
import '../data/services/api_service.dart';

enum AuthStatus { initial, authenticated, unauthenticated, loading }

class AuthProvider extends ChangeNotifier {
  AuthStatus _status = AuthStatus.initial;
  UserModel? _user;
  String? _error;
  String? _token;
  String _selectedRole = 'institution';

  AuthStatus get status => _status;
  UserModel? get user => _user;
  String? get error => _error;
  String? get token => _token;
  String get selectedRole => _selectedRole;
  bool get isAuthenticated => _status == AuthStatus.authenticated;

  void setSelectedRole(String role) {
    _selectedRole = role;
  }

  final ApiService _api = ApiService();

  AuthProvider() {
    _init();
  }

  Future<void> _init() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString(AppConstants.tokenKey);

    if (_token != null) {
      final result = await _api.getUser();
      if (result.success && result.data != null) {
        _user = result.data;
        _status = AuthStatus.authenticated;

        // Re-register FCM token on app restart if logged in
        await NotificationService().registerFcmToken();
      } else if (result.isUnauthorized) {
        // Token genuinely rejected by the server → log out.
        await _clearToken();
        _status = AuthStatus.unauthenticated;
      } else {
        // Network / server hiccup (offline, 503, timeout) — keep the saved
        // session so the user isn't kicked back to login on every cold start.
        _status = AuthStatus.authenticated;
      }
    } else {
      _status = AuthStatus.unauthenticated;
    }
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    _status = AuthStatus.loading;
    _error = null;
    notifyListeners();

    try {
      final cred = await fb.FirebaseAuth.instance.signInWithEmailAndPassword(
        email: email.trim(),
        password: password,
      );
      return await _exchangeFirebaseToken(cred.user, null);
    } on fb.FirebaseAuthException catch (e) {
      _failAuth(_mapFirebaseError(e));
      return false;
    } catch (_) {
      _failAuth('کێشەیەک ڕوویدا. تکایە دووبارە هەوڵ بدەرەوە.');
      return false;
    }
  }

  Future<bool> register(String name, String email, String password,
      {String? phone}) async {
    _status = AuthStatus.loading;
    _error = null;
    notifyListeners();

    try {
      final cred = await fb.FirebaseAuth.instance.createUserWithEmailAndPassword(
        email: email.trim(),
        password: password,
      );
      await cred.user?.updateDisplayName(name);
      // Best-effort email verification — don't block sign-up if it fails.
      try {
        await cred.user?.sendEmailVerification();
      } catch (_) {}
      return await _exchangeFirebaseToken(cred.user, name, phone: phone);
    } on fb.FirebaseAuthException catch (e) {
      _failAuth(_mapFirebaseError(e));
      return false;
    } catch (_) {
      _failAuth('کێشەیەک ڕوویدا. تکایە دووبارە هەوڵ بدەرەوە.');
      return false;
    }
  }

  /// Sends a Firebase password-reset email (contains a reset link).
  Future<bool> sendPasswordReset(String email) async {
    _error = null;
    try {
      await fb.FirebaseAuth.instance
          .sendPasswordResetEmail(email: email.trim());
      return true;
    } on fb.FirebaseAuthException catch (e) {
      _error = _mapFirebaseError(e);
      notifyListeners();
      return false;
    } catch (_) {
      _error = 'کێشەیەک ڕوویدا. تکایە دووبارە هەوڵ بدەرەوە.';
      notifyListeners();
      return false;
    }
  }

  /// Exchanges a Firebase user's ID token for a Sanctum token from our backend.
  Future<bool> _exchangeFirebaseToken(fb.User? fbUser, String? name,
      {String? phone}) async {
    final idToken = await fbUser?.getIdToken();
    if (fbUser == null || idToken == null) {
      _failAuth('چوونەژوورەوە سەرنەکەوت. تکایە دووبارە هەوڵ بدەرەوە.');
      return false;
    }

    final result = await _api.firebaseLogin(idToken,
        name: name ?? fbUser.displayName, phone: phone);

    if (result.success && result.data != null) {
      final inner =
          (result.data!['data'] ?? result.data!) as Map<String, dynamic>;
      _token = inner['token'] as String?;
      _user = UserModel.fromJson(inner['user'] as Map<String, dynamic>);

      await _saveToken(_token!);
      _status = AuthStatus.authenticated;
      notifyListeners();

      // Register FCM token after a successful sign-in.
      await NotificationService().registerFcmToken();
      return true;
    }

    _failAuth(result.error);
    return false;
  }

  void _failAuth(String? message) {
    _error = message;
    _status = AuthStatus.unauthenticated;
    notifyListeners();
  }

  /// Maps Firebase auth error codes to friendly Kurdish messages.
  String _mapFirebaseError(fb.FirebaseAuthException e) {
    switch (e.code) {
      case 'invalid-email':
        return 'ئیمەیڵەکە نادروستە.';
      case 'user-disabled':
        return 'ئەم هەژمارە ڕاگیراوە.';
      case 'user-not-found':
        return 'هیچ هەژمارێک بەم ئیمەیڵە نییە.';
      case 'wrong-password':
      case 'invalid-credential':
        return 'ئیمەیڵ یان وشەی نهێنی هەڵەیە.';
      case 'email-already-in-use':
        return 'ئەم ئیمەیڵە پێشتر بەکارهاتووە.';
      case 'weak-password':
        return 'وشەی نهێنی لاوازە (لانیکەم ٦ پیت).';
      case 'network-request-failed':
        return 'پەیوەندی بە ئینتەرنێتەوە نییە.';
      case 'too-many-requests':
        return 'هەوڵی زۆر درا. تکایە دواتر هەوڵ بدەرەوە.';
      default:
        return e.message ?? 'کێشەیەک لە چوونەژوورەوە ڕوویدا.';
    }
  }

  Future<void> logout() async {
    await _api.logout();
    try {
      await fb.FirebaseAuth.instance.signOut();
    } catch (_) {}
    await _clearToken();
    _user = null;
    _token = null;
    _status = AuthStatus.unauthenticated;
    notifyListeners();
  }

  /// Permanently deletes the user's account (server + Firebase) and signs out.
  /// Returns true on success.
  Future<bool> deleteAccount() async {
    final result = await _api.deleteAccount();
    if (!result.success) {
      _error = result.error;
      notifyListeners();
      return false;
    }

    // Remove the Firebase Auth user, then sign out locally.
    try {
      await fb.FirebaseAuth.instance.currentUser?.delete();
    } catch (_) {
      // If deletion needs recent login, fall back to sign-out.
      try {
        await fb.FirebaseAuth.instance.signOut();
      } catch (_) {}
    }

    await _clearToken();
    _user = null;
    _token = null;
    _status = AuthStatus.unauthenticated;
    notifyListeners();
    return true;
  }

  Future<void> _saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(AppConstants.tokenKey, token);
  }

  Future<void> _clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(AppConstants.tokenKey);
    await prefs.remove(AppConstants.userKey);
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
