import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/task_model.dart';

class ApiService {
  // Base API URL - Easily configurable (pointing to your computer's local IP)
  static const String baseUrl = 'http://192.168.0.240:8000/api';

  // Helper to generate headers with optional authentication token
  Map<String, String> _getHeaders(String? token) {
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    if (token != null) {
      headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  // User Registration API
  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    final url = Uri.parse('$baseUrl/register');
    try {
      final response = await http.post(
        url,
        headers: _getHeaders(null),
        body: json.encode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        }),
      );

      final data = json.decode(response.body) as Map<String, dynamic>;
      if (response.statusCode == 201) {
        return {
          'success': true,
          'token': data['data']['token'] as String,
          'user': data['data']['user'] as Map<String, dynamic>,
          'message': data['message'] as String?,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Registration failed.',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Connection error: Could not reach the server ($e).',
      };
    }
  }

  // User Login API
  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    final url = Uri.parse('$baseUrl/login');
    try {
      final response = await http.post(
        url,
        headers: _getHeaders(null),
        body: json.encode({
          'email': email,
          'password': password,
        }),
      );

      final data = json.decode(response.body) as Map<String, dynamic>;
      if (response.statusCode == 200) {
        return {
          'success': true,
          'token': data['data']['token'] as String,
          'user': data['data']['user'] as Map<String, dynamic>,
          'message': data['message'] as String?,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Invalid login details.',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Connection error: Could not reach the server ($e).',
      };
    }
  }

  // User Logout API
  Future<bool> logout(String token) async {
    final url = Uri.parse('$baseUrl/logout');
    try {
      final response = await http.post(
        url,
        headers: _getHeaders(token),
      );
      return response.statusCode == 200;
    } catch (_) {
      return false;
    }
  }

  // Fetch Tasks List API
  Future<List<Task>> fetchTasks(String token) async {
    final url = Uri.parse('$baseUrl/tasks');
    final response = await http.get(
      url,
      headers: _getHeaders(token),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body) as Map<String, dynamic>;
      final list = data['data'] as List<dynamic>;
      return list
          .map((item) => Task.fromJson(item as Map<String, dynamic>))
          .toList();
    } else {
      throw Exception('Failed to load tasks: Server returned code ${response.statusCode}');
    }
  }

  // Create Task API
  Future<Task> createTask(
    String token, {
    required String title,
    String? description,
    required String category,
    required String priority,
    DateTime? dueDate,
  }) async {
    final url = Uri.parse('$baseUrl/tasks');
    final response = await http.post(
      url,
      headers: _getHeaders(token),
      body: json.encode({
        'title': title,
        'description': description,
        'category': category,
        'priority': priority,
        'due_date': dueDate?.toIso8601String().split('T')[0],
      }),
    );

    final data = json.decode(response.body) as Map<String, dynamic>;
    if (response.statusCode == 201) {
      return Task.fromJson(data['data'] as Map<String, dynamic>);
    } else {
      throw Exception(data['message'] ?? 'Failed to create task.');
    }
  }

  // Update Task API (Supports full or partial updates)
  Future<Task> updateTask(
    String token,
    int taskId,
    Map<String, dynamic> updateData,
  ) async {
    final url = Uri.parse('$baseUrl/tasks/$taskId');
    final response = await http.put(
      url,
      headers: _getHeaders(token),
      body: json.encode(updateData),
    );

    final data = json.decode(response.body) as Map<String, dynamic>;
    if (response.statusCode == 200) {
      return Task.fromJson(data['data'] as Map<String, dynamic>);
    } else {
      throw Exception(data['message'] ?? 'Failed to update task.');
    }
  }

  // Delete Task API
  Future<void> deleteTask(String token, int taskId) async {
    final url = Uri.parse('$baseUrl/tasks/$taskId');
    final response = await http.delete(
      url,
      headers: _getHeaders(token),
    );

    if (response.statusCode != 200) {
      final data = json.decode(response.body) as Map<String, dynamic>;
      throw Exception(data['message'] ?? 'Failed to delete task.');
    }
  }
}
