import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../core/constants/app_constants.dart';
import '../../data/models/institution_model.dart';
import '../../data/models/teacher_model.dart';
import '../../data/models/cv_model.dart';
import '../../providers/institutions_provider.dart';

/// =====================
/// INSTITUTION CARD
/// =====================
class InstitutionCard extends StatelessWidget {
  final InstitutionModel institution;
  final String lang;
  final bool isFavorite;
  final VoidCallback? onTap;
  final VoidCallback? onFavorite;

  const InstitutionCard({
    super.key,
    required this.institution,
    required this.lang,
    this.isFavorite = false,
    this.onTap,
    this.onFavorite,
  });

  @override
  Widget build(BuildContext context) {
    final typeColor = AppColors.typeColor(institution.type);
    final institutionName = institution.name(lang);
    final rawType = institution.type ?? '';

    final prov = Provider.of<InstitutionsProvider>(context, listen: false);

    String typeLabel = rawType.replaceAll('_', ' ');
    String emoji = '🏫';

    try {
      final typeModel =
          prov.institutionTypes.firstWhere((t) => t.key == rawType);
      typeLabel = lang == 'ku'
          ? typeModel.name
          : (lang == 'ar'
              ? (typeModel.nameAr ?? typeModel.name)
              : (typeModel.nameEn ?? typeModel.name));
      if (typeModel.emoji != null && typeModel.emoji!.isNotEmpty) {
        emoji = typeModel.emoji!;
      }
    } catch (_) {
      typeLabel = AppConstants.institutionTypes[rawType]?[lang] ?? typeLabel;
      emoji = AppConstants.institutionTypes[rawType]?['emoji'] ?? emoji;
    }

    final hasPhone = institution.phone != null && institution.phone!.isNotEmpty;
    final hasWeb = institution.web != null && institution.web!.isNotEmpty;
    final hasSocial = [
      institution.fb,
      institution.ig,
      institution.tg,
      institution.wa
    ].any((s) => s != null && s.isNotEmpty);

    // Immersive full-image card: the whole tile is the photo, with a
    // frosted-glass info panel floating at the bottom.
    final hasContact = hasPhone || hasWeb || hasSocial;

    return GestureDetector(
      onTap: onTap,
      child: ClipRRect(
        borderRadius: BorderRadius.circular(20),
        child: Stack(
          fit: StackFit.expand,
          children: [
            // ── Full-bleed background image ──
            institution.imgUrl.isNotEmpty
                ? CachedNetworkImage(
                    imageUrl: institution.imgUrl,
                    fit: BoxFit.cover,
                    errorWidget: (_, __, ___) =>
                        _InstCardFallback(typeColor: typeColor, emoji: emoji),
                  )
                : _InstCardFallback(typeColor: typeColor, emoji: emoji),

            // ── Cinematic dark gradient for legibility ──
            Positioned.fill(
              child: DecoratedBox(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    stops: const [0.0, 0.3, 0.6, 1.0],
                    colors: [
                      Colors.black.withValues(alpha: 0.4),
                      Colors.transparent,
                      Colors.black.withValues(alpha: 0.2),
                      Colors.black.withValues(alpha: 0.95),
                    ],
                  ),
                ),
              ),
            ),

            // ── Top bar: Views & Favorite ──
            Positioned(
              top: 10,
              left: 10,
              right: 10,
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  _ViewsBadge(views: institution.views),
                  GestureDetector(
                    onTap: onFavorite,
                    child: Container(
                      width: 26,
                      height: 26,
                      decoration: BoxDecoration(
                        color: isFavorite
                            ? const Color(0xFFFF4757).withValues(alpha: 0.95)
                            : Colors.black.withValues(alpha: 0.3),
                        shape: BoxShape.circle,
                        border: Border.all(
                          color: Colors.white.withValues(alpha: 0.2),
                        ),
                      ),
                      child: Icon(
                        isFavorite
                            ? Icons.favorite_rounded
                            : Icons.favorite_border_rounded,
                        color: Colors.white,
                        size: 13,
                      ),
                    ),
                  ),
                ],
              ),
            ),

            // ── Bottom Info Overlay ──
            Positioned(
              left: 12,
              right: 12,
              bottom: 12,
              child: Directionality(
                textDirection: TextDirection.rtl,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    // Type Chip
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 8, vertical: 3),
                      decoration: BoxDecoration(
                        color: typeColor.withValues(alpha: 0.9),
                        borderRadius: BorderRadius.circular(6),
                      ),
                      child: Text(
                        '$emoji $typeLabel',
                        style: const TextStyle(
                          fontSize: 9.0,
                          fontWeight: FontWeight.w800,
                          color: Colors.white,
                          fontFamily: 'Rabar',
                        ),
                      ),
                    ),
                    const SizedBox(height: 6),
                    // Name
                    Text(
                      institutionName,
                      style: const TextStyle(
                        fontSize: 13.5,
                        fontWeight: FontWeight.w900,
                        color: Colors.white,
                        fontFamily: 'Rabar',
                        height: 1.2,
                        shadows: [
                          Shadow(blurRadius: 4, color: Colors.black87),
                        ],
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    // City & Contact Info row
                    if ((institution.city != null && institution.city!.isNotEmpty) || hasContact) ...[
                      const SizedBox(height: 6),
                      Row(
                        children: [
                          if (institution.city != null && institution.city!.isNotEmpty) ...[
                            const Icon(Icons.location_on_rounded, size: 10, color: Colors.white70),
                            const SizedBox(width: 3),
                            Expanded(
                              child: Text(
                                institution.city!,
                                style: const TextStyle(
                                  fontSize: 10.5,
                                  color: Colors.white70,
                                  fontFamily: 'Rabar',
                                  fontWeight: FontWeight.w600,
                                ),
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                              ),
                            ),
                          ],
                          if (hasContact) ...[
                            if (hasPhone)
                              const _InfoBadge(icon: Icons.phone_rounded, color: AppColors.success),
                            if (hasWeb)
                              const _InfoBadge(icon: Icons.language_rounded, color: AppColors.accentGold),
                            if (hasSocial)
                              const _InfoBadge(icon: Icons.share_rounded, color: Color(0xFFE05C8A)),
                          ]
                        ],
                      ),
                    ],
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ── helpers ──────────────────────────────────────────────────────────────────

class _InstCardFallback extends StatelessWidget {
  final Color typeColor;
  final String emoji;
  const _InstCardFallback({required this.typeColor, required this.emoji});

  @override
  Widget build(BuildContext context) => Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [typeColor, typeColor.withValues(alpha: 0.55)],
          ),
        ),
        child: Center(child: Text(emoji, style: const TextStyle(fontSize: 46))),
      );
}

class _InfoBadge extends StatelessWidget {
  final IconData icon;
  final Color color;
  const _InfoBadge({required this.icon, required this.color});

  @override
  Widget build(BuildContext context) => Container(
        margin: const EdgeInsets.only(right: 5),
        width: 24,
        height: 24,
        decoration: BoxDecoration(
          color: color.withValues(alpha: 0.16),
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: color.withValues(alpha: 0.30)),
        ),
        child: Icon(icon, size: 12, color: color),
      );
}

/// Frosted "views" pill shown on the institution image.
class _ViewsBadge extends StatelessWidget {
  final int views;
  const _ViewsBadge({required this.views});

  /// Converts western digits to Arabic-Indic and abbreviates thousands.
  static String _format(int n) {
    String s;
    if (n >= 1000000) {
      s = '${(n / 1000000).toStringAsFixed(n % 1000000 == 0 ? 0 : 1)}M';
    } else if (n >= 1000) {
      s = '${(n / 1000).toStringAsFixed(n % 1000 == 0 ? 0 : 1)}k';
    } else {
      s = '$n';
    }
    const western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const eastern = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    for (var i = 0; i < western.length; i++) {
      s = s.replaceAll(western[i], eastern[i]);
    }
    return s;
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: Colors.black.withValues(alpha: 0.42),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.white.withValues(alpha: 0.22)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          const Icon(Icons.visibility_rounded, size: 12, color: Colors.white),
          const SizedBox(width: 4),
          Text(
            _format(views),
            style: const TextStyle(
              fontSize: 10,
              fontWeight: FontWeight.w800,
              color: Colors.white,
              fontFamily: 'Rabar',
            ),
          ),
        ],
      ),
    );
  }
}

/// =====================
/// INSTITUTION CARD - HORIZONTAL (for featured slider)
/// =====================
class FeaturedInstitutionCard extends StatelessWidget {
  final InstitutionModel institution;
  final String lang;
  final VoidCallback? onTap;

  const FeaturedInstitutionCard({
    super.key,
    required this.institution,
    required this.lang,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final typeColor = AppColors.typeColor(institution.type);
    final institutionName = institution.name(lang);
    final rawType = institution.type ?? '';

    final prov = Provider.of<InstitutionsProvider>(context, listen: false);

    String emoji = '🏫';
    try {
      final typeModel =
          prov.institutionTypes.firstWhere((t) => t.key == rawType);
      if (typeModel.emoji != null && typeModel.emoji!.isNotEmpty) {
        emoji = typeModel.emoji!;
      }
    } catch (_) {
      emoji = AppConstants.institutionTypes[rawType]?['emoji'] ?? emoji;
    }

    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 240,
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [typeColor, typeColor.withValues(alpha: 0.6)],
          ),
          borderRadius: BorderRadius.circular(AppConstants.radiusXl),
          boxShadow: [
            BoxShadow(
              color: typeColor.withValues(alpha: 0.4),
              blurRadius: 20,
              offset: const Offset(0, 8),
            ),
          ],
        ),
        child: Stack(
          children: [
            // Background image
            if (institution.imgUrl.isNotEmpty)
              ClipRRect(
                borderRadius: BorderRadius.circular(AppConstants.radiusXl),
                child: CachedNetworkImage(
                  imageUrl: institution.imgUrl,
                  width: 240,
                  height: double.infinity,
                  fit: BoxFit.cover,
                  color: Colors.black.withValues(alpha: 0.4),
                  colorBlendMode: BlendMode.darken,
                  errorWidget: (_, __, ___) => const SizedBox(),
                ),
              ),
            // Content
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.end,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(emoji, style: const TextStyle(fontSize: 32)),
                  const SizedBox(height: 8),
                  Text(
                    institutionName,
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      color: Colors.white,
                      fontFamily: 'Rabar',
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  if (institution.city != null)
                    Row(
                      children: [
                        const Icon(Icons.location_on_outlined,
                            size: 12, color: Colors.white70),
                        const SizedBox(width: 2),
                        Text(
                          institution.city!,
                          style: const TextStyle(
                            fontSize: 12,
                            color: Colors.white70,
                            fontFamily: 'Rabar',
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
}

/// =====================
/// TEACHER CARD
/// =====================
class TeacherCard extends StatelessWidget {
  final TeacherModel teacher;
  final String lang;
  final VoidCallback? onTap;
  final VoidCallback? onContact;
  final bool isFavorite; // Kept for logic if needed elsewhere, but UI removed
  final VoidCallback? onFavorite;

  const TeacherCard({
    super.key,
    required this.teacher,
    required this.lang,
    this.onTap,
    this.onContact,
    this.isFavorite = false,
    this.onFavorite,
  });

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final isUniversity = teacher.type == 'university';
    final typeColor =
        isUniversity ? AppColors.primary : const Color(0xFF10B981);
    final typeIcon =
        isUniversity ? Icons.school_rounded : Icons.menu_book_rounded;

    final String typeLabel;
    if (teacher.subject != null && teacher.subject!.trim().isNotEmpty) {
      typeLabel = 'مامۆستای ${teacher.subject!.trim()}';
    } else if (teacher.typeLabel != null && teacher.typeLabel!.isNotEmpty) {
      typeLabel = teacher.typeLabel!;
    } else {
      typeLabel = isUniversity ? 'مامۆستای زانکۆ' : 'مامۆستای قوتابخانە';
    }

    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: const EdgeInsets.only(bottom: 14),
        decoration: BoxDecoration(
          color: isDark ? const Color(0xFF1E1E1E) : Colors.white,
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: isDark ? 0.2 : 0.03),
              blurRadius: 12,
              offset: const Offset(0, 4),
            ),
          ],
          border: Border.all(
            color: isDark ? const Color(0xFF2C2C2C) : const Color(0xFFEDF2F7),
            width: 1,
          ),
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(20),
          child: Stack(
            children: [
              // Subtle modern gradient background flare
              Positioned(
                right: -30,
                top: -30,
                child: Container(
                  width: 120,
                  height: 120,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: typeColor.withValues(alpha: 0.04),
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(14),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    // Brand New Premium Circular Avatar Frame
                    Container(
                      width: 60,
                      height: 60,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: isDark
                            ? const Color(0xFF2C2C2C)
                            : const Color(0xFFF1F5F9),
                      ),
                      child: Stack(
                        fit: StackFit.expand,
                        children: [
                          ClipOval(
                            child: teacher.photoUrl.isNotEmpty
                                ? CachedNetworkImage(
                                    imageUrl: teacher.photoUrl,
                                    fit: BoxFit.cover,
                                    errorWidget: (_, __, ___) =>
                                        _avatarFallback(
                                            teacher.name, typeColor),
                                  )
                                : _avatarFallback(teacher.name, typeColor),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 14),

                    // Main info
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            teacher.name,
                            style: TextStyle(
                              fontSize: 15.5,
                              fontWeight: FontWeight.w900,
                              color: isDark
                                  ? Colors.white
                                  : const Color(0xFF1A202C),
                              fontFamily: 'Rabar',
                              height: 1.25,
                            ),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                          const SizedBox(height: 6),

                          // Modern Type Chip
                          Row(
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 8, vertical: 4),
                                decoration: BoxDecoration(
                                  color: typeColor.withValues(alpha: 0.1),
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                child: Row(
                                  mainAxisSize: MainAxisSize.min,
                                  children: [
                                    Icon(typeIcon, size: 11, color: typeColor),
                                    const SizedBox(width: 4),
                                    Text(
                                      typeLabel,
                                      style: TextStyle(
                                        fontSize: 10.5,
                                        fontWeight: FontWeight.w800,
                                        color: typeColor,
                                        fontFamily: 'Rabar',
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              if (teacher.city != null &&
                                  teacher.city!.isNotEmpty) ...[
                                const SizedBox(width: 6),
                                Container(
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 8, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: isDark
                                        ? const Color(0xFF2C2C2C)
                                        : const Color(0xFFF1F5F9),
                                    borderRadius: BorderRadius.circular(8),
                                  ),
                                  child: Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      Icon(Icons.location_on_rounded,
                                          size: 11,
                                          color: isDark
                                              ? Colors.white70
                                              : const Color(0xFF64748B)),
                                      const SizedBox(width: 4),
                                      Text(
                                        teacher.city!,
                                        style: TextStyle(
                                          fontSize: 10.5,
                                          fontWeight: FontWeight.w700,
                                          color: isDark
                                              ? Colors.white70
                                              : const Color(0xFF64748B),
                                          fontFamily: 'Rabar',
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ],
                          ),
                          const SizedBox(height: 8),

                          // Rating & Stats Row
                          Row(
                            children: [
                              if (teacher.experienceYears != null) ...[
                                Row(
                                  children: [
                                    const Icon(Icons.work_history_rounded,
                                        size: 12, color: Color(0xFF3B82F6)),
                                    const SizedBox(width: 4),
                                    Text(
                                      '${teacher.experienceYears} ساڵ ئەزموون',
                                      style: TextStyle(
                                        fontSize: 11,
                                        fontWeight: FontWeight.w700,
                                        color: isDark
                                            ? Colors.white60
                                            : const Color(0xFF4A5568),
                                        fontFamily: 'Rabar',
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(width: 12),
                              ],
                              Row(
                                children: [
                                  const Icon(Icons.star_rounded,
                                      size: 13, color: Color(0xFFF59E0B)),
                                  const SizedBox(width: 3),
                                  Text(
                                    '٤.٩',
                                    style: TextStyle(
                                      fontSize: 11,
                                      fontWeight: FontWeight.w800,
                                      color: isDark
                                          ? Colors.white60
                                          : const Color(0xFF4A5568),
                                      fontFamily: 'Rabar',
                                    ),
                                  ),
                                ],
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 10),

                    Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: isDark
                            ? const Color(0xFF2D2D2D)
                            : const Color(0xFFF1F5F9),
                        shape: BoxShape.circle,
                      ),
                      child: Icon(
                        Icons.arrow_forward_ios_rounded,
                        size: 12,
                        color:
                            isDark ? Colors.white70 : const Color(0xFF64748B),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _avatarFallback(String name, Color color) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [color, color.withValues(alpha: 0.7)],
        ),
      ),
      child: Center(
        child: Text(
          _getInitials(name),
          style: const TextStyle(
            fontSize: 22,
            fontWeight: FontWeight.w900,
            color: Colors.white,
            fontFamily: 'Rabar',
          ),
        ),
      ),
    );
  }

  String _getInitials(String name) {
    final parts =
        name.split(RegExp(r'\s+')).where((e) => e.isNotEmpty).toList();
    if (parts.length >= 2) {
      final p1 = parts[0].replaceAll('.', '');
      final p2 = parts[1].replaceAll('.', '');
      return '${p1.isNotEmpty ? p1[0] : ''}${p2.isNotEmpty ? p2[0] : ''}'
          .trim();
    }
    final p = name.replaceAll('.', '').trim();
    return p.isNotEmpty ? p[0] : '?';
  }
}

/// =====================
/// CV CARD
/// =====================
class CvCard extends StatelessWidget {
  final CvModel cv;
  final VoidCallback? onTap;

  const CvCard({super.key, required this.cv, this.onTap});

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: const EdgeInsets.only(bottom: 14),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isDark ? const Color(0xFF1E1E1E) : Colors.white,
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: isDark ? 0.2 : 0.04),
              blurRadius: 15,
              offset: const Offset(0, 5),
            ),
          ],
          border: Border.all(
            color: isDark ? const Color(0xFF2C2C2C) : const Color(0xFFEDF2F7),
            width: 1,
          ),
        ),
        child: Row(
          children: [
            // ── Avatar ──
            SizedBox(
              width: 68,
              height: 68,
              child: Stack(
                fit: StackFit.expand,
                children: [
                  ClipOval(
                    child: cv.photoUrl.isNotEmpty
                        ? CachedNetworkImage(
                            imageUrl: cv.photoUrl,
                            fit: BoxFit.cover,
                            errorWidget: (_, __, ___) => _initials(cv.name),
                          )
                        : _initials(cv.name),
                  ),
                ],
              ),
            ),
            const SizedBox(width: 16),

            // ── Content ──
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    cv.name,
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w900,
                      color: isDark ? Colors.white : const Color(0xFF1A202C),
                      fontFamily: 'Rabar',
                      height: 1.2,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 6),
                  if (cv.field != null)
                    Text(
                      cv.field!,
                      style: const TextStyle(
                        fontSize: 13,
                        fontWeight: FontWeight.w700,
                        color: AppColors.primary,
                        fontFamily: 'Rabar',
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                  const SizedBox(height: 10),

                  // Row of badges
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: [
                      if (cv.city != null)
                        _CvSmallBadge(
                          icon: Icons.location_on_rounded,
                          text: cv.city!,
                          color: const Color(0xFF64748B),
                          isDark: isDark,
                        ),
                      if (cv.educationLevel != null)
                        _CvSmallBadge(
                          icon: Icons.school_rounded,
                          text: cv.educationLevel!,
                          color: const Color(0xFF8B5CF6),
                          isDark: isDark,
                        ),
                    ],
                  ),
                ],
              ),
            ),

            // ── Action Indicator ──
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color:
                    isDark ? const Color(0xFF2D2D2D) : const Color(0xFFF1F5F9),
                shape: BoxShape.circle,
              ),
              child: Icon(
                Icons.arrow_forward_ios_rounded,
                size: 12,
                color: isDark ? Colors.white70 : const Color(0xFF64748B),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _initials(String name) {
    return Center(
      child: Text(
        name.isNotEmpty ? name[0].toUpperCase() : '?',
        style: const TextStyle(
          fontSize: 24,
          fontWeight: FontWeight.w900,
          color: AppColors.primary,
          fontFamily: 'Rabar',
        ),
      ),
    );
  }
}

class _CvSmallBadge extends StatelessWidget {
  final IconData icon;
  final String text;
  final Color color;
  final bool isDark;

  const _CvSmallBadge({
    required this.icon,
    required this.text,
    required this.color,
    required this.isDark,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF2C2C2C) : const Color(0xFFF1F5F9),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 11, color: isDark ? Colors.white70 : color),
          const SizedBox(width: 4),
          Flexible(
            child: Text(
              text,
              style: TextStyle(
                fontSize: 10,
                fontWeight: FontWeight.w700,
                color: isDark ? Colors.white70 : color,
                fontFamily: 'Rabar',
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ),
        ],
      ),
    );
  }
}
