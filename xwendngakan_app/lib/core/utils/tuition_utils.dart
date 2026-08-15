/// Helpers for reading the fee/discount fields stored on institutions.
///
/// Records saved by the current dashboard carry a percentage in `discount`
/// plus a ready-made `final_price`. Older records only carry `fee` and
/// `discount`, where `discount` holds the price *after* the discount rather
/// than a percentage. [TuitionPrice.parse] normalises both shapes so every
/// screen can render the same three tags: struck-through fee, discount
/// percentage, final price.
class TuitionPrice {
  /// The original fee, formatted as stored (e.g. `3,500,000`).
  final String fee;

  /// Discount percentage without the `%` sign (e.g. `15`), or the raw stored
  /// value when it could not be interpreted as a discount at all.
  final String discount;

  /// Price after the discount (e.g. `2,975,000`); empty when unknown.
  final String finalPrice;

  const TuitionPrice({
    required this.fee,
    required this.discount,
    required this.finalPrice,
  });

  static const empty = TuitionPrice(fee: '', discount: '', finalPrice: '');

  /// True when the row has a usable discount and the percentage badge and
  /// final-price badge should both be shown.
  bool get hasDiscount => discount.isNotEmpty && finalPrice.isNotEmpty;

  /// True when there is something to render next to the department name.
  bool get isEmpty => fee.isEmpty && discount.isEmpty;

  /// Reads one row out of a `tuition_plans` entry or a `colleges[].depts[]`
  /// entry. Unknown or malformed values fall back to showing the raw text
  /// rather than dropping the price altogether.
  static TuitionPrice parse(Map? source) {
    if (source == null) return empty;
    final fee = (source['fee'] ?? '').toString().trim();
    final discount = (source['discount'] ?? '').toString().trim();
    final finalPrice = (source['final_price'] ?? '').toString().trim();

    // Saved by the current dashboard — already a percentage plus final price.
    if (discount.isNotEmpty && finalPrice.isNotEmpty) {
      return TuitionPrice(
          fee: fee, discount: discount, finalPrice: finalPrice);
    }
    if (fee.isEmpty || discount.isEmpty) {
      return TuitionPrice(fee: fee, discount: discount, finalPrice: '');
    }

    final f = _toNumber(fee);
    final d = _toNumber(discount);
    if (f == null || d == null || f <= 0 || d <= 0) {
      return TuitionPrice(fee: fee, discount: discount, finalPrice: '');
    }

    // A value of 100 or less is a percentage.
    if (d <= 100) {
      return TuitionPrice(
        fee: fee,
        discount: _formatPercent(d),
        finalPrice: _formatAmount(f - f * d / 100),
      );
    }

    // Legacy record: the owner typed the discounted price into the discount
    // field, so derive the percentage from the gap between the two.
    if (d < f) {
      return TuitionPrice(
        fee: fee,
        discount: _formatPercent((f - d) / f * 100),
        finalPrice: _formatAmount(d),
      );
    }

    // Discount is larger than the fee — nothing sensible to derive, so keep
    // the raw value visible instead of inventing a price.
    return TuitionPrice(fee: fee, discount: discount, finalPrice: '');
  }

  /// Strips currency symbols, thousands separators and Arabic-Indic digits.
  static double? _toNumber(String raw) {
    const arabicDigits = '٠١٢٣٤٥٦٧٨٩';
    final buffer = StringBuffer();
    for (final rune in raw.runes) {
      final char = String.fromCharCode(rune);
      final arabicIndex = arabicDigits.indexOf(char);
      if (arabicIndex >= 0) {
        buffer.write(arabicIndex);
      } else if (RegExp(r'[0-9.]').hasMatch(char)) {
        buffer.write(char);
      }
    }
    return double.tryParse(buffer.toString());
  }

  /// One decimal at most, with a trailing `.0` trimmed away.
  static String _formatPercent(double value) {
    final rounded = (value * 10).round() / 10;
    return rounded == rounded.roundToDouble()
        ? rounded.round().toString()
        : rounded.toString();
  }

  /// Whole number with `,` thousands separators, matching what the dashboard
  /// stores for `final_price`.
  static String _formatAmount(double value) {
    final digits = value.round().toString();
    final buffer = StringBuffer();
    for (var i = 0; i < digits.length; i++) {
      if (i > 0 && (digits.length - i) % 3 == 0) buffer.write(',');
      buffer.write(digits[i]);
    }
    return buffer.toString();
  }
}
