import '../../core/utils/html_utils.dart';
import '../../core/utils/image_utils.dart';

class NewsModel {
  final int id;
  final String title;
  final String content;
  final String? imageUrl;
  final String createdAt;

  NewsModel({
    required this.id,
    required this.title,
    required this.content,
    this.imageUrl,
    required this.createdAt,
  });

  String get displayImageUrl => ImageUtils.resolveUrl(imageUrl);

  factory NewsModel.fromJson(Map<String, dynamic> json) {
    return NewsModel(
      id: json['id'] as int,
      title: json['title'] as String,
      content: HtmlUtils.toPlainText(json['content'] as String?),
      imageUrl: json['image_url'] as String?,
      createdAt: json['created_at'] as String,
    );
  }
}
