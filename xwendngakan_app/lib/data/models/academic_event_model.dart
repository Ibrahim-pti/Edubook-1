class AcademicEventModel {
  final int? id;
  final String title;
  final String description;
  final DateTime date;
  final int durationDays;
  final String category;
  final String? icon;

  AcademicEventModel({
    this.id,
    required this.title,
    required this.description,
    required this.date,
    required this.durationDays,
    required this.category,
    this.icon,
  });

  factory AcademicEventModel.fromJson(Map<String, dynamic> json) {
    return AcademicEventModel(
      id: json['id'] as int?,
      title: json['title'] as String? ?? '',
      description: json['description'] as String? ?? '',
      date: DateTime.parse(json['date'] as String),
      durationDays: json['duration_days'] as int? ?? 1,
      category: json['category'] as String? ?? 'holiday',
      icon: json['icon'] as String?,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'date': date.toIso8601String().substring(0, 10),
      'duration_days': durationDays,
      'category': category,
      'icon': icon,
    };
  }
}
