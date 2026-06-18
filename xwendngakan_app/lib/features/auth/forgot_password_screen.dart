import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../core/constants/app_constants.dart';
import '../../core/localization/app_localizations.dart';
import '../../providers/auth_provider.dart';
import '../../shared/widgets/common_widgets.dart';

enum _ForgotStep { email, done }

class ForgotPasswordScreen extends StatefulWidget {
  const ForgotPasswordScreen({super.key});

  @override
  State<ForgotPasswordScreen> createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen> {
  _ForgotStep _step = _ForgotStep.email;
  final _emailCtrl = TextEditingController();
  bool _loading = false;
  String? _error;

  Future<void> _sendResetLink() async {
    if (_emailCtrl.text.trim().isEmpty) return;
    setState(() { _loading = true; _error = null; });
    final ok = await context
        .read<AuthProvider>()
        .sendPasswordReset(_emailCtrl.text.trim());
    if (!mounted) return;
    setState(() {
      _loading = false;
      if (ok) {
        _step = _ForgotStep.done;
      } else {
        _error = context.read<AuthProvider>().error;
      }
    });
  }

  @override
  void dispose() {
    _emailCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final l = AppLocalizations.of(context);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      backgroundColor: isDark ? AppColors.darkBg : AppColors.lightBg,
      body: Stack(
        children: [
          // Gold curved header with decorative circles (matches login)
          Container(
            height: 280,
            decoration: const BoxDecoration(
              gradient: AppColors.primaryGradient,
              borderRadius: BorderRadius.only(
                bottomLeft: Radius.circular(44),
                bottomRight: Radius.circular(44),
              ),
            ),
            child: Stack(
              children: [
                Positioned(
                  top: -40,
                  right: -40,
                  child: Container(
                    width: 180,
                    height: 180,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: Colors.white.withValues(alpha: 0.12),
                    ),
                  ),
                ),
                Positioned(
                  bottom: 20,
                  left: -30,
                  child: Container(
                    width: 110,
                    height: 110,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: Colors.white.withValues(alpha: 0.08),
                    ),
                  ),
                ),
              ],
            ),
          ),
          SafeArea(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Row(
                    children: [
                      GestureDetector(
                        onTap: () => context.pop(),
                        child: Container(
                          width: 40, height: 40,
                          decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.2),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Icon(Icons.arrow_back_ios_new_rounded,
                              color: Colors.white, size: 18),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Center(
                    child: Column(
                      children: [
                        Container(
                          width: 72, height: 72,
                          decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.2),
                            shape: BoxShape.circle,
                          ),
                          child: const Center(child: Text('🔐', style: TextStyle(fontSize: 34))),
                        ),
                        const SizedBox(height: 12),
                        Text(l.forgotPassword.replaceAll('?', ''),
                            style: const TextStyle(fontSize: 20,
                                fontWeight: FontWeight.w700, color: Colors.white,
                                fontFamily: 'Rabar')),
                      ],
                    ),
                  ),
                  const SizedBox(height: 32),
                  // Card
                  Container(
                    decoration: BoxDecoration(
                      color: isDark ? AppColors.darkCard : AppColors.lightCard,
                      borderRadius: BorderRadius.circular(AppConstants.radiusXl),
                      boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.15),
                          blurRadius: 30)],
                    ),
                    padding: const EdgeInsets.all(24),
                    child: _buildStep(context, l),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStep(BuildContext context, AppLocalizations l) {
    if (_step == _ForgotStep.done) {
      return Column(
        children: [
          const Text('✅', style: TextStyle(fontSize: 56)),
          const SizedBox(height: 16),
          Text(l.resetPassword,
              style: Theme.of(context).textTheme.headlineSmall),
          const SizedBox(height: 8),
          Text(l.passwordResetSuccess,
              style: Theme.of(context).textTheme.bodyMedium,
              textAlign: TextAlign.center),
          const SizedBox(height: 24),
          GradientButton(
            text: l.login,
            onPressed: () => context.go('/login'),
          ),
        ],
      );
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        if (_error != null)
          Container(
            padding: const EdgeInsets.all(12),
            margin: const EdgeInsets.only(bottom: 16),
            decoration: BoxDecoration(
              color: AppColors.error.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(AppConstants.radiusMd),
              border: Border.all(color: AppColors.error.withValues(alpha: 0.3)),
            ),
            child: Text(_error!, style: const TextStyle(color: AppColors.error, fontSize: 13)),
          ),
        if (_step == _ForgotStep.email) ...[
          Text(l.email, style: Theme.of(context).textTheme.titleMedium),
          const SizedBox(height: 12),
          TextField(
            controller: _emailCtrl,
            keyboardType: TextInputType.emailAddress,
            decoration: const InputDecoration(
              hintText: 'email@example.com',
              prefixIcon: Icon(Icons.email_outlined),
            ),
          ),
          const SizedBox(height: 20),
          GradientButton(
            text: l.sendOtp,
            onPressed: _sendOtp,
            isLoading: _loading,
          ),
        ] else if (_step == _ForgotStep.otp) ...[
          Text(l.enterOtp, style: Theme.of(context).textTheme.titleMedium),
          const SizedBox(height: 4),
          Text(_emailCtrl.text, style: Theme.of(context).textTheme.bodySmall
              ?.copyWith(color: AppColors.primary)),
          const SizedBox(height: 20),
          // OTP boxes
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: List.generate(6, (i) => _OtpBox(
              controller: _otpCtrl[i],
              onFilled: (val) {
                if (val.isNotEmpty && i < 5) {
                  FocusScope.of(context).nextFocus();
                }
                setState(() {});
              },
            )),
          ),
          const SizedBox(height: 20),
          GradientButton(
            text: l.done,
            onPressed: _verifyOtp,
            isLoading: _loading,
          ),
          TextButton(
            onPressed: _sendOtp,
            child: Text(l.resendOtp),
          ),
        ] else if (_step == _ForgotStep.newPassword) ...[
          Text(l.resetPassword, style: Theme.of(context).textTheme.titleMedium),
          const SizedBox(height: 12),
          TextField(
            controller: _passCtrl,
            obscureText: true,
            decoration: InputDecoration(
              hintText: l.password,
              prefixIcon: const Icon(Icons.lock_outline),
            ),
          ),
          const SizedBox(height: 20),
          GradientButton(
            text: l.resetPassword,
            onPressed: _resetPassword,
            isLoading: _loading,
          ),
        ],
      ],
    );
  }
}

class _OtpBox extends StatelessWidget {
  final TextEditingController controller;
  final ValueChanged<String> onFilled;

  const _OtpBox({required this.controller, required this.onFilled});

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    return Container(
      width: 44,
      height: 52,
      decoration: BoxDecoration(
        color: isDark ? AppColors.darkCardElevated : AppColors.lightCardElevated,
        borderRadius: BorderRadius.circular(AppConstants.radiusMd),
        border: Border.all(
          color: controller.text.isNotEmpty
              ? AppColors.primary
              : (isDark ? AppColors.darkBorder : AppColors.lightBorder),
          width: controller.text.isNotEmpty ? 1.5 : 0.8,
        ),
      ),
      child: TextField(
        controller: controller,
        textAlign: TextAlign.center,
        maxLength: 1,
        keyboardType: TextInputType.number,
        inputFormatters: [FilteringTextInputFormatter.digitsOnly],
        onChanged: onFilled,
        style: TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.w700,
          color: isDark ? AppColors.textWhite : AppColors.textDark,
        ),
        decoration: const InputDecoration(
          border: InputBorder.none,
          counterText: '',
          contentPadding: EdgeInsets.zero,
        ),
      ),
    );
  }
}
