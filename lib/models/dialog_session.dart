import 'package:isridhar/main.dart';

/// Модель сохраненного диалога/сессии
class DialogSession {
  final String id;
  final String title;
  final DateTime createdAt;
  final DateTime updatedAt;
  final List<Message> messages;
  final String promptModeId;
  final String conversationModeId;
  
  DialogSession({
    required this.id,
    required this.title,
    required this.createdAt,
    required this.updatedAt,
    required this.messages,
    required this.promptModeId,
    required this.conversationModeId,
  });
  
  DialogSession copyWith({
    String? title,
    DateTime? updatedAt,
    List<Message>? messages,
    String? promptModeId,
    String? conversationModeId,
  }) {
    return DialogSession(
      id: id,
      title: title ?? this.title,
      createdAt: createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
      messages: messages ?? this.messages,
      promptModeId: promptModeId ?? this.promptModeId,
      conversationModeId: conversationModeId ?? this.conversationModeId,
    );
  }
  
  /// Генерация заголовка из первого сообщения
  static String generateTitle(List<Message> messages) {
    if (messages.isEmpty) return 'Новый диалог';
    
    final firstUserMessage = messages.firstWhere(
      (m) => m.isUser && m.text.trim().isNotEmpty,
      orElse: () => messages.first,
    );
    
    final text = firstUserMessage.text.trim();
    if (text.length <= 50) return text;
    return '${text.substring(0, 47)}...';
  }
}

