/// Iraqi mobile number handling.
///
/// Only the three carriers the app accepts are allowed:
/// Korek (075x), Asiacell (077x) and Zain (078x). Numbers are stored and sent
/// to the API in E.164 form, e.g. `+9647501234567`.
class PhoneUtils {
  static const String countryCode = '+964';

  /// 10-digit national number: 7 + carrier prefix + 7 digits.
  static final RegExp _nationalPattern = RegExp(r'^7(5[0-4]|7[0-4]|8[0-4])\d{7}$');

  /// Strips formatting plus any country/trunk prefix, leaving the bare
  /// national number (e.g. `07501234567` and `+964 750 123 4567` both give
  /// `7501234567`). Returns null when there is nothing left.
  static String? national(String? raw) {
    if (raw == null) return null;
    var digits = raw.replaceAll(RegExp(r'[^0-9]'), '');
    if (digits.startsWith('00964')) {
      digits = digits.substring(5);
    } else if (digits.startsWith('964')) {
      digits = digits.substring(3);
    }
    if (digits.startsWith('0')) digits = digits.substring(1);
    return digits.isEmpty ? null : digits;
  }

  static bool isValid(String? raw) {
    final n = national(raw);
    return n != null && _nationalPattern.hasMatch(n);
  }

  /// E.164 form for the API, or null when the number is not a valid
  /// Korek/Asiacell/Zain number.
  static String? toE164(String? raw) {
    final n = national(raw);
    if (n == null || !_nationalPattern.hasMatch(n)) return null;
    return '$countryCode$n';
  }
}
