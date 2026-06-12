import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../core/constants/app_colors.dart';
import '../../core/constants/app_constants.dart';
import 'package:provider/provider.dart';
import '../../core/localization/app_localizations.dart';
import '../../providers/connectivity_provider.dart';
import '../../shared/widgets/common_widgets.dart';

class MainShell extends StatelessWidget {
  final Widget child;
  const MainShell({super.key, required this.child});

  int _locationToIndex(String location) {
    if (location.startsWith('/news')) return 0;
    if (location.startsWith('/teachers')) return 1;
    if (location.startsWith('/cvs')) return 3;
    if (location.startsWith('/profile')) return 4;
    return 2; // home is default center
  }

  void _onTap(BuildContext context, int index) {
    switch (index) {
      case 0:
        context.go('/news');
        break;
      case 1:
        context.go('/teachers');
        break;
      case 2:
        context.go('/home');
        break;
      case 3:
        context.go('/cvs');
        break;
      case 4:
        context.go('/profile');
        break;
    }
  }

  @override
  Widget build(BuildContext context) {
    final l = AppLocalizations.of(context);
    final location = GoRouterState.of(context).matchedLocation;
    final currentIndex = _locationToIndex(location);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    final items = [
      _NavItem(
          icon: Icons.article_outlined,
          activeIcon: Icons.article_rounded,
          label: l.news),
      _NavItem(
          icon: Icons.school_outlined,
          activeIcon: Icons.school_rounded,
          label: l.teachers),
      _NavItem(
          icon: Icons.home_outlined,
          activeIcon: Icons.home_rounded,
          label: l.home,
          isMain: true),
      _NavItem(
          icon: Icons.work_outline_rounded,
          activeIcon: Icons.work_rounded,
          label: l.cvBank),
      _NavItem(
          icon: Icons.person_outline_rounded,
          activeIcon: Icons.person_rounded,
          label: l.profile),
    ];

    return PopScope(
      canPop: false,
      child: Scaffold(
        extendBody: true,
        body: Column(
          children: [
            Consumer<ConnectivityProvider>(
              builder: (context, connectivity, _) {
                if (connectivity.isOffline) {
                  return const OfflineBanner();
                }
                return const SizedBox.shrink();
              },
            ),
            Expanded(child: child),
          ],
        ),
      bottomNavigationBar: Padding(
        padding: const EdgeInsets.only(left: 24, right: 24, bottom: 24),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(32),
          child: BackdropFilter(
            filter: ImageFilter.blur(sigmaX: 15, sigmaY: 15),
            child: Container(
              height: 70,
              decoration: BoxDecoration(
                color: (isDark ? AppColors.darkCard : Colors.white)
                    .withValues(alpha: 0.7),
                borderRadius: BorderRadius.circular(32),
                border: Border.all(
                  color:
                      (isDark ? Colors.white : Colors.black).withValues(alpha: 0.15),
                  width: 1,
                ),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.05),
                    blurRadius: 10,
                    offset: const Offset(0, 5),
                  ),
                ],
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: List.generate(items.length, (i) {
                  final item = items[i];
                  final isActive = currentIndex == i;

                  if (item.isMain) {
                    return GestureDetector(
                      onTap: () => _onTap(context, i),
                      child: Container(
                        width: 44,
                        height: 44,
                        decoration: BoxDecoration(
                          gradient: AppColors.primaryGradient,
                          shape: BoxShape.circle,
                          boxShadow: [
                            BoxShadow(
                              color: AppColors.primary.withValues(alpha: 0.35),
                              blurRadius: 10,
                              offset: const Offset(0, 4),
                            ),
                          ],
                        ),
                        child: Icon(
                          isActive ? item.activeIcon : item.icon,
                          color: Colors.white,
                          size: 24,
                        ),
                      ),
                    );
                  }

                  return GestureDetector(
                    onTap: () => _onTap(context, i),
                    behavior: HitTestBehavior.opaque,
                    child: AnimatedContainer(
                      duration: AppConstants.fast,
                      padding: const EdgeInsets.symmetric(
                          horizontal: 10, vertical: 8),
                      decoration: BoxDecoration(
                        color: isActive
                            ? AppColors.primary.withValues(alpha: 0.15)
                            : Colors.transparent,
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(
                            isActive ? item.activeIcon : item.icon,
                            color: isActive
                                ? AppColors.primary
                                : (isDark
                                    ? AppColors.textGrey
                                    : AppColors.textMuted),
                            size: 24,
                          ),
                          if (isActive) ...[
                            const SizedBox(width: 6),
                            Flexible(
                              child: Text(
                                item.label,
                                overflow: TextOverflow.ellipsis,
                                maxLines: 1,
                                style: const TextStyle(
                                  fontSize: 12,
                                  fontWeight: FontWeight.w700,
                                  color: AppColors.primary,
                                  fontFamily: 'Rabar',
                                ),
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
                  );
                }),
              ),
            ),
          ),
        ),
      ),
    ),
  );
  }
}

class _NavItem {
  final IconData icon;
  final IconData activeIcon;
  final String label;
  final bool isMain;
  const _NavItem({
    required this.icon,
    required this.activeIcon,
    required this.label,
    this.isMain = false,
  });
}
