/// Helpers for turning rich-text (Quill/HTML) content into plain text
/// suitable for display in plain [Text] widgets.
class HtmlUtils {
  HtmlUtils._();

  /// Converts HTML produced by the web editor (Quill) into plain text:
  /// block tags become line breaks, list items get bullets, remaining
  /// tags are stripped and common HTML entities are decoded.
  static String toPlainText(String? input) {
    if (input == null || input.isEmpty) return '';

    var text = input
        .replaceAll(RegExp(r'<br\s*/?>', caseSensitive: false), '\n')
        .replaceAll(RegExp(r'</(p|div|li|h[1-6])>', caseSensitive: false), '\n')
        .replaceAll(RegExp(r'<li[^>]*>', caseSensitive: false), '• ')
        .replaceAll(RegExp(r'<[^>]+>'), ''); // strip any remaining tags

    text = text
        .replaceAll('&nbsp;', ' ')
        .replaceAll('&amp;', '&')
        .replaceAll('&lt;', '<')
        .replaceAll('&gt;', '>')
        .replaceAll('&quot;', '"')
        .replaceAll('&#39;', "'");

    // Collapse 3+ newlines and trim trailing spaces on each line.
    text = text
        .replaceAll(RegExp(r'[ \t]+\n'), '\n')
        .replaceAll(RegExp(r'\n{3,}'), '\n\n');

    return text.trim();
  }
}
