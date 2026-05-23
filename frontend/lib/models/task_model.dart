class Task {
  final int id;
  final int userId;
  final String title;
  final String? description;
  final String category;
  final String priority;
  final DateTime? dueDate;
  final bool isCompleted;

  Task({
    required this.id,
    required this.userId,
    required this.title,
    this.description,
    required this.category,
    required this.priority,
    this.dueDate,
    required this.isCompleted,
  });

  factory Task.fromJson(Map<String, dynamic> json) {
    return Task(
      id: json['id'] as int,
      userId: json['user_id'] as int,
      title: json['title'] as String,
      description: json['description'] as String?,
      category: (json['category'] as String?) ?? 'General',
      priority: (json['priority'] as String?) ?? 'Medium',
      dueDate: json['due_date'] != null 
          ? DateTime.tryParse(json['due_date'] as String) 
          : null,
      isCompleted: json['is_completed'] == 1 || json['is_completed'] == true,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'title': title,
      'description': description,
      'category': category,
      'priority': priority,
      'due_date': dueDate?.toIso8601String().split('T')[0],
      'is_completed': isCompleted,
    };
  }
}
