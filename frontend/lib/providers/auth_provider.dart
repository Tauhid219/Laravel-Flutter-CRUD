import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';
import '../services/auth_service.dart';

class AuthProvider extends ChangeNotifier {
  final ApiService _apiService = ApiService();
  final AuthService _authService = AuthService();

  String? _token;
  User? _user;
  bool _isLoading = false;

  String? get token => _token;
  User? get user => _user;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _token != null;

  // Initialize and check local storage on startup
  Future<void> tryAutoLogin() async {
    _token = await _authService.getToken();
    _user = await _authService.getUser();
    notifyListeners();
  }

  // Register a new user
  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    _isLoading = true;
    notifyListeners();

    final result = await _apiService.register(
      name: name,
      email: email,
      password: password,
      passwordConfirmation: passwordConfirmation,
    );

    if (result['success'] == true) {
      _token = result['token'] as String;
      final userMap = result['user'] as Map<String, dynamic>;
      _user = User.fromJson(userMap);

      await _authService.saveToken(_token!);
      await _authService.saveUser(_user!);
    }

    _isLoading = false;
    notifyListeners();
    return result;
  }

  // Log in an existing user
  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    _isLoading = true;
    notifyListeners();

    final result = await _apiService.login(
      email: email,
      password: password,
    );

    if (result['success'] == true) {
      _token = result['token'] as String;
      final userMap = result['user'] as Map<String, dynamic>;
      _user = User.fromJson(userMap);

      await _authService.saveToken(_token!);
      await _authService.saveUser(_user!);
    }

    _isLoading = false;
    notifyListeners();
    return result;
  }

  // Log out current user
  Future<void> logout() async {
    _isLoading = true;
    notifyListeners();

    if (_token != null) {
      // Call backend to invalidate token
      await _apiService.logout(_token!);
    }

    _token = null;
    _user = null;
    await _authService.clearAuthData();

    _isLoading = false;
    notifyListeners();
  }
}
