class InstitutionTypeModel {
  final String key;
  final String name;
  final String? nameEn;
  final String? nameAr;
  final String? emoji;
  final String? icon;

  InstitutionTypeModel({
    required this.key,
    required this.name,
    this.nameEn,
    this.nameAr,
    this.emoji,
    this.icon,
  });

  factory InstitutionTypeModel.fromJson(Map<String, dynamic> json) {
    return InstitutionTypeModel(
      key: json['key'] ?? '',
      name: json['name'] ?? '',
      nameEn: json['name_en'],
      nameAr: json['name_ar'],
      emoji: json['emoji'],
      icon: json['icon'],
    );
  }

  String localizedName(String lang) {
    switch (lang) {
      case 'en':
        return (nameEn != null && nameEn!.isNotEmpty) ? nameEn! : name;
      case 'ar':
        return (nameAr != null && nameAr!.isNotEmpty) ? nameAr! : name;
      default:
        return name;
    }
  }
}
