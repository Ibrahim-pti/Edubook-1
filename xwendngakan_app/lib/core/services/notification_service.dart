import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import '../../data/services/api_service.dart';

class NotificationService {
  static final NotificationService _instance = NotificationService._internal();
  factory NotificationService() => _instance;
  NotificationService._internal();

  Future<void> initialize() async {
    final FirebaseMessaging fcm = FirebaseMessaging.instance;

    // Request permission for notifications
    NotificationSettings settings = await fcm.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    // Update foreground notification presentation options for iOS
    await FirebaseMessaging.instance.setForegroundNotificationPresentationOptions(
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

      // Handle foreground messages (Log or handle internally, native UI will not pop up in foreground on Android without local_notifications)
      FirebaseMessaging.onMessage.listen((RemoteMessage message) {
        debugPrint('Received a foreground message: ${message.messageId}');
      });
      
      // Handle when user taps on the notification in background/terminated state
      FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
        debugPrint('Message opened app: ${message.messageId}');
      });
    } else {
      debugPrint('User declined or has not accepted permission');
    }
  }
}
