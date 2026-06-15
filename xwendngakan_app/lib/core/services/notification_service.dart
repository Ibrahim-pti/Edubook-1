import 'dart:io';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import '../../data/services/api_service.dart';

class NotificationService {
  static final NotificationService _instance = NotificationService._internal();
  factory NotificationService() => _instance;
  NotificationService._internal();

  final FlutterLocalNotificationsPlugin _localNotifications =
      FlutterLocalNotificationsPlugin();

  bool _initialized = false;

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

  /// Register FCM token — call this AFTER user login
  Future<void> registerFcmToken() async {
    try {
      final FirebaseMessaging fcm = FirebaseMessaging.instance;
      String? token = await fcm.getToken();
      if (token != null) {
        debugPrint('\n========== FCM TOKEN ==========');
        debugPrint(token);
        debugPrint('===============================\n');
        await ApiService().updateFcmToken(token);
      }

      // Listen for token refresh
      fcm.onTokenRefresh.listen((newToken) async {
        debugPrint('FCM Token refreshed');
        await ApiService().updateFcmToken(newToken);
      });
    } catch (e) {
      debugPrint('Error registering FCM token: $e');
    }
  }

  /// Show a local notification when message arrives in foreground
  void _handleForegroundMessage(RemoteMessage message) {
    debugPrint('Foreground message received: ${message.messageId}');

    final notification = message.notification;
    final android = message.notification?.android;
    final data = message.data;

    // Get title/body from notification payload or data payload
    final title = notification?.title ?? data['title'] ?? '';
    final body = notification?.body ?? data['body'] ?? '';

    if (title.isEmpty && body.isEmpty) return;

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
        payload: message.data['type'] ?? '',
      );
    } catch (e) {
      debugPrint('Error showing local notification: $e');
      // Try again without specifying icon to let it use default
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
        );
      } catch (e2) {
        debugPrint('Fallback local notification also failed: $e2');
      }
    }
  }

  /// Handle notification tap from background
  void _handleNotificationTap(RemoteMessage message) {
    debugPrint('Notification tapped: ${message.messageId}');
    // Navigation can be handled here if needed
  }

  /// Handle local notification tap
  void _onNotificationTap(NotificationResponse response) {
    debugPrint('Local notification tapped: ${response.payload}');
    // Navigation can be handled here if needed
  }
}
