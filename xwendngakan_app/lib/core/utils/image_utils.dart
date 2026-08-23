import '../constants/app_constants.dart';

class ImageUtils {
  ImageUtils._();

  /// Resolves any relative path, old-domain URL, or localhost URL to a valid image URL.
  static String resolveUrl(String? rawUrl) {
    if (rawUrl == null || rawUrl.trim().isEmpty) return '';
    final url = rawUrl.trim();

    final baseDomain = AppConstants.baseUrl.replaceAll(RegExp(r'/api/?$'), '');

    // Full URL handling
    if (url.startsWith('http://') || url.startsWith('https://')) {
      final uri = Uri.tryParse(url);
      if (uri != null) {
        // If domain is old, local, or placeholder, migrate to current base domain
        if (uri.host == 'localhost' ||
            uri.host == '127.0.0.1' ||
            uri.host == '10.0.2.2' ||
            uri.host.contains('khwenden.com') ||
            uri.host.contains('192.168.')) {
          final path = uri.path.startsWith('/') ? uri.path : '/${uri.path}';
          if (!path.startsWith('/storage/')) {
            return '$baseDomain/storage$path';
          }
          return '$baseDomain$path';
        }
      }
      return url;
    }

    // Relative path handling
    final path = url.startsWith('/') ? url : '/$url';
    if (!path.startsWith('/storage/')) {
      return '$baseDomain/storage$path';
    }
    return '$baseDomain$path';
  }
}
