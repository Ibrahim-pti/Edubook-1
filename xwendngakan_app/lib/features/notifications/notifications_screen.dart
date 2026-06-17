import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../core/localization/app_localizations.dart';
import '../../data/services/api_service.dart';
import '../../providers/auth_provider.dart';
import '../../providers/notifications_provider.dart';
import '../../shared/widgets/common_widgets.dart';

class NotificationsScreen extends StatefulWidget {
  const NotificationsScreen({super.key});

  @override
  State<NotificationsScreen> createState() => _NotificationsScreenState();
}

class _NotificationsScreenState extends State<NotificationsScreen>
    with SingleTickerProviderStateMixin {
  final _api = ApiService();
  List<Map<String, dynamic>> _notifications = [];
  bool _loading = true;
  bool _navigating = false;
  late AnimationController _animCtrl;
  late Animation<double> _fadeAnim;

  @override
  void initState() {
    super.initState();
    _animCtrl = AnimationController(
        vsync: this, duration: const Duration(milliseconds: 500));
    _fadeAnim = CurvedAnimation(parent: _animCtrl, curve: Curves.easeOut);
    _load();
  }

  @override
  void dispose() {
    _animCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    final auth = Provider.of<AuthProvider>(context, listen: false);
    if (!auth.isAuthenticated) {
      setState(() => _loading = false);
      return;
    }

    // Mark as read in both local state and server
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) {
        Provider.of<NotificationsProvider>(context, listen: false)
            .markAllRead();
      }
    });
    _api.markAllNotificationsRead();

    final r = await _api.getNotifications();
    if (!mounted) return;
    if (r.success && r.data != null) {
      setState(() {
        _notifications = r.data!;
        _loading = false;
      });
      _animCtrl.forward();
    } else {
      setState(() => _loading = false);
    }
  }

  /// Navigate to the relevant screen when a notification is tapped
  Future<void> _handleNotifTap(Map<String, dynamic> notif) async {
    if (_navigating) return;

    final outerData = notif['data'] as Map<String, dynamic>? ?? {};
    // The inner data (routing info) may be nested under 'data'
    final innerData = (outerData['data'] is Map)
        ? (outerData['data'] as Map).cast<String, dynamic>()
        : outerData;

    final type = innerData['type']?.toString() ?? '';
    final postId = innerData['post_id']?.toString();
    final institutionId = innerData['institution_id']?.toString();

    if (type == 'post' && postId != null) {
      setState(() => _navigating = true);
      HapticFeedback.lightImpact();

      final result = await _api.getPost(postId);
      if (!mounted) return;
      setState(() => _navigating = false);

      if (result.success && result.data != null) {
        context.push('/news-detail', extra: result.data);
      } else {
        // Fallback: go to institutions page
        if (institutionId != null) {
          context.push('/institutions/$institutionId');
        }
      }
    } else if (institutionId != null) {
      HapticFeedback.lightImpact();
      context.push('/institutions/$institutionId');
    }
  }

  @override
  Widget build(BuildContext context) {
    final l = AppLocalizations.of(context);
    final auth = Provider.of<AuthProvider>(context);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: isDark
          ? SystemUiOverlayStyle.light
          : SystemUiOverlayStyle.dark,
      child: Scaffold(
        backgroundColor:
            isDark ? const Color(0xFF0A0F1E) : const Color(0xFFF2F5FB),
        body: Column(
          children: [
            _buildHeader(isDark, l),
            Expanded(
              child: !auth.isAuthenticated
                  ? _buildLoginPrompt(l)
                  : _loading
                      ? _buildShimmer(isDark)
                      : _notifications.isEmpty
                          ? _buildEmpty(l, isDark)
                          : FadeTransition(
                              opacity: _fadeAnim,
                              child: RefreshIndicator(
                                onRefresh: _load,
                                color: AppColors.primary,
                                child: ListView.builder(
                                  padding: const EdgeInsets.fromLTRB(
                                      16, 8, 16, 100),
                                  itemCount: _notifications.length,
                                  itemBuilder: (_, i) =>
                                      _buildNotifCard(_notifications[i], isDark, i),
                                ),
                              ),
                            ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(bool isDark, AppLocalizations l) {
    return Container(
      padding: EdgeInsets.only(
        top: MediaQuery.of(context).padding.top + 12,
        bottom: 20,
        left: 20,
        right: 20,
      ),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: isDark
              ? [const Color(0xFF131D2E), const Color(0xFF0A0F1E)]
              : [Colors.white, const Color(0xFFF2F5FB)],
          begin: Alignment.topCenter,
          end: Alignment.bottomCenter,
        ),
        border: Border(
          bottom: BorderSide(
            color: isDark
                ? Colors.white.withValues(alpha: 0.05)
                : Colors.black.withValues(alpha: 0.06),
            width: 1,
          ),
        ),
      ),
      child: Row(
        children: [
          GestureDetector(
            onTap: () =>
                context.canPop() ? context.pop() : context.go('/profile'),
            child: Container(
              width: 42,
              height: 42,
              decoration: BoxDecoration(
                color: isDark
                    ? Colors.white.withValues(alpha: 0.07)
                    : Colors.black.withValues(alpha: 0.06),
                borderRadius: BorderRadius.circular(14),
              ),
              child: Icon(
                Icons.arrow_back_ios_new_rounded,
                size: 17,
                color: isDark ? Colors.white70 : Colors.black54,
              ),
            ),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  l.notifications,
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.w900,
                    fontFamily: 'Rabar',
                    color: isDark ? Colors.white : Colors.black87,
                  ),
                ),
                if (_notifications.isNotEmpty)
                  Text(
                    '${_notifications.length} ${l.notifications}',
                    style: TextStyle(
                      fontSize: 12,
                      fontFamily: 'Rabar',
                      color: isDark ? Colors.white38 : Colors.black38,
                    ),
                  ),
              ],
            ),
          ),
          // Bell icon with pulsing animation
          Container(
            width: 42,
            height: 42,
            decoration: BoxDecoration(
              color: AppColors.primary.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(14),
            ),
            child: const Icon(
              Icons.notifications_rounded,
              color: AppColors.primary,
              size: 22,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildNotifCard(Map<String, dynamic> notif, bool isDark, int index) {
    final outerData = notif['data'] as Map<String, dynamic>? ?? {};
    final innerData = (outerData['data'] is Map)
        ? (outerData['data'] as Map).cast<String, dynamic>()
        : outerData;

    final title = outerData['title']?.toString() ??
        innerData['title']?.toString() ??
        notif['type']?.toString() ??
        'ئاگادارکردنەوە';
    final body = outerData['body']?.toString() ??
        outerData['message']?.toString() ??
        innerData['body']?.toString() ??
        '';
    final type = innerData['type']?.toString() ?? '';
    final isRead = notif['read_at'] != null;
    final isPost = type == 'post';
    final isNavigable = isPost || innerData['institution_id'] != null;
    final createdAt = _formatTime(notif['created_at']?.toString());

    return TweenAnimationBuilder<double>(
      duration: Duration(milliseconds: 300 + (index * 60)),
      tween: Tween(begin: 0.0, end: 1.0),
      builder: (context, value, child) => Opacity(
        opacity: value,
        child: Transform.translate(
          offset: Offset(0, 20 * (1 - value)),
          child: child,
        ),
      ),
      child: GestureDetector(
        onTap: isNavigable ? () => _handleNotifTap(notif) : null,
        child: Container(
          margin: const EdgeInsets.only(bottom: 10),
          decoration: BoxDecoration(
            color: isRead
                ? (isDark ? const Color(0xFF131D2E) : Colors.white)
                : (isDark
                    ? AppColors.primary.withValues(alpha: 0.08)
                    : AppColors.primary.withValues(alpha: 0.04)),
            borderRadius: BorderRadius.circular(20),
            border: Border.all(
              color: isRead
                  ? (isDark
                      ? Colors.white.withValues(alpha: 0.06)
                      : Colors.black.withValues(alpha: 0.05))
                  : AppColors.primary.withValues(alpha: 0.25),
              width: isRead ? 1 : 1.5,
            ),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withValues(alpha: isDark ? 0.2 : 0.04),
                blurRadius: 12,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Icon
                Container(
                  width: 46,
                  height: 46,
                  decoration: BoxDecoration(
                    gradient: isPost
                        ? LinearGradient(
                            colors: [
                              AppColors.primary.withValues(alpha: 0.9),
                              AppColors.primary,
                            ],
                          )
                        : LinearGradient(
                            colors: [
                              const Color(0xFF10B981).withValues(alpha: 0.9),
                              const Color(0xFF10B981),
                            ],
                          ),
                    borderRadius: BorderRadius.circular(14),
                    boxShadow: [
                      BoxShadow(
                        color: (isPost ? AppColors.primary : const Color(0xFF10B981))
                            .withValues(alpha: 0.3),
                        blurRadius: 10,
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: Icon(
                    isPost
                        ? Icons.article_rounded
                        : Icons.notifications_rounded,
                    color: Colors.white,
                    size: 22,
                  ),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Expanded(
                            child: Text(
                              title,
                              style: TextStyle(
                                fontSize: 14,
                                fontWeight: isRead
                                    ? FontWeight.w600
                                    : FontWeight.w900,
                                fontFamily: 'Rabar',
                                color: isDark ? Colors.white : Colors.black87,
                              ),
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                            ),
                          ),
                          if (!isRead)
                            Container(
                              width: 8,
                              height: 8,
                              margin: const EdgeInsets.only(right: 4, top: 4),
                              decoration: const BoxDecoration(
                                color: AppColors.primary,
                                shape: BoxShape.circle,
                              ),
                            ),
                        ],
                      ),
                      if (body.isNotEmpty) ...[
                        const SizedBox(height: 5),
                        Text(
                          body,
                          style: TextStyle(
                            fontSize: 12,
                            fontFamily: 'Rabar',
                            height: 1.5,
                            color: isDark ? Colors.white60 : Colors.black54,
                          ),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ],
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          Icon(
                            Icons.access_time_rounded,
                            size: 12,
                            color: isDark ? Colors.white30 : Colors.black26,
                          ),
                          const SizedBox(width: 4),
                          Text(
                            createdAt,
                            style: TextStyle(
                              fontSize: 11,
                              fontFamily: 'Rabar',
                              color: isDark ? Colors.white30 : Colors.black38,
                            ),
                          ),
                          if (isNavigable) ...[
                            const Spacer(),
                            Container(
                              padding: const EdgeInsets.symmetric(
                                  horizontal: 10, vertical: 4),
                              decoration: BoxDecoration(
                                color: AppColors.primary.withValues(alpha: 0.1),
                                borderRadius: BorderRadius.circular(10),
                              ),
                              child: const Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Text(
                                    'کردنەوە',
                                    style: TextStyle(
                                      fontSize: 11,
                                      fontFamily: 'Rabar',
                                      fontWeight: FontWeight.w700,
                                      color: AppColors.primary,
                                    ),
                                  ),
                                  SizedBox(width: 4),
                                  Icon(
                                    Icons.arrow_forward_ios_rounded,
                                    size: 10,
                                    color: AppColors.primary,
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildLoginPrompt(AppLocalizations l) {
    return Center(
      child: EmptyState(
        icon: Icons.notifications_none_outlined,
        message: l.loginToSeeNotifications,
        actionLabel: l.login,
        onAction: () => context.go('/login'),
      ),
    );
  }

  Widget _buildEmpty(AppLocalizations l, bool isDark) {
    return Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 90,
            height: 90,
            decoration: BoxDecoration(
              color: AppColors.primary.withValues(alpha: 0.1),
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.notifications_none_outlined,
              color: AppColors.primary,
              size: 44,
            ),
          ),
          const SizedBox(height: 18),
          Text(
            l.noNotifications,
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w700,
              fontFamily: 'Rabar',
              color: isDark ? Colors.white70 : Colors.black54,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'کاتێک پۆستێکی نوێ بڵاودەکرێتەوە ئاگادار دەبیتەوە',
            style: TextStyle(
              fontSize: 12,
              fontFamily: 'Rabar',
              color: isDark ? Colors.white38 : Colors.black38,
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildShimmer(bool isDark) {
    return ListView.builder(
      padding: const EdgeInsets.fromLTRB(16, 8, 16, 100),
      itemCount: 6,
      itemBuilder: (_, __) => Padding(
        padding: const EdgeInsets.only(bottom: 10),
        child: Container(
          height: 96,
          decoration: BoxDecoration(
            color: isDark ? const Color(0xFF131D2E) : Colors.white,
            borderRadius: BorderRadius.circular(20),
          ),
          child: const ShimmerBox(
            width: double.infinity,
            height: 96,
            borderRadius: 20,
          ),
        ),
      ),
    );
  }

  String _formatTime(String? dateStr) {
    if (dateStr == null) return '';
    try {
      final dt = DateTime.parse(dateStr).toLocal();
      final diff = DateTime.now().difference(dt);
      if (diff.inMinutes < 1) return 'هەر ئێستا';
      if (diff.inMinutes < 60) return '${diff.inMinutes} خولەک لەمەوبەر';
      if (diff.inHours < 24) return '${diff.inHours} کاتژمێر لەمەوبەر';
      if (diff.inDays == 1) return 'دوێنێ';
      if (diff.inDays < 7) return '${diff.inDays} ڕۆژ لەمەوبەر';
      return '${dt.day}/${dt.month}/${dt.year}';
    } catch (_) {
      return '';
    }
  }
}
