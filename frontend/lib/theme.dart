import 'package:flutter/material.dart';

class AppColors {
  // Playful preschool theme from kider-1.0.0 template
  static const Color primary = Color(0xFFFE5D37);      // Vibrant Coral/Orange
  static const Color secondary = Color(0xFF103741);    // Deep Teal
  static const Color background = Color(0xFFFFF5F3);   // Soft Pinkish background
  static const Color cardBg = Colors.white;
  static const Color textDark = Color(0xFF103741);
  static const Color textMuted = Color(0xFF78909C);
  
  // Priority colors for tasks
  static const Color priorityHigh = Color(0xFFEF5350);   // Red
  static const Color priorityMedium = Color(0xFFFFB74D); // Orange
  static const Color priorityLow = Color(0xFF4FC3F7);    // Blue
}

final ThemeData kiderTheme = ThemeData(
  useMaterial3: true,
  primaryColor: AppColors.primary,
  scaffoldBackgroundColor: AppColors.background,
  colorScheme: ColorScheme.fromSeed(
    seedColor: AppColors.primary,
    primary: AppColors.primary,
    secondary: AppColors.secondary,
    surface: AppColors.cardBg,
  ),
  
  // Text theme adjustments
  textTheme: const TextTheme(
    headlineMedium: TextStyle(
      color: AppColors.textDark,
      fontWeight: FontWeight.bold,
      fontSize: 26,
    ),
    titleLarge: TextStyle(
      color: AppColors.textDark,
      fontWeight: FontWeight.bold,
      fontSize: 20,
    ),
    bodyLarge: TextStyle(
      color: AppColors.textDark,
      fontSize: 16,
    ),
    bodyMedium: TextStyle(
      color: AppColors.textMuted,
      fontSize: 14,
    ),
  ),

  // Card styles
  cardTheme: CardThemeData(
    color: AppColors.cardBg,
    elevation: 3,
    shadowColor: AppColors.primary.withValues(alpha: 0.1),
    shape: RoundedRectangleBorder(
      borderRadius: BorderRadius.circular(20),
    ),
  ),

  // Button styles
  elevatedButtonTheme: ElevatedButtonThemeData(
    style: ElevatedButton.styleFrom(
      backgroundColor: AppColors.primary,
      foregroundColor: Colors.white,
      minimumSize: const Size(double.infinity, 54),
      elevation: 4,
      shadowColor: AppColors.primary.withValues(alpha: 0.3),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(30),
      ),
      textStyle: const TextStyle(
        fontSize: 16,
        fontWeight: FontWeight.bold,
      ),
    ),
  ),

  // Input styles (rounded and filled)
  inputDecorationTheme: InputDecorationTheme(
    filled: true,
    fillColor: Colors.white,
    contentPadding: const EdgeInsets.symmetric(horizontal: 24, vertical: 18),
    border: OutlineInputBorder(
      borderRadius: BorderRadius.circular(30),
      borderSide: BorderSide.none,
    ),
    enabledBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(30),
      borderSide: BorderSide.none,
    ),
    focusedBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(30),
      borderSide: const BorderSide(color: AppColors.primary, width: 2),
    ),
    errorBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(30),
      borderSide: const BorderSide(color: AppColors.priorityHigh, width: 1.5),
    ),
    labelStyle: const TextStyle(
      color: AppColors.textMuted,
    ),
    hintStyle: const TextStyle(
      color: AppColors.textMuted,
    ),
  ),

  // FAB Style
  floatingActionButtonTheme: const FloatingActionButtonThemeData(
    backgroundColor: AppColors.primary,
    foregroundColor: Colors.white,
    shape: StadiumBorder(),
    elevation: 6,
  ),
);
