import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/widgets.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:http/http.dart' as http;
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

      await androidImplementation?.requestNotificationsPermission();
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

    // Handle when user taps on notification in background state
    FirebaseMessaging.onMessageOpenedApp.listen(_handleNotificationTap);

    // Terminated state: appRouter is null here (router not built yet).
    // Defer navigation until after the first frame so the router is ready.
    final RemoteMessage? initialMessage = await fcm.getInitialMessage();
    if (initialMessage != null) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        _handleNotificationTap(initialMessage);
      });
    }

    _initialized = true;
    debugPrint('NotificationService initialized successfully');
  }

  /// Register FCM token — call this AFTER user login.
  Future<void> registerFcmToken() async {
    try {
      final FirebaseMessaging fcm = FirebaseMessaging.instance;

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

      await _tokenRefreshSub?.cancel();
      _tokenRefreshSub = fcm.onTokenRefresh.listen((newToken) async {
        debugPrint('FCM Token refreshed — updating server');
        await ApiService().updateFcmToken(newToken);
      });
    } catch (e) {
      debugPrint('Error registering FCM token: $e');
    }
  }

  /// Show a local notification when a message arrives in the foreground.
  /// If the message carries an image URL, downloads and shows it as a big picture.
  void _handleForegroundMessage(RemoteMessage message) {
    debugPrint('Foreground message received: ${message.messageId}');

    onMessageReceived?.call();

    final notification = message.notification;
    final data = message.data;

    final title = notification?.title ?? data['title'] ?? '';
    final body = notification?.body ?? data['body'] ?? '';
    if (title.isEmpty && body.isEmpty) return;

    final imageUrl = data['image_url'] as String?;

    if (imageUrl != null && imageUrl.isNotEmpty && Platform.isAndroid) {
      _showForegroundWithImage(message.hashCode, title, body, imageUrl,
          jsonEncode(data));
    } else {
      _showForegroundPlain(message.hashCode, title, body, jsonEncode(data));
    }
  }

  /// Download the image and show a big-picture notification.
  Future<void> _showForegroundWithImage(
    int id,
    String title,
    String body,
    String imageUrl,
    String payload,
  ) async {
    try {
      final response =
          await http.get(Uri.parse(imageUrl)).timeout(const Duration(seconds: 8));

      if (response.statusCode == 200) {
        final bitmap = ByteArrayAndroidBitmap(response.bodyBytes);
        await _localNotifications.show(
          id,
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
              largeIcon: bitmap,
              styleInformation: BigPictureStyleInformation(
                bitmap,
                hideExpandedLargeIcon: true,
                contentTitle: title,
                summaryText: body,
              ),
            ),
            iOS: const DarwinNotificationDetails(
              presentAlert: true,
              presentBadge: true,
              presentSound: true,
            ),
          ),
          payload: payload,
        );
        return;
      }
    } catch (e) {
      debugPrint('Could not download notification image: $e');
    }
    // Fall back to plain notification if image download fails
    _showForegroundPlain(id, title, body, payload);
  }

  /// Show a plain text notification (no image).
  Future<void> _showForegroundPlain(
      int id, String title, String body, String payload) async {
    try {
      await _localNotifications.show(
        id,
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
        payload: payload,
      );
    } catch (e) {
      debugPrint('Error showing local notification: $e');
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
  /// Supports `type == 'post'` → opens the post detail screen.
  Future<void> _navigateFromData(Map<String, dynamic> data) async {
    if (data['type'] != 'post' || data['post_id'] == null) return;

    final result = await ApiService().getPost(data['post_id'].toString());
    if (!result.success || result.data == null) return;

    appRouter?.push('/news-detail', extra: result.data);
  }
}
