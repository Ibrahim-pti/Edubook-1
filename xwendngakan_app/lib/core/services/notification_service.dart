import 'package:firebase_messaging/firebase_messaging.dart';
import '../../data/services/api_service.dart';

class NotificationService {
  static final NotificationService _instance = NotificationService._internal();
  factory NotificationService() => _instance;
  NotificationService._internal();

  Future<void> initialize() async {
    final FirebaseMessaging fcm = FirebaseMessaging.instance;

    // Request permission for iOS
    NotificationSettings settings = await fcm.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    if (settings.authorizationStatus == AuthorizationStatus.authorized) {
      // Get FCM Token
      String? token = await fcm.getToken();
      if (token != null) {
        await ApiService().updateFcmToken(token);
      }

      // Handle token refresh
      fcm.onTokenRefresh.listen((newToken) async {
        await ApiService().updateFcmToken(newToken);
      });

      // Handle foreground messages
      FirebaseMessaging.onMessage.listen((RemoteMessage message) {
        if (message.notification != null) {}
      });
    } else {}
  }
}
