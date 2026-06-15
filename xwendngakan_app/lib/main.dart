import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'app.dart';
import 'core/services/notification_service.dart';
import 'firebase_options.dart';

/// Background message handler — MUST be top-level function
@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp(options: DefaultFirebaseOptions.currentPlatform);
  debugPrint("Background message: ${message.messageId}");

  // Show notification via flutter_local_notifications for data-only messages
  final notification = message.notification;
  final data = message.data;

  // If there's already a notification payload, Android will show it automatically
  // Only need to handle data-only messages
  if (notification == null && data.isNotEmpty) {
    final title = data['title'] ?? '';
    final body = data['body'] ?? '';

    if (title.isNotEmpty || body.isNotEmpty) {
      final FlutterLocalNotificationsPlugin flutterLocalNotificationsPlugin =
          FlutterLocalNotificationsPlugin();

      try {
        await flutterLocalNotificationsPlugin.show(
          message.hashCode,
          title,
          body,
          const NotificationDetails(
            android: AndroidNotificationDetails(
              'high_importance_channel',
              'ئاگادارکردنەوەکان',
              channelDescription: 'ئەم کەناڵە بۆ ئاگادارکردنەوەی گرنگ بەکاردێت',
              importance: Importance.max,
              priority: Priority.high,
              icon: '@mipmap/ic_launcher',
            ),
            iOS: DarwinNotificationDetails(
              presentAlert: true,
              presentBadge: true,
              presentSound: true,
            ),
          ),
        );
      } catch (e) {
        debugPrint('Error showing background local notification: $e');
        try {
          await flutterLocalNotificationsPlugin.show(
            message.hashCode,
            title,
            body,
            const NotificationDetails(
              android: AndroidNotificationDetails(
                'high_importance_channel',
                'ئاگادارکردنەوەکان',
                channelDescription: 'ئەم کەناڵە بۆ ئاگادارکردنەوەی گرنگ بەکاردێت',
                importance: Importance.max,
                priority: Priority.high,
              ),
              iOS: DarwinNotificationDetails(),
            ),
          );
        } catch (e2) {
          debugPrint('Fallback background local notification failed: $e2');
        }
      }
    }
  }
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  bool firebaseReady = false;
  try {
    await Firebase.initializeApp(
      options: DefaultFirebaseOptions.currentPlatform,
    );
    firebaseReady = true;
  } catch (e) {
    debugPrint('Firebase init error: $e');
  }

  if (firebaseReady) {
    try {
      FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);
      await NotificationService().initialize();
    } catch (e) {
      debugPrint('NotificationService init error: $e');
    }
  }

  await SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
    DeviceOrientation.portraitDown,
  ]);

  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.light,
    ),
  );

  runApp(const XwendngakanApp());
}
