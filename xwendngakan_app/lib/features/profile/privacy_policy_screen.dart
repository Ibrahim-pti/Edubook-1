import 'package:flutter/material.dart';
import '../../core/constants/app_colors.dart';
import '../../core/localization/app_localizations.dart';

class PrivacyPolicyScreen extends StatelessWidget {
  const PrivacyPolicyScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    
    return Scaffold(
      backgroundColor: isDark ? AppColors.darkBg : const Color(0xFFF8F9FA),
      appBar: AppBar(
        title: Text(AppLocalizations.of(context).privacyPolicyTitle, style: const TextStyle(fontFamily: 'Rabar', fontWeight: FontWeight.bold)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildSection(
              AppLocalizations.of(context).privacySec1Title,
              AppLocalizations.of(context).privacySec1Body,
              isDark,
            ),
            _buildSection(
              AppLocalizations.of(context).privacySec2Title,
              AppLocalizations.of(context).privacySec2Body,
              isDark,
            ),
            _buildSection(
              AppLocalizations.of(context).privacySec3Title,
              AppLocalizations.of(context).privacySec3Body,
              isDark,
            ),
            _buildSection(
              AppLocalizations.of(context).privacySec4Title,
              AppLocalizations.of(context).privacySec4Body,
              isDark,
            ),
            const SizedBox(height: 40),
            Center(
              child: Text(
                AppLocalizations.of(context).privacyLastUpdate,
                style: TextStyle(
                  color: isDark ? Colors.white30 : Colors.black26,
                  fontSize: 12,
                  fontFamily: 'Rabar',
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSection(String title, String content, bool isDark) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: AppColors.primary,
              fontFamily: 'Rabar',
            ),
          ),
          const SizedBox(height: 8),
          Text(
            content,
            style: TextStyle(
              fontSize: 14,
              height: 1.6,
              color: isDark ? Colors.white70 : Colors.black87,
              fontFamily: 'Rabar',
            ),
          ),
        ],
      ),
    );
  }
}
