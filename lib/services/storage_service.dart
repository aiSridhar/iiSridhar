import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/dialog_session.dart';

/// Сервис для сохранения и загрузки данных приложения
class StorageService {
  static const String _dialogsKey = 'saved_dialogs';
  static const String _currentModeKey = 'current_prompt_mode';
  static const String _conversationModeKey = 'conversation_mode';

  /// Сохранить список диалогов
  static Future<bool> saveDialogs(List<DialogSession> dialogs) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final dialogsJson = dialogs.map((d) => d.toJson()).toList();
      final jsonString = jsonEncode(dialogsJson);
      return await prefs.setString(_dialogsKey, jsonString);
    } catch (e) {
      debugPrint('Error saving dialogs: $e');
      return false;
    }
  }

  /// Загрузить список диалогов
  static Future<List<DialogSession>> loadDialogs() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final jsonString = prefs.getString(_dialogsKey);
      
      if (jsonString == null || jsonString.isEmpty) {
        return [];
      }

      final List<dynamic> dialogsJson = jsonDecode(jsonString);
      return dialogsJson
          .map((json) => DialogSession.fromJson(json))
          .toList();
    } catch (e) {
      debugPrint('Error loading dialogs: $e');
      return [];
    }
  }

  /// Сохранить текущий режим промпта
  static Future<bool> savePromptMode(String modeId) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      return await prefs.setString(_currentModeKey, modeId);
    } catch (e) {
      debugPrint('Error saving prompt mode: $e');
      return false;
    }
  }

  /// Загрузить текущий режим промпта
  static Future<String?> loadPromptMode() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      return prefs.getString(_currentModeKey);
    } catch (e) {
      debugPrint('Error loading prompt mode: $e');
      return null;
    }
  }

  /// Сохранить режим разговора
  static Future<bool> saveConversationMode(String mode) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      return await prefs.setString(_conversationModeKey, mode);
    } catch (e) {
      debugPrint('Error saving conversation mode: $e');
      return false;
    }
  }

  /// Загрузить режим разговора
  static Future<String?> loadConversationMode() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      return prefs.getString(_conversationModeKey);
    } catch (e) {
      debugPrint('Error loading conversation mode: $e');
      return null;
    }
  }

  /// Очистить все сохраненные данные
  static Future<bool> clearAll() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      return await prefs.clear();
    } catch (e) {
      debugPrint('Error clearing data: $e');
      return false;
    }
  }
}

