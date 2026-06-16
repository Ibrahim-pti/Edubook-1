import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import '../../data/services/api_service.dart';
import '../router.dart';

class NotificationService {
  static final NotificationService _instance = NotificationService._internal();
  factory NotificationService() => _instance;
  NotificationService._internal();

  final FlutterLocalNotificationsPlugin _localNotifications =
      FlutterLocalNotificationsPlugin();

  bool _initialized = false;
  StreamSubscription<String>? _tokenRefreshSub;

  /// Called when a push arrives while the app is in the foreground, so the
  /// in-app notification badge can update in real time.
  static VoidCallback? onMessageReceived;

  /// Android notification channel for high-importance notifications
  static const AndroidNotificationChannel _channel = AndroidNotificationChannel(
    'high_importance_channel',
    'ئاگادارکردنەوەکان',
    description: 'ئەم کەناڵە بۆ ئاگادارکردنەوەی گرنگ بەکاردێت',
    importance: Importance.max,
    playSound: true,
    enableVibration: true,
    showBadge: true,
  );

  Future<void> initialize() async {
    if (_initialized) return;

    final FirebaseMessaging fcm = FirebaseMessaging.instance;

    // Request permission for notifications
    NotificationSettings settings = await fcm.requestPermission(
      alert: true,
      badge: true,
      sound: true,
      provisional: false,
    );

    if (settings.authorizationStatus != AuthorizationStatus.authorized &&
        settings.authorizationStatus != AuthorizationStatus.provisional) {
      debugPrint('User declined notification permission');
      return;
    }

    // Create notification channel on Android and request permissions
    if (Platform.isAndroid) {
      final androidImplementation = _localNotifications
          .resolvePlatformSpecificImplementation<
              AndroidFlutterLocalNotificationsPlugin>();
              
      // Request permission for Android 13+
      await androidImplementation?.requestNotificationsPermission();
      
      // Create channel
      await androidImplementation?.createNotificationChannel(_channel);
    }

    // Initialize local notifications
    const androidSettings =
        AndroidInitializationSettings('@mipmap/ic_launcher');
    const iosSettings = DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
    );
    const initSettings = InitializationSettings(
      android: androidSettings,
      iOS: iosSettings,
    );

    await _localNotifications.initialize(
      initSettings,
      onDidReceiveNotificationResponse: _onNotificationTap,
    );

    // Set foreground notification presentation options (iOS)
    await fcm.setForegroundNotificationPresentationOptions(
      alert: true,
      badge: true,
      sound: true,
    );

    // Handle foreground messages — show local notification
    FirebaseMessaging.onMessage.listen(_handleForegroundMessage);

    // Handle when user taps on notification in background/terminated state
    FirebaseMessaging.onMessageOpenedApp.listen(_handleNotificationTap);

    // Check if app was opened from a terminated state notification
    RemoteMessage? initialMessage = await fcm.getInitialMessage();
    if (initialMessage != null) {
      _handleNotificationTap(initialMessage);
    }

    _initialized = true;
    debugPrint('NotificationService initialized successfully');
  }

  /// Register FCM token — call this AFTER user login.
  /// Works independently of initialize() — sends token even if
  /// notification permission was denied (token still works for data messages).
  Future<void> registerFcmToken() async {
    try {
      final FirebaseMessaging fcm = FirebaseMessaging.instance;

      // Get token regardless of notification permission status
      String? token = await fcm.getToken();
      if (token != null) {
        debugPrint('\n========== FCM TOKEN ==========');
        debugPrint(token);
        debugPrint('===============================\n');
        final result = await ApiService().updateFcmToken(token);
        if (result.success) {
          debugPrint('[FCM] ✅ Token successfully saved to server!');
        } else {
          debugPrint('[FCM] ❌ Failed to save token: ${result.error}');
        }
      } else {
        debugPrint('FCM token is null — Firebase may not be configured correctly');
      }

      // Cancel existing subscription to avoid duplicates
      await _tokenRefreshSub?.cancel();
      _tokenRefreshSub = fcm.onTokenRefresh.listen((newToken) async {
        debugPrint('FCM Token refreshed — updating server');
        await ApiService().updateFcmToken(newToken);
      });
    } catch (e) {
      debugPrint('Error registering FCM token: $e');
    }
  }

  /// Show a local notification when message arrives in foreground
  void _handleForegroundMessage(RemoteMessage message) {
    debugPrint('Foreground message received: ${message.messageId}');

    // Bump the in-app notification badge in real time.
    onMessageReceived?.call();

    final notification = message.notification;
    final data = message.data;

    // Get title/body from notification payload or data payload
    final title = notification?.title ?? data['title'] ?? '';
    final body = notification?.body ?? data['body'] ?? '';
    if (title.isEmpty && body.isEmpty) return;

    // FCM handles image display automatically in background/terminated state.
    // For foreground we show a rich text notification.
    try {
      _localNotifications.show(
        message.hashCode,
        title,
        body,
        NotificationDetails(
          android: AndroidNotificationDetails(
            _channel.id,
            _channel.name,
            channelDescription: _channel.description,
            importance: Importance.max,
            priority: Priority.high,
            icon: '@mipmap/ic_launcher',
            playSound: true,
            enableVibration: true,
            styleInformation: BigTextStyleInformation(body),
          ),
          iOS: const DarwinNotificationDetails(
            presentAlert: true,
            presentBadge: true,
            presentSound: true,
          ),
        ),
        payload: jsonEncode(message.data),
      );
    } catch (e) {
      debugPrint('Error showing local notification: $e');
      // Fallback without image
      try {
        _localNotifications.show(
          message.hashCode,
          title,
          body,
          NotificationDetails(
            android: AndroidNotificationDetails(
              _channel.id,
              _channel.name,
              channelDescription: _channel.description,
              importance: Importance.max,
              priority: Priority.high,
            ),
            iOS: const DarwinNotificationDetails(),
          ),
          payload: jsonEncode(message.data),
        );
      } catch (e2) {
        debugPrint('Fallback local notification also failed: $e2');
      }
    }
  }

  /// Handle FCM notification tap (background / terminated → opened).
  void _handleNotificationTap(RemoteMessage message) {
    debugPrint('Notification tapped: ${message.messageId}');
    _navigateFromData(message.data);
  }

  /// Handle local (foreground) notification tap — payload is the JSON data.
  void _onNotificationTap(NotificationResponse response) {
    debugPrint('Local notification tapped: ${response.payload}');
    final payload = response.payload;
    if (payload == null || payload.isEmpty) return;
    try {
      final data = (jsonDecode(payload) as Map).cast<String, dynamic>();
      _navigateFromData(data);
    } catch (e) {
      debugPrint('Could not parse notification payload: $e');
    }
  }

  /// Route a notification to the right screen based on its data payload.
  /// Currently supports `type == 'post'` → opens the post detail screen.
  Future<void> _navigateFromData(Map<String, dynamic> data) async {
    if (data['type'] != 'post' || data['post_id'] == null) return;

    final result = await ApiService().getPost(data['post_id'].toString());
    if (!result.success || result.data == null) return;

    // Navigate without a BuildContext (tap happens outside the widget tree).
    appRouter?.push('/news-detail', extra: result.data);
  }
}
