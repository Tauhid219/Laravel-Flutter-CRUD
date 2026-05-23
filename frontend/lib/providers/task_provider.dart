import 'package:flutter/material.dart';
import '../models/task_model.dart';
import '../services/api_service.dart';

class TaskProvider extends ChangeNotifier {
  final ApiService _apiService = ApiService();

  List<Task> _tasks = [];
  bool _isLoading = false;
  String? _errorMessage;

  List<Task> get tasks => _tasks;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  // Task Statistics
  int get totalTasks => _tasks.length;
  int get completedTasks => _tasks.where((task) => task.isCompleted).length;
  int get pendingTasks => _tasks.where((task) => !task.isCompleted).length;
  double get completionPercentage =>
      totalTasks == 0 ? 0.0 : (completedTasks / totalTasks);

  // Load all tasks from the API
  Future<void> loadTasks(String token) async {
    _isLoading = true;
    _errorMessage = null;
    // We notify listeners later, but we can notify now to show loader
    notifyListeners();

    try {
      _tasks = await _apiService.fetchTasks(token);
      _errorMessage = null;
    } catch (e) {
      _errorMessage = e.toString().replaceAll('Exception: ', '');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Create a new task
  Future<void> addTask(
    String token, {
    required String title,
    String? description,
    required String category,
    required String priority,
    DateTime? dueDate,
  }) async {
    _isLoading = true;
    notifyListeners();

    try {
      final newTask = await _apiService.createTask(
        token,
        title: title,
        description: description,
        category: category,
        priority: priority,
        dueDate: dueDate,
      );
      _tasks.insert(0, newTask); // Add new task to the top
      _errorMessage = null;
    } catch (e) {
      _errorMessage = e.toString().replaceAll('Exception: ', '');
      rethrow;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Update task details
  Future<void> updateTask(
    String token,
    int taskId,
    Map<String, dynamic> updateData,
  ) async {
    final index = _tasks.indexWhere((t) => t.id == taskId);
    if (index == -1) return;

    _isLoading = true;
    notifyListeners();

    try {
      final updatedTask = await _apiService.updateTask(token, taskId, updateData);
      _tasks[index] = updatedTask;
      _errorMessage = null;
    } catch (e) {
      _errorMessage = e.toString().replaceAll('Exception: ', '');
      rethrow;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Toggle completion status optimistically
  Future<void> toggleTaskStatus(String token, Task task) async {
    final index = _tasks.indexWhere((t) => t.id == task.id);
    if (index == -1) return;

    final oldTask = _tasks[index];
    final updatedStatus = !task.isCompleted;

    // Optimistically update local list
    _tasks[index] = Task(
      id: oldTask.id,
      userId: oldTask.userId,
      title: oldTask.title,
      description: oldTask.description,
      category: oldTask.category,
      priority: oldTask.priority,
      dueDate: oldTask.dueDate,
      isCompleted: updatedStatus,
    );
    notifyListeners();

    try {
      final updatedTask = await _apiService.updateTask(token, task.id, {
        'is_completed': updatedStatus,
      });
      // Replace with fresh server data
      _tasks[index] = updatedTask;
      notifyListeners();
    } catch (e) {
      // Revert to old state on failure
      _tasks[index] = oldTask;
      notifyListeners();
      _errorMessage = e.toString().replaceAll('Exception: ', '');
      rethrow;
    }
  }

  // Delete a task optimistically
  Future<void> deleteTask(String token, int taskId) async {
    final index = _tasks.indexWhere((t) => t.id == taskId);
    if (index == -1) return;

    final deletedTask = _tasks[index];

    // Optimistically remove from list
    _tasks.removeAt(index);
    notifyListeners();

    try {
      await _apiService.deleteTask(token, taskId);
    } catch (e) {
      // Revert and insert task back to its position on failure
      _tasks.insert(index, deletedTask);
      notifyListeners();
      _errorMessage = e.toString().replaceAll('Exception: ', '');
      rethrow;
    }
  }
}
