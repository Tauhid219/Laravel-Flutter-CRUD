import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user_model.dart';

class AuthService {
  static const String _keyToken = 'auth_token';
  static const String _keyUser = 'user_data';

  // Save authentication token
  Future<bool> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    return await prefs.setString(_keyToken, token);
  }

  // Get saved authentication token
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_keyToken);
  }

  // Clear saved authentication token
  Future<bool> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    return await prefs.remove(_keyToken);
  }

  // Save user profile details
  Future<bool> saveUser(User user) async {
    final prefs = await SharedPreferences.getInstance();
    final jsonStr = json.encode(user.toJson());
    return await prefs.setString(_keyUser, jsonStr);
  }

  // Get saved user profile details
  Future<User?> getUser() async {
    final prefs = await SharedPreferences.getInstance();
    final jsonStr = prefs.getString(_keyUser);
    if (jsonStr == null) return null;
    try {
      final jsonMap = json.decode(jsonStr) as Map<String, dynamic>;
      return User.fromJson(jsonMap);
    } catch (_) {
      return null;
    }
  }

  // Clear all authentication and user details (logout)
  Future<void> clearAuthData() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_keyToken);
    await prefs.remove(_keyUser);
  }
}
