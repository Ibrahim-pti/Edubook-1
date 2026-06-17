import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../providers/theme_provider.dart';
import '../../data/models/academic_event_model.dart';
import '../../data/services/api_service.dart';
import '../../shared/widgets/common_widgets.dart';

class AcademicCalendarScreen extends StatefulWidget {
  const AcademicCalendarScreen({super.key});

  @override
  State<AcademicCalendarScreen> createState() => _AcademicCalendarScreenState();
}

class _AcademicCalendarScreenState extends State<AcademicCalendarScreen>
    with SingleTickerProviderStateMixin {
  final ApiService _api = ApiService();
  late AnimationController _animCtrl;
  late Animation<double> _fadeAnim;

  List<AcademicEventModel> _events = [];
  bool _loading = true;
  String? _error;
  String _selectedFilter = 'all'; // 'all', 'holiday', 'exam', 'deadline'

  // Dynamic current date to calculate remaining days
  final DateTime _today = DateTime.now();

  @override
  void initState() {
    super.initState();
    _animCtrl = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 600),
    );
    _fadeAnim = CurvedAnimation(parent: _animCtrl, curve: Curves.easeInOut);
    _fetchCalendar();
  }

  @override
  void dispose() {
    _animCtrl.dispose();
    super.dispose();
  }

  Future<void> _fetchCalendar() async {
    setState(() {
      _loading = true;
      _error = null;
    });

    final res = await _api.getAcademicCalendar();

    if (!mounted) return;

    if (res.success && res.data != null) {
      setState(() {
        _events = res.data!;
        _loading = false;
      });
      _animCtrl.forward();
    } else {
      setState(() {
        _error = res.error ?? 'کێشەیەک لە بارکردنی زانیارییەکان ڕوویدا.';
        _loading = false;
      });
    }
  }

  List<AcademicEventModel> get _filteredEvents {
    // Events are already sorted from backend, but let's be sure
    final sorted = List<AcademicEventModel>.from(_events)
      ..sort((a, b) => a.date.compareTo(b.date));

    if (_selectedFilter == 'all') {
      return sorted;
    }
    return sorted.where((e) => e.category == _selectedFilter).toList();
  }

  AcademicEventModel? get _nextUpcomingEvent {
    final sorted = List<AcademicEventModel>.from(_events)
      ..sort((a, b) => a.date.compareTo(b.date));

    for (final event in sorted) {
      if (event.date.isAfter(_today) ||
          DateUtils.isSameDay(event.date, _today)) {
        return event;
      }
    }
    return null;
  }

  int _calculateRemainingDays(DateTime date) {
    final eventDate = DateTime(date.year, date.month, date.day);
    final todayDate = DateTime(_today.year, _today.month, _today.day);
    return eventDate.difference(todayDate).inDays;
  }

  String _formatKurdishMonth(int month) {
    const months = {
      1: 'کانوونی دووەم',
      2: 'شوبات',
      3: 'ئادار',
      4: 'نیسان',
      5: 'ئایار',
      6: 'حوزەیران',
      7: 'تەممووز',
      8: 'ئاب',
      9: 'ئەیلوول',
      10: 'تشرینی یەکەم',
      11: 'تشرینی دووەم',
      12: 'کانوونی یەکەم',
    };
    return months[month] ?? '';
  }

  Color _getCategoryColor(String category) {
    switch (category) {
      case 'holiday':
        return Colors.teal;
      case 'exam':
        return AppColors.primary;
      case 'deadline':
        return Colors.deepOrange;
      default:
        return AppColors.textMuted;
    }
  }

  String _getCategoryLabel(String category) {
    switch (category) {
      case 'holiday':
        return 'پشوو';
      case 'exam':
        return 'تاقیکردنەوە یان قوتابخانە';
      case 'deadline':
        return 'پێشکەشکردن و وادە';
      default:
        return '';
    }
  }

  IconData _getIconData(String? iconName) {
    switch (iconName) {
      case 'brightness_high_rounded':
        return Icons.brightness_high_rounded;
      case 'assignment_turned_in_rounded':
        return Icons.assignment_turned_in_rounded;
      case 'analytics_rounded':
        return Icons.analytics_rounded;
      case 'input_rounded':
        return Icons.input_rounded;
      case 'menu_book_rounded':
        return Icons.menu_book_rounded;
      case 'school_rounded':
        return Icons.school_rounded;
      case 'account_balance_rounded':
        return Icons.account_balance_rounded;
      case 'star_rounded':
        return Icons.star_rounded;
      case 'assignment_rounded':
        return Icons.assignment_rounded;
      case 'ac_unit_rounded':
        return Icons.ac_unit_rounded;
      case 'celebration_rounded':
        return Icons.celebration_rounded;
      case 'note_alt_rounded':
        return Icons.note_alt_rounded;
      case 'hotel_rounded':
        return Icons.hotel_rounded;
      case 'play_arrow_rounded':
        return Icons.play_arrow_rounded;
      case 'accessibility_new_rounded':
        return Icons.accessibility_new_rounded;
      case 'forest_rounded':
        return Icons.forest_rounded;
      case 'check_circle_rounded':
        return Icons.check_circle_rounded;
      case 'draw_rounded':
        return Icons.draw_rounded;
      default:
        return Icons.calendar_month_rounded;
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Provider.of<ThemeProvider>(context);
    final isDark = theme.isDark;
    final filtered = _filteredEvents;
    final nextEvent = _nextUpcomingEvent;

    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: isDark ? SystemUiOverlayStyle.light : SystemUiOverlayStyle.dark,
      child: Scaffold(
        backgroundColor: isDark ? const Color(0xFF0A0F1E) : const Color(0xFFF2F5FB),
        body: Column(
          children: [
            _buildHeader(isDark),
            Expanded(
              child: _loading
                  ? _buildShimmerLoading(isDark)
                  : _error != null
                      ? _buildErrorState(isDark)
                      : RefreshIndicator(
                          onRefresh: _fetchCalendar,
                          color: AppColors.primary,
                          child: CustomScrollView(
                            physics: const BouncingScrollPhysics(),
                            slivers: [
                              // Upcoming Event Highlight Card
                              if (nextEvent != null)
                                SliverToBoxAdapter(
                                  child: _buildUpcomingHighlight(nextEvent, isDark),
                                ),

                              // Filters tabs
                              SliverToBoxAdapter(
                                child: _buildFiltersRow(isDark),
                              ),

                              // Main Timeline List
                              filtered.isEmpty
                                  ? SliverFillRemaining(
                                      hasScrollBody: false,
                                      child: _buildEmptyState(isDark),
                                    )
                                  : SliverPadding(
                                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                                      key: ValueKey(_selectedFilter),
                                      sliver: SliverList(
                                        delegate: SliverChildBuilderDelegate(
                                          (context, index) {
                                            final event = filtered[index];
                                            return FadeTransition(
                                              opacity: _fadeAnim,
                                              child: _buildEventCard(event, isDark, index),
                                            );
                                          },
                                          childCount: filtered.length,
                                        ),
                                      ),
                                    ),
                              const SliverToBoxAdapter(
                                child: SizedBox(height: 100),
                              )
                            ],
                          ),
                        ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(bool isDark) {
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
            onTap: () => context.canPop() ? context.pop() : context.go('/home'),
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
                const Text(
                  'ڕۆژژمێری ئەکادیمی',
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.w900,
                    fontFamily: 'Rabar',
                    color: AppColors.primary,
                  ),
                ),
                Text(
                  'پشوو و وادە فەرمییەکانی خوێندنی کوردستان',
                  style: TextStyle(
                    fontSize: 11,
                    fontFamily: 'Rabar',
                    color: isDark ? Colors.white38 : Colors.black45,
                  ),
                ),
              ],
            ),
          ),
          Container(
            width: 42,
            height: 42,
            decoration: BoxDecoration(
              color: AppColors.primary.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(14),
            ),
            child: const Icon(
              Icons.calendar_month_rounded,
              color: AppColors.primary,
              size: 22,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildUpcomingHighlight(AcademicEventModel event, bool isDark) {
    final title = event.title;
    final date = event.date;
    final desc = event.description;
    final cat = event.category;
    final remainingDays = _calculateRemainingDays(date);

    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 8),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: isDark
              ? [const Color(0xFF16203A), const Color(0xFF1E2D48)]
              : [Colors.white, const Color(0xFFFDF8EE)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(24),
        border: Border.all(
          color: AppColors.primary.withValues(alpha: 0.3),
          width: 1.5,
        ),
        boxShadow: [
          BoxShadow(
            color: AppColors.primary.withValues(alpha: isDark ? 0.08 : 0.04),
            blurRadius: 16,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(24),
        child: Stack(
          children: [
            Positioned(
              right: -30,
              top: -30,
              child: Icon(
                _getIconData(event.icon),
                size: 140,
                color: _getCategoryColor(cat).withValues(alpha: 0.06),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: _getCategoryColor(cat).withValues(alpha: 0.15),
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(
                            color: _getCategoryColor(cat).withValues(alpha: 0.3),
                            width: 1,
                          ),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Container(
                              width: 8,
                              height: 8,
                              decoration: BoxDecoration(
                                color: _getCategoryColor(cat),
                                shape: BoxShape.circle,
                              ),
                            ),
                            const SizedBox(width: 6),
                            const Text(
                              'ڕووداوی نزیکترین',
                              style: TextStyle(
                                fontSize: 11,
                                fontWeight: FontWeight.bold,
                                fontFamily: 'Rabar',
                                color: AppColors.primary,
                              ),
                            ),
                          ],
                        ),
                      ),
                      Text(
                        '${date.day}ی ${_formatKurdishMonth(date.month)}',
                        style: TextStyle(
                          fontSize: 13,
                          fontWeight: FontWeight.w700,
                          fontFamily: 'Rabar',
                          color: isDark ? Colors.white70 : Colors.black87,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 14),
                  Text(
                    title,
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.w900,
                      fontFamily: 'Rabar',
                      color: isDark ? Colors.white : AppColors.textDark,
                      height: 1.4,
                    ),
                  ),
                  if (desc.isNotEmpty) ...[
                    const SizedBox(height: 6),
                    Text(
                      desc,
                      style: TextStyle(
                        fontSize: 12,
                        fontFamily: 'Rabar',
                        color: isDark ? Colors.white60 : Colors.black54,
                        height: 1.5,
                      ),
                    ),
                  ],
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        remainingDays == 0
                            ? 'ئەمڕۆیە 🎉'
                            : remainingDays == 1
                                ? 'سبەینێیە ⏱️'
                                : 'ماوە بۆی: $remainingDays ڕۆژ ⏳',
                        style: TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.w800,
                          fontFamily: 'Rabar',
                          color: remainingDays <= 3 ? Colors.redAccent : AppColors.primary,
                        ),
                      ),
                      GestureDetector(
                        onTap: () {
                          HapticFeedback.mediumImpact();
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text(
                                'ئاگادارکردنەوە بۆ ئەم ڕووداوە چالاک کرا.',
                                style: TextStyle(fontFamily: 'Rabar'),
                                textAlign: TextAlign.right,
                              ),
                              behavior: SnackBarBehavior.floating,
                            ),
                          );
                        },
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                          decoration: BoxDecoration(
                            color: AppColors.primary,
                            borderRadius: BorderRadius.circular(14),
                            boxShadow: [
                              BoxShadow(
                                color: AppColors.primary.withValues(alpha: 0.3),
                                blurRadius: 8,
                                offset: const Offset(0, 4),
                              ),
                            ],
                          ),
                          child: const Row(
                            children: [
                              Icon(Icons.notifications_active_rounded, color: Colors.white, size: 14),
                              SizedBox(width: 6),
                              Text(
                                'ئاگadارم بکەرەوە',
                                style: TextStyle(
                                  fontSize: 11,
                                  fontWeight: FontWeight.bold,
                                  fontFamily: 'Rabar',
                                  color: Colors.white,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFiltersRow(bool isDark) {
    final tabs = [
      {'key': 'all', 'label': 'سەرجەم'},
      {'key': 'holiday', 'label': 'پشووەکان'},
      {'key': 'exam', 'label': 'خوێندن و تاقیکردنەوە'},
      {'key': 'deadline', 'label': 'پێشکەشکردن'},
    ];

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
      child: Row(
        children: tabs.map((tab) {
          final isSelected = _selectedFilter == tab['key'];
          return GestureDetector(
            onTap: () {
              HapticFeedback.lightImpact();
              setState(() {
                _selectedFilter = tab['key']!;
              });
            },
            child: Container(
              margin: const EdgeInsets.only(left: 8),
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
              decoration: BoxDecoration(
                color: isSelected
                    ? AppColors.primary
                    : isDark
                        ? const Color(0xFF16203A)
                        : Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(
                  color: isSelected
                      ? AppColors.primary
                      : isDark
                          ? Colors.white.withValues(alpha: 0.05)
                          : Colors.black.withValues(alpha: 0.05),
                  width: 1,
                ),
                boxShadow: [
                  if (isSelected)
                    BoxShadow(
                      color: AppColors.primary.withValues(alpha: 0.25),
                      blurRadius: 8,
                      offset: const Offset(0, 4),
                    ),
                ],
              ),
              child: Text(
                tab['label']!,
                style: TextStyle(
                  fontSize: 12,
                  fontFamily: 'Rabar',
                  fontWeight: isSelected ? FontWeight.w900 : FontWeight.w600,
                  color: isSelected
                      ? Colors.white
                      : isDark
                          ? Colors.white70
                          : Colors.black87,
                ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildEventCard(AcademicEventModel event, bool isDark, int index) {
    final title = event.title;
    final date = event.date;
    final desc = event.description;
    final cat = event.category;
    final icon = _getIconData(event.icon);
    final remainingDays = _calculateRemainingDays(date);
    final isPast = remainingDays < 0;

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF131D2E) : Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: isPast
              ? (isDark ? Colors.white.withValues(alpha: 0.03) : Colors.black.withValues(alpha: 0.04))
              : _getCategoryColor(cat).withValues(alpha: 0.15),
          width: isPast ? 1 : 1.5,
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: isDark ? 0.15 : 0.03),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Date badge
            Container(
              width: 54,
              height: 62,
              decoration: BoxDecoration(
                color: isPast
                    ? (isDark ? Colors.white.withValues(alpha: 0.05) : Colors.grey.shade100)
                    : _getCategoryColor(cat).withValues(alpha: 0.08),
                borderRadius: BorderRadius.circular(14),
                border: Border.all(
                  color: isPast
                      ? Colors.transparent
                      : _getCategoryColor(cat).withValues(alpha: 0.2),
                  width: 1,
                ),
              ),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    date.day.toString(),
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.w900,
                      color: isPast
                          ? (isDark ? Colors.white38 : Colors.grey)
                          : _getCategoryColor(cat),
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    _formatKurdishMonth(date.month).split(' ').first,
                    style: TextStyle(
                      fontSize: 9,
                      fontWeight: FontWeight.w600,
                      fontFamily: 'Rabar',
                      color: isPast
                          ? (isDark ? Colors.white24 : Colors.grey.shade500)
                          : _getCategoryColor(cat).withValues(alpha: 0.8),
                    ),
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
            const SizedBox(width: 14),
            // Text Details
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
                            fontWeight: FontWeight.w800,
                            fontFamily: 'Rabar',
                            color: isPast
                                ? (isDark ? Colors.white38 : Colors.grey.shade500)
                                : (isDark ? Colors.white : AppColors.textDark),
                            decoration: isPast ? TextDecoration.lineThrough : null,
                          ),
                        ),
                      ),
                      const SizedBox(width: 6),
                      Icon(
                        icon,
                        size: 16,
                        color: isPast
                            ? (isDark ? Colors.white24 : Colors.grey.shade300)
                            : _getCategoryColor(cat),
                      ),
                    ],
                  ),
                  if (desc.isNotEmpty) ...[
                    const SizedBox(height: 5),
                    Text(
                      desc,
                      style: TextStyle(
                        fontSize: 11,
                        fontFamily: 'Rabar',
                        height: 1.5,
                        color: isPast
                            ? (isDark ? Colors.white24 : Colors.grey.shade400)
                            : (isDark ? Colors.white60 : Colors.black54),
                      ),
                    ),
                  ],
                  const SizedBox(height: 8),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: isPast
                              ? Colors.transparent
                              : _getCategoryColor(cat).withValues(alpha: 0.08),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          _getCategoryLabel(cat),
                          style: TextStyle(
                            fontSize: 9,
                            fontWeight: FontWeight.w700,
                            fontFamily: 'Rabar',
                            color: isPast
                                ? (isDark ? Colors.white24 : Colors.grey.shade400)
                                : _getCategoryColor(cat),
                          ),
                        ),
                      ),
                      Text(
                        isPast
                            ? 'کۆتایی هاتووە ✓'
                            : remainingDays == 0
                                ? 'ئەمڕۆ'
                                : remainingDays == 1
                                    ? 'سبەینێ'
                                    : '$remainingDays ڕۆژی ماوە',
                        style: TextStyle(
                          fontSize: 11,
                          fontWeight: FontWeight.w700,
                          fontFamily: 'Rabar',
                          color: isPast
                              ? (isDark ? Colors.white24 : Colors.grey.shade400)
                              : remainingDays <= 3
                                  ? Colors.redAccent
                                  : AppColors.primary,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyState(bool isDark) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            width: 80,
            height: 80,
            decoration: BoxDecoration(
              color: AppColors.primary.withValues(alpha: 0.1),
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.calendar_today_rounded,
              color: AppColors.primary,
              size: 38,
            ),
          ),
          const SizedBox(height: 16),
          const Text(
            'هیچ ڕووداوێک نەدۆزرایەوە',
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w700,
              fontFamily: 'Rabar',
              color: Colors.white70,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'تکایە فیلتەرێکی تر هەڵبژێرە',
            style: TextStyle(
              fontSize: 12,
              fontFamily: 'Rabar',
              color: isDark ? Colors.white38 : Colors.black38,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildErrorState(bool isDark) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: 80,
              height: 80,
              decoration: const BoxDecoration(
                color: Colors.redAccent,
                shape: BoxShape.circle,
              ),
              child: const Icon(
                Icons.error_outline_rounded,
                color: Colors.white,
                size: 40,
              ),
            ),
            const SizedBox(height: 18),
            Text(
              _error ?? 'هەڵەیەک ڕوویدا لە بارکردنی ڕۆژژمێر',
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w800,
                fontFamily: 'Rabar',
                color: isDark ? Colors.white : AppColors.textDark,
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 24),
            ElevatedButton.icon(
              onPressed: _fetchCalendar,
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(16),
                ),
              ),
              icon: const Icon(Icons.refresh_rounded, color: Colors.white),
              label: const Text(
                'دووبارە هەوڵبدەرەوە',
                style: TextStyle(
                  fontFamily: 'Rabar',
                  color: Colors.white,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildShimmerLoading(bool isDark) {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: 5,
      itemBuilder: (_, __) => const Padding(
        padding: EdgeInsets.only(bottom: 12),
        child: ShimmerBox(
          width: double.infinity,
          height: 100,
          borderRadius: 20,
        ),
      ),
    );
  }
}
