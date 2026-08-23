import '../../core/utils/html_utils.dart';
import '../../core/utils/image_utils.dart';

class PostModel {
  final int id;
  final int institutionId;
  final String? title;
  final String content;
  final String? image;
  final String? authorName;
  final String? createdAt;

  // From nested institution relation
  final String? institutionName;
  final String? institutionLogo;
  final String? institutionType;

  PostModel({
    required this.id,
    required this.institutionId,
    this.title,
    required this.content,
    this.image,
    this.authorName,
    this.createdAt,
    this.institutionName,
    this.institutionLogo,
    this.institutionType,
  });

  String get imageUrl => ImageUtils.resolveUrl(image);

  String get logoUrl => ImageUtils.resolveUrl(institutionLogo);

  String get displayName =>
      institutionName ?? authorName ?? 'دامەزراوە';

  factory PostModel.fromJson(Map<String, dynamic> json) {
    final institution = json['institution'] as Map<String, dynamic>?;
    return PostModel(
      id: json['id'] ?? 0,
      institutionId: json['institution_id'] ?? 0,
      title: json['title'],
      content: HtmlUtils.toPlainText(json['content']),
      image: json['image'],
      authorName: json['author_name'],
      createdAt: json['created_at'],
      institutionName: institution?['nku'] ??
          institution?['nen'] ??
          institution?['name'],
      institutionLogo: institution?['logo'],
      institutionType: institution?['type'],
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'institution_id': institutionId,
        'title': title,
        'content': content,
        'image': image,
        'author_name': authorName,
        'created_at': createdAt,
      };
}
