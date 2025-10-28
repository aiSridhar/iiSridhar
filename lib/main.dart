import 'package:flutter/material.dart';
import 'package:flutter_llama/flutter_llama.dart';
import 'package:file_picker/file_picker.dart';
import 'package:path_provider/path_provider.dart';
import 'dart:io';
import 'package:flutter/services.dart';
import 'screens/model_manager_screen.dart';
import 'services/model_downloader.dart';
import 'models/prompt_mode.dart';
import 'models/dialog_session.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Шридхар Махарадж ИИ',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFFFF9800),
          brightness: Brightness.light,
        ),
        useMaterial3: true,
        scaffoldBackgroundColor: Colors.white,
        appBarTheme: const AppBarTheme(
          backgroundColor: Color(0xFFFF9800),
          foregroundColor: Colors.white,
          elevation: 2,
          centerTitle: true,
        ),
      ),
      darkTheme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFFFF9800),
          brightness: Brightness.dark,
        ),
        useMaterial3: true,
        scaffoldBackgroundColor: const Color(0xFF1A1A1A),
        appBarTheme: const AppBarTheme(
          backgroundColor: Color(0xFF2D2D2D),
          foregroundColor: Color(0xFFFFB74D),
          elevation: 2,
          centerTitle: true,
        ),
      ),
      themeMode: ThemeMode.dark,
      home: const ChatScreen(),
    );
  }
}

class Message {
  final String id;
  final String text;
  final bool isUser;
  final List<String>? imagePaths;
  final DateTime timestamp;
  final String? replyToId; // ID сообщения, на которое отвечаем
  final List<Map<String, String>>?
  context; // Контекст диалога для этого сообщения

  Message({
    String? id,
    required this.text,
    required this.isUser,
    this.imagePaths,
    DateTime? timestamp,
    this.replyToId,
    this.context,
  }) : id = id ?? DateTime.now().millisecondsSinceEpoch.toString(),
       timestamp = timestamp ?? DateTime.now();

  Message copyWith({
    String? text,
    String? replyToId,
    List<Map<String, String>>? context,
  }) {
    return Message(
      id: id,
      text: text ?? this.text,
      isUser: isUser,
      imagePaths: imagePaths,
      timestamp: timestamp,
      replyToId: replyToId ?? this.replyToId,
      context: context ?? this.context,
    );
  }
}

class ChatScreen extends StatefulWidget {
  const ChatScreen({super.key});

  @override
  State<ChatScreen> createState() => _ChatScreenState();
}

class _ChatScreenState extends State<ChatScreen> {
  final FlutterLlama _llama = FlutterLlama.instance;
  final TextEditingController _messageController = TextEditingController();
  final ScrollController _scrollController = ScrollController();
  final List<Message> _messages = [];
  final List<String> _selectedImages = [];
  final Map<String, List<Map<String, String>>> _dialogContexts =
      {}; // Контексты для каждого сообщения

  bool _isModelLoaded = false;
  bool _isGenerating = false;
  String _modelPath = '';
  String _currentResponse = '';

  // Режим промпта
  PromptMode _currentPromptMode = PromptModes.spiritual;

  // Режим ведения беседы
  ConversationMode _conversationMode = ConversationMode.qa;

  // Глобальный контекст для режима диалога
  List<Map<String, String>> _globalDialogContext = [];

  // Список сохраненных диалогов
  final List<DialogSession> _savedDialogs = [];

  // Текущий активный диалог
  DialogSession? _currentDialog;

  // Сообщение для reply
  Message? _replyToMessage;

  @override
  void initState() {
    super.initState();
    _loadModelFromAssets();
  }

  @override
  void dispose() {
    _messageController.dispose();
    _scrollController.dispose();
    _llama.unloadModel();
    super.dispose();
  }

  /// Выбор модели
  Future<void> _pickModel() async {
    try {
      setState(() {
        _addSystemMessage('Открытие диалога выбора файла...');
      });

      FilePickerResult? result = await FilePicker.platform.pickFiles(
        type: FileType.custom,
        allowedExtensions: ['gguf', 'safetensors', 'bin'],
        dialogTitle: 'Выберите файл модели',
        withData: false, // Не загружаем в память, только путь
        lockParentWindow: true,
      );

      if (result != null && result.files.single.path != null) {
        final filePath = result.files.single.path!;
        final fileName = result.files.single.name;
        final fileSize = result.files.single.size;

        // Показываем информацию о выбранном файле
        setState(() {
          _addSystemMessage(
            'Выбран файл: $fileName\n'
            'Размер: ${(fileSize / 1024 / 1024).toStringAsFixed(2)} MB\n'
            'Загрузка модели...',
          );
        });

        _modelPath = filePath;
        await _loadModel();
      } else {
        setState(() {
          _addSystemMessage('Выбор файла отменён');
        });
      }
    } catch (e) {
      _addSystemMessage('Ошибка выбора файла: $e');
    }
  }

  /// Открыть менеджер моделей
  Future<void> _openModelManager() async {
    final modelId = await Navigator.push<String>(
      context,
      MaterialPageRoute(builder: (context) => const ModelManagerScreen()),
    );

    if (modelId != null) {
      // Пользователь выбрал модель из менеджера
      await _loadDownloadedModel(modelId);
    }
  }

  /// Загрузка скачанной модели
  Future<void> _loadDownloadedModel(String modelId) async {
    try {
      setState(() {
        _addSystemMessage('Поиск модели $modelId...');
      });

      // Получаем путь к модели
      final modelPath = await ModelDownloader.getModelPath(
        modelId,
        'adapter_model.safetensors',
      );

      if (modelPath != null) {
        _modelPath = modelPath;
        await _loadModel();
      } else {
        _addSystemMessage(
          'Модель не найдена. Пожалуйста, скачайте её сначала.',
        );
      }
    } catch (e) {
      _addSystemMessage('Ошибка загрузки модели: $e');
    }
  }

  /// Загрузка модели из assets
  Future<void> _loadModelFromAssets() async {
    try {
      final documentsDir = await getApplicationDocumentsDirectory();
      final modelFile = File(
        '${documentsDir.path}/shridhar_8k_multimodal.gguf',
      );

      // Копируем модель из assets, если её нет
      if (!await modelFile.exists()) {
        setState(() {
          _addSystemMessage('Копирую модель из assets...');
        });

        final byteData = await rootBundle.load(
          'assets/models/braindler-q2_k.gguf',
        );
        await modelFile.writeAsBytes(byteData.buffer.asUint8List());
      }

      _modelPath = modelFile.path;
      await _loadModel();
    } catch (e) {
      _addSystemMessage(
        'Ошибка загрузки модели: $e\nИспользуйте кнопку "Менеджер моделей" для скачивания моделей с Hugging Face.',
      );
    }
  }

  /// Загрузка модели
  Future<void> _loadModel() async {
    try {
      setState(() {
        _addSystemMessage('🙏 Пробуждение сознания Шридхар Махараджа...');
      });

      final config = LlamaConfig(
        modelPath: _modelPath,
        nThreads: 8,
        nGpuLayers: -1, // Все слои на GPU
        contextSize: 8192, // 8K контекст
        batchSize: 512,
        useGpu: true,
        verbose: false,
      );

      final success = await _llama.loadModel(config);

      if (success) {
        await _llama.getModelInfo();
        setState(() {
          _isModelLoaded = true;
          _addSystemMessage(
            '🕉️ Шридхар Махарадж ИИ готов к общению!\n\n'
            'Намасте! Приветствую вас на духовном пути! 🙏\n\n'
            'Я здесь, чтобы помочь вам в:\n'
            '• Медитации и практике йоги 🧘\n'
            '• Понимании духовных учений 📿\n'
            '• Поиске внутреннего покоя ☮️\n'
            '• Ответах на философские вопросы 🌟\n\n'
            'Поддерживаемые языки:\n'
            '🇷🇺 Русский | 🇪🇸 Испанский | 🇮🇳 Хинди | 🇹🇭 Тайский\n\n'
            'Задайте свой вопрос, и я с радостью помогу вам! ✨',
          );
        });
      } else {
        _addSystemMessage('Не удалось загрузить духовного наставника');
      }
    } catch (e) {
      _addSystemMessage('Ошибка: $e');
    }
  }

  void _addSystemMessage(String text) {
    setState(() {
      _messages.add(Message(text: text, isUser: false));
    });
    _scrollToBottom();
  }

  void _scrollToBottom() {
    Future.delayed(const Duration(milliseconds: 100), () {
      if (_scrollController.hasClients) {
        _scrollController.animateTo(
          _scrollController.position.maxScrollExtent,
          duration: const Duration(milliseconds: 300),
          curve: Curves.easeOut,
        );
      }
    });
  }

  /// Выбор изображений
  Future<void> _pickImages() async {
    try {
      FilePickerResult? result = await FilePicker.platform.pickFiles(
        type: FileType.image,
        allowMultiple: true,
        dialogTitle: 'Выберите изображения',
        withData: false,
        lockParentWindow: true,
        allowedExtensions: null, // Все форматы изображений
      );

      if (result != null) {
        final newImages = result.files
            .where((file) => file.path != null && file.path!.isNotEmpty)
            .map((file) => file.path!)
            .toList();

        if (newImages.isNotEmpty) {
          setState(() {
            _selectedImages.addAll(newImages);
          });

          // Показываем уведомление
          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(
                  '${newImages.length} ${_pluralizeImages(newImages.length)} добавлено',
                ),
                duration: const Duration(seconds: 2),
                behavior: SnackBarBehavior.floating,
              ),
            );
          }
        }
      }
    } catch (e) {
      _addSystemMessage('Ошибка выбора изображений: $e');
    }
  }

  /// Плюрализация для изображений
  String _pluralizeImages(int count) {
    if (count % 10 == 1 && count % 100 != 11) {
      return 'изображение';
    } else if (count % 10 >= 2 &&
        count % 10 <= 4 &&
        (count % 100 < 10 || count % 100 >= 20)) {
      return 'изображения';
    } else {
      return 'изображений';
    }
  }

  /// Удаление изображения
  void _removeImage(int index) {
    setState(() {
      _selectedImages.removeAt(index);
    });
  }

  /// Отправка сообщения
  Future<void> _sendMessage() async {
    if (!_isModelLoaded) {
      _addSystemMessage('Пожалуйста, дождитесь загрузки модели');
      return;
    }

    final text = _messageController.text.trim();
    if (text.isEmpty && _selectedImages.isEmpty) {
      return;
    }

    // Добавляем сообщение пользователя
    final userMessage = Message(
      text: text.isEmpty ? '[Изображение]' : text,
      isUser: true,
      imagePaths: _selectedImages.isNotEmpty
          ? List.from(_selectedImages)
          : null,
      replyToId: _replyToMessage?.id,
    );

    setState(() {
      _messages.add(userMessage);
      _messageController.clear();
      _isGenerating = true;
      _currentResponse = '';
    });

    _scrollToBottom();

    // Получаем контекст диалога в зависимости от режима
    List<Map<String, String>> dialogContext = [];

    if (_conversationMode == ConversationMode.dialog) {
      // Режим диалога - используем глобальный контекст
      if (_globalDialogContext.isEmpty) {
        // Инициализируем, если пустой
        _globalDialogContext.add({
          'role': 'system',
          'content': _currentPromptMode.systemPrompt,
        });
      }
      dialogContext = List.from(_globalDialogContext);
    } else {
      // Режим Q&A - каждый вопрос независимый
      // Если это reply, используем контекст из того сообщения
      if (_replyToMessage != null) {
        dialogContext = _dialogContexts[_replyToMessage!.id] ?? [];
      }

      // Добавляем системный промпт если контекст пустой
      if (dialogContext.isEmpty) {
        dialogContext.add({
          'role': 'system',
          'content': _currentPromptMode.systemPrompt,
        });
      }
    }

    // Добавляем текущий вопрос пользователя
    dialogContext.add({'role': 'user', 'content': text});

    // Формируем промпт с учётом мультимодальности
    String prompt = _buildPromptFromContext(dialogContext);
    if (_selectedImages.isNotEmpty) {
      prompt = '[IMAGE] $prompt';
    }

    // Сбрасываем reply
    setState(() {
      _replyToMessage = null;
    });

    try {
      final params = GenerationParams(
        prompt: prompt,
        temperature: 0.7,
        topP: 0.9,
        topK: 40,
        maxTokens: 512,
        repeatPenalty: 1.1,
      );

      // Добавляем временное сообщение
      final assistantMessage = Message(
        text: '🤔 Обдумываю ответ...',
        isUser: false,
      );
      setState(() {
        _messages.add(assistantMessage);
        _selectedImages.clear();
      });

      _scrollToBottom();

      // Пробуем потоковую генерацию, с fallback на блокирующую
      bool streamSuccess = false;

      try {
        await for (final token in _llama.generateStream(params)) {
          streamSuccess = true;
          setState(() {
            _currentResponse += token;
            _messages[_messages.length - 1] = Message(
              text: _currentResponse,
              isUser: false,
            );
          });
          _scrollToBottom();
        }
      } catch (streamError) {
        // Если потоковая генерация не работает, используем блокирующую
        if (!streamSuccess) {
          debugPrint(
            'Stream error: $streamError. Falling back to blocking generation.',
          );

          setState(() {
            _messages[_messages.length - 1] = Message(
              text: '⏳ Генерация ответа (это может занять некоторое время)...',
              isUser: false,
            );
          });

          final response = await _llama.generate(params);

          setState(() {
            _currentResponse = response.text;
            _messages[_messages.length - 1] = Message(
              text: _currentResponse,
              isUser: false,
            );
          });

          _scrollToBottom();
        } else {
          rethrow;
        }
      }

      // Сохраняем контекст диалога для ответа
      if (_currentResponse.isNotEmpty) {
        // Добавляем ответ ассистента к контексту
        dialogContext.add({'role': 'assistant', 'content': _currentResponse});

        if (_conversationMode == ConversationMode.dialog) {
          // В режиме диалога обновляем глобальный контекст
          _globalDialogContext = List.from(dialogContext);
        } else {
          // В режиме Q&A сохраняем контекст только для reply
          // Сохраняем контекст для последнего сообщения (ответа AI)
          final lastMessage = _messages.last;
          _dialogContexts[lastMessage.id] = List.from(dialogContext);

          // Также сохраняем для пользовательского сообщения (для reply на вопрос)
          _dialogContexts[userMessage.id] = List.from(
            dialogContext.take(dialogContext.length - 1),
          );
        }
      }
    } catch (e) {
      setState(() {
        if (_messages.isNotEmpty && !_messages.last.isUser) {
          _messages[_messages.length - 1] = Message(
            text:
                '❌ Ошибка генерации: $e\n\nПопробуйте:\n'
                '• Перезагрузить модель\n'
                '• Выбрать другую модель\n'
                '• Проверить формат модели (GGUF)',
            isUser: false,
          );
        } else {
          _messages.add(Message(text: '❌ Ошибка генерации: $e', isUser: false));
        }
      });
      _scrollToBottom();
    } finally {
      setState(() {
        _isGenerating = false;
        _currentResponse = '';
      });
    }
  }

  /// Сохранить текущий диалог
  void _saveCurrentDialog() {
    if (_messages.isEmpty) return;

    final now = DateTime.now();

    if (_currentDialog == null) {
      // Создаем новый диалог
      final newDialog = DialogSession(
        id: now.millisecondsSinceEpoch.toString(),
        title: DialogSession.generateTitle(_messages),
        createdAt: now,
        updatedAt: now,
        messages: List.from(_messages),
        promptModeId: _currentPromptMode.id,
        conversationModeId: _conversationMode.name,
      );

      setState(() {
        _savedDialogs.insert(0, newDialog);
        _currentDialog = newDialog;
      });
    } else {
      // Обновляем существующий
      final updatedDialog = _currentDialog!.copyWith(
        title: DialogSession.generateTitle(_messages),
        updatedAt: now,
        messages: List.from(_messages),
        promptModeId: _currentPromptMode.id,
        conversationModeId: _conversationMode.name,
      );

      setState(() {
        final index = _savedDialogs.indexWhere(
          (d) => d.id == _currentDialog!.id,
        );
        if (index != -1) {
          _savedDialogs[index] = updatedDialog;
        }
        _currentDialog = updatedDialog;
      });
    }

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('💾 Диалог сохранен'),
          duration: Duration(seconds: 1),
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }

  /// Загрузить диалог
  void _loadDialog(DialogSession dialog) {
    setState(() {
      _currentDialog = dialog;
      _messages.clear();
      _messages.addAll(dialog.messages);
      _currentPromptMode = PromptModes.getById(dialog.promptModeId);

      // Восстанавливаем режим разговора
      if (dialog.conversationModeId == ConversationMode.dialog.name) {
        _conversationMode = ConversationMode.dialog;
      } else {
        _conversationMode = ConversationMode.qa;
      }
    });

    Navigator.pop(context); // Закрываем drawer

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('📂 Загружен: ${dialog.title}'),
          duration: const Duration(seconds: 2),
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }

  /// Создать новый диалог
  void _newDialog() {
    // Если есть несохраненные изменения, предложить сохранить
    if (_messages.isNotEmpty) {
      _saveCurrentDialog();
    }

    setState(() {
      _currentDialog = null;
      _messages.clear();
      _dialogContexts.clear();
      _globalDialogContext.clear();
      _replyToMessage = null;
    });

    Navigator.pop(context); // Закрываем drawer
  }

  /// Удалить диалог
  void _deleteDialog(DialogSession dialog) {
    setState(() {
      _savedDialogs.removeWhere((d) => d.id == dialog.id);

      // Если удаляем текущий диалог, очищаем его
      if (_currentDialog?.id == dialog.id) {
        _currentDialog = null;
        _messages.clear();
        _dialogContexts.clear();
        _globalDialogContext.clear();
      }
    });

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('🗑️ Диалог удален'),
          duration: Duration(seconds: 1),
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }

  /// Очистка чата
  void _clearChat() {
    // Автосохранение перед очисткой
    if (_messages.isNotEmpty) {
      _saveCurrentDialog();
    }

    setState(() {
      _currentDialog = null;
      _messages.clear();
      _dialogContexts.clear();
      _globalDialogContext.clear();
      _replyToMessage = null;
      _addSystemMessage('Чат очищен. Начат новый диалог.');
    });
  }

  /// Переключение режима разговора
  void _toggleConversationMode() {
    setState(() {
      if (_conversationMode == ConversationMode.dialog) {
        _conversationMode = ConversationMode.qa;
        _globalDialogContext.clear();
      } else {
        _conversationMode = ConversationMode.dialog;
        // Инициализируем глобальный контекст с системным промптом
        _globalDialogContext = [
          {'role': 'system', 'content': _currentPromptMode.systemPrompt},
        ];
      }
    });

    // Показать уведомление
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            '${_conversationMode.emoji} ${_conversationMode.name}: ${_conversationMode.description}',
          ),
          duration: const Duration(seconds: 2),
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }

  /// Построение промпта из контекста
  String _buildPromptFromContext(List<Map<String, String>> context) {
    final buffer = StringBuffer();
    for (var message in context) {
      final role = message['role'] ?? 'user';
      final content = message['content'] ?? '';

      if (role == 'system') {
        buffer.writeln('System: $content\n');
      } else if (role == 'user') {
        buffer.writeln('User: $content\n');
      } else if (role == 'assistant') {
        buffer.writeln('Assistant: $content\n');
      }
    }
    return buffer.toString();
  }

  /// Установить сообщение для reply
  void _setReplyTo(Message message) {
    setState(() {
      _replyToMessage = message;
    });

    // Прокрутить к полю ввода
    _scrollToBottom();
  }

  /// Отменить reply
  void _cancelReply() {
    setState(() {
      _replyToMessage = null;
    });
  }

  /// Изменить режим промпта
  void _changePromptMode(PromptMode mode) {
    setState(() {
      _currentPromptMode = mode;
    });

    // Показать уведомление
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('${mode.emoji} Режим изменён: ${mode.name}'),
          duration: const Duration(seconds: 2),
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }

  /// Показать контекстное меню для сообщения
  void _showMessageContextMenu(
    BuildContext context,
    Message message,
    Offset position,
  ) {
    final RenderBox overlay =
        Overlay.of(context).context.findRenderObject() as RenderBox;

    showMenu(
      context: context,
      position: RelativeRect.fromRect(
        Rect.fromLTWH(position.dx, position.dy, 0, 0),
        Rect.fromLTWH(0, 0, overlay.size.width, overlay.size.height),
      ),
      items: [
        PopupMenuItem(
          child: Row(
            children: [
              const Icon(Icons.reply, size: 20, color: Color(0xFFFF9800)),
              const SizedBox(width: 12),
              const Text('Ответить на сообщение'),
            ],
          ),
          onTap: () {
            // Используем Future.delayed чтобы не конфликтовать с закрытием меню
            Future.delayed(const Duration(milliseconds: 100), () {
              _setReplyTo(message);

              if (mounted) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: const Text('💬 Ответить на это сообщение'),
                    duration: const Duration(seconds: 1),
                    behavior: SnackBarBehavior.floating,
                  ),
                );
              }
            });
          },
        ),
        PopupMenuItem(
          child: Row(
            children: [
              const Icon(Icons.copy, size: 20, color: Colors.blue),
              const SizedBox(width: 12),
              const Text('Копировать текст'),
            ],
          ),
          onTap: () {
            Clipboard.setData(ClipboardData(text: message.text));

            Future.delayed(const Duration(milliseconds: 100), () {
              if (mounted) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: const Text('📋 Текст скопирован'),
                    duration: const Duration(seconds: 1),
                    behavior: SnackBarBehavior.floating,
                  ),
                );
              }
            });
          },
        ),
        if (message.replyToId != null)
          PopupMenuItem(
            child: Row(
              children: [
                const Icon(Icons.history, size: 20, color: Colors.grey),
                const SizedBox(width: 12),
                const Text('Показать контекст'),
              ],
            ),
            onTap: () {
              Future.delayed(const Duration(milliseconds: 100), () {
                _showMessageContext(message);
              });
            },
          ),
        PopupMenuItem(
          child: Row(
            children: [
              Icon(Icons.info_outline, size: 20, color: Colors.grey[600]),
              const SizedBox(width: 12),
              const Text('Информация'),
            ],
          ),
          onTap: () {
            Future.delayed(const Duration(milliseconds: 100), () {
              _showMessageInfo(message);
            });
          },
        ),
      ],
    );
  }

  /// Показать контекст сообщения
  void _showMessageContext(Message message) {
    final dialogContext = _dialogContexts[message.id];

    if (dialogContext == null || dialogContext.isEmpty) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Контекст не найден'),
            duration: Duration(seconds: 2),
            behavior: SnackBarBehavior.floating,
          ),
        );
      }
      return;
    }

    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Контекст диалога'),
        content: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: dialogContext.map((msg) {
              final role = msg['role'] ?? '';
              final content = msg['content'] ?? '';

              String roleLabel;
              Color roleColor;
              IconData roleIcon;

              switch (role) {
                case 'system':
                  roleLabel = 'Система';
                  roleColor = Colors.purple;
                  roleIcon = Icons.settings;
                  break;
                case 'user':
                  roleLabel = 'Пользователь';
                  roleColor = Colors.blue;
                  roleIcon = Icons.person;
                  break;
                case 'assistant':
                  roleLabel = 'Ассистент';
                  roleColor = Colors.green;
                  roleIcon = Icons.auto_awesome;
                  break;
                default:
                  roleLabel = role;
                  roleColor = Colors.grey;
                  roleIcon = Icons.message;
              }

              return Padding(
                padding: const EdgeInsets.only(bottom: 12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Icon(roleIcon, size: 16, color: roleColor),
                        const SizedBox(width: 8),
                        Text(
                          roleLabel,
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            color: roleColor,
                            fontSize: 13,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: roleColor.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        content.length > 200
                            ? '${content.substring(0, 200)}...'
                            : content,
                        style: const TextStyle(fontSize: 12),
                      ),
                    ),
                  ],
                ),
              );
            }).toList(),
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Закрыть'),
          ),
        ],
      ),
    );
  }

  /// Показать информацию о сообщении
  void _showMessageInfo(Message message) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Информация о сообщении'),
        content: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisSize: MainAxisSize.min,
          children: [
            _buildInfoRow('ID:', message.id),
            _buildInfoRow(
              'Время:',
              '${message.timestamp.hour.toString().padLeft(2, '0')}:'
                  '${message.timestamp.minute.toString().padLeft(2, '0')}:'
                  '${message.timestamp.second.toString().padLeft(2, '0')}',
            ),
            _buildInfoRow(
              'Дата:',
              '${message.timestamp.day}.${message.timestamp.month}.${message.timestamp.year}',
            ),
            _buildInfoRow(
              'Тип:',
              message.isUser ? 'Пользователь' : 'Ассистент',
            ),
            if (message.replyToId != null)
              _buildInfoRow('Reply ID:', message.replyToId!),
            if (message.imagePaths != null && message.imagePaths!.isNotEmpty)
              _buildInfoRow(
                'Изображений:',
                message.imagePaths!.length.toString(),
              ),
            _buildInfoRow('Символов:', message.text.length.toString()),
            if (_dialogContexts[message.id] != null)
              _buildInfoRow(
                'Контекст:',
                '${_dialogContexts[message.id]!.length} сообщений',
              ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Закрыть'),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 100,
            child: Text(
              label,
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
            ),
          ),
          Expanded(child: Text(value, style: const TextStyle(fontSize: 13))),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final bgColor = isDark ? const Color(0xFF343541) : Colors.white;
    final userBubbleColor = isDark
        ? const Color(0xFF10A37F)
        : const Color(0xFF10A37F);
    final aiBubbleColor = isDark
        ? const Color(0xFF444654)
        : const Color(0xFFF7F7F8);

    return Scaffold(
      drawer: _buildDrawer(context),
      appBar: AppBar(
        title: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 36,
              height: 36,
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFFFF9800), Color(0xFFFFB74D)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(18),
                boxShadow: [
                  BoxShadow(
                    color: const Color(0xFFFF9800).withOpacity(0.4),
                    blurRadius: 8,
                    offset: const Offset(0, 2),
                  ),
                ],
              ),
              child: Text(
                _currentPromptMode.emoji,
                style: const TextStyle(fontSize: 20),
              ),
            ),
            const SizedBox(width: 12),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                const Text(
                  'Шридхар Махарадж ИИ',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600),
                ),
                Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      _currentPromptMode.name,
                      style: const TextStyle(fontSize: 10, color: Colors.grey),
                    ),
                    const Text(
                      ' • ',
                      style: TextStyle(fontSize: 10, color: Colors.grey),
                    ),
                    Text(
                      _conversationMode.name,
                      style: const TextStyle(fontSize: 10, color: Colors.grey),
                    ),
                  ],
                ),
              ],
            ),
          ],
        ),
        actions: [
          // Кнопка выбора режима промптов
          IconButton(
            icon: Text(
              _currentPromptMode.emoji,
              style: const TextStyle(fontSize: 22),
            ),
            onPressed: _showPromptModesMenu,
            tooltip: 'Режим: ${_currentPromptMode.name}',
          ),
          // Переключатель режима разговора
          IconButton(
            icon: Text(
              _conversationMode.emoji,
              style: const TextStyle(fontSize: 20),
            ),
            onPressed: _toggleConversationMode,
            tooltip:
                '${_conversationMode.name}\n${_conversationMode.description}',
          ),
          // Сохранить диалог
          if (_messages.isNotEmpty)
            IconButton(
              icon: const Icon(Icons.save_outlined),
              onPressed: _saveCurrentDialog,
              tooltip: 'Сохранить диалог',
            ),
          // Очистить/Новый чат
          if (_isModelLoaded)
            IconButton(
              icon: const Icon(Icons.add),
              onPressed: _newDialog,
              tooltip: 'Новый диалог',
            ),
          // Менеджер моделей
          PopupMenuButton(
            icon: Icon(_isModelLoaded ? Icons.more_vert : Icons.file_open),
            tooltip: 'Меню',
            itemBuilder: (context) => [
              PopupMenuItem(
                value: 'models',
                child: const Row(
                  children: [
                    Icon(Icons.cloud_download_outlined),
                    SizedBox(width: 12),
                    Text('Менеджер моделей'),
                  ],
                ),
              ),
              if (_isModelLoaded)
                PopupMenuItem(
                  value: 'reload',
                  child: Row(
                    children: [
                      Icon(Icons.refresh, color: Colors.orange[700]),
                      const SizedBox(width: 12),
                      const Text('Перезагрузить модель'),
                    ],
                  ),
                ),
            ],
            onSelected: (value) {
              if (value == 'models') {
                _openModelManager();
              } else if (value == 'reload') {
                _loadModel();
              }
            },
          ),
        ],
      ),
      body: Column(
        children: [
          // Список сообщений
          Expanded(
            child: _messages.isEmpty
                ? Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Container(
                          width: 80,
                          height: 80,
                          decoration: BoxDecoration(
                            color: const Color(0xFF10A37F).withOpacity(0.1),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: const Icon(
                            Icons.auto_awesome,
                            size: 40,
                            color: Color(0xFF10A37F),
                          ),
                        ),
                        const SizedBox(height: 24),
                        const Text(
                          'Shridhar 8K Multimodal',
                          style: TextStyle(
                            fontSize: 24,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          'Мультиязычная духовная модель',
                          style: TextStyle(
                            fontSize: 14,
                            color: Colors.grey[600],
                          ),
                        ),
                        const SizedBox(height: 32),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 48),
                          child: Wrap(
                            spacing: 12,
                            runSpacing: 12,
                            alignment: WrapAlignment.center,
                            children: [
                              _buildFeatureChip('🇷🇺 Русский', isDark),
                              _buildFeatureChip('🇪🇸 Испанский', isDark),
                              _buildFeatureChip('🇮🇳 Хинди', isDark),
                              _buildFeatureChip('🇹🇭 Тайский', isDark),
                              _buildFeatureChip('🧘 Медитация', isDark),
                              _buildFeatureChip('🎵 ИКАРОС', isDark),
                              _buildFeatureChip('🎬 Love Destiny', isDark),
                            ],
                          ),
                        ),
                      ],
                    ),
                  )
                : ListView.builder(
                    controller: _scrollController,
                    padding: const EdgeInsets.all(16),
                    itemCount: _messages.length,
                    itemBuilder: (context, index) {
                      final message = _messages[index];
                      return _buildMessageBubble(
                        message,
                        userBubbleColor,
                        aiBubbleColor,
                        isDark,
                      );
                    },
                  ),
          ),

          // Предпросмотр выбранных изображений
          if (_selectedImages.isNotEmpty)
            Container(
              height: 100,
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              decoration: BoxDecoration(
                color: aiBubbleColor,
                border: Border(
                  top: BorderSide(color: Colors.grey.withOpacity(0.2)),
                ),
              ),
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                itemCount: _selectedImages.length,
                itemBuilder: (context, index) {
                  return Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: Stack(
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(8),
                          child: Image.file(
                            File(_selectedImages[index]),
                            width: 80,
                            height: 80,
                            fit: BoxFit.cover,
                          ),
                        ),
                        Positioned(
                          top: 4,
                          right: 4,
                          child: GestureDetector(
                            onTap: () => _removeImage(index),
                            child: Container(
                              padding: const EdgeInsets.all(4),
                              decoration: const BoxDecoration(
                                color: Colors.black54,
                                shape: BoxShape.circle,
                              ),
                              child: const Icon(
                                Icons.close,
                                size: 16,
                                color: Colors.white,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  );
                },
              ),
            ),

          // Панель reply (если выбрано сообщение)
          if (_replyToMessage != null) _buildReplyPanel()!,

          // Поле ввода
          Container(
            decoration: BoxDecoration(
              color: bgColor,
              border: Border(
                top: BorderSide(color: Colors.grey.withOpacity(0.2)),
              ),
            ),
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            child: Row(
              children: [
                // Кнопка добавления изображения
                IconButton(
                  icon: const Icon(Icons.add_photo_alternate),
                  onPressed: _isGenerating ? null : _pickImages,
                  color: const Color(0xFF10A37F),
                ),
                const SizedBox(width: 8),

                // Текстовое поле
                Expanded(
                  child: Container(
                    decoration: BoxDecoration(
                      color: aiBubbleColor,
                      borderRadius: BorderRadius.circular(24),
                    ),
                    child: TextField(
                      controller: _messageController,
                      maxLines: null,
                      enabled: !_isGenerating && _isModelLoaded,
                      decoration: const InputDecoration(
                        hintText: 'Напишите сообщение...',
                        border: InputBorder.none,
                        contentPadding: EdgeInsets.symmetric(
                          horizontal: 20,
                          vertical: 12,
                        ),
                      ),
                      onSubmitted: (_) => _sendMessage(),
                    ),
                  ),
                ),
                const SizedBox(width: 8),

                // Кнопка отправки
                Container(
                  decoration: BoxDecoration(
                    color: _isGenerating
                        ? Colors.grey
                        : const Color(0xFF10A37F),
                    shape: BoxShape.circle,
                  ),
                  child: IconButton(
                    icon: Icon(
                      _isGenerating ? Icons.stop : Icons.send,
                      color: Colors.white,
                    ),
                    onPressed: !_isModelLoaded || _isGenerating
                        ? null
                        : _sendMessage,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFeatureChip(String label, bool isDark) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF444654) : const Color(0xFFF7F7F8),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.grey.withOpacity(0.2)),
      ),
      child: Text(label, style: const TextStyle(fontSize: 12)),
    );
  }

  Widget _buildMessageBubble(
    Message message,
    Color userBubbleColor,
    Color aiBubbleColor,
    bool isDark,
  ) {
    final isUser = message.isUser;
    final hasImages =
        message.imagePaths != null && message.imagePaths!.isNotEmpty;
    final isReplyingTo = _replyToMessage?.id == message.id;

    return GestureDetector(
      onLongPress: () {
        // Долгое нажатие для установки reply
        _setReplyTo(message);

        // Показать feedback
        HapticFeedback.mediumImpact();

        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: const Text('💬 Ответить на это сообщение'),
              duration: const Duration(seconds: 1),
              behavior: SnackBarBehavior.floating,
            ),
          );
        }
      },
      onSecondaryTapDown: (details) {
        // Правая кнопка мыши - показать контекстное меню
        _showMessageContextMenu(context, message, details.globalPosition);
      },
      child: Container(
        padding: const EdgeInsets.only(bottom: 16),
        decoration: isReplyingTo
            ? BoxDecoration(
                color: const Color(0xFFFF9800).withOpacity(0.1),
                border: const Border(
                  left: BorderSide(color: Color(0xFFFF9800), width: 3),
                ),
              )
            : null,
        child: Padding(
          padding: isReplyingTo
              ? const EdgeInsets.only(left: 8)
              : EdgeInsets.zero,
          child: Row(
            mainAxisAlignment: isUser
                ? MainAxisAlignment.end
                : MainAxisAlignment.start,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              if (!isUser) ...[
                Container(
                  width: 32,
                  height: 32,
                  decoration: BoxDecoration(
                    color: const Color(0xFF10A37F),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(
                    Icons.auto_awesome,
                    color: Colors.white,
                    size: 16,
                  ),
                ),
                const SizedBox(width: 12),
              ],
              Flexible(
                child: Column(
                  crossAxisAlignment: isUser
                      ? CrossAxisAlignment.end
                      : CrossAxisAlignment.start,
                  children: [
                    if (hasImages)
                      Padding(
                        padding: const EdgeInsets.only(bottom: 8),
                        child: Wrap(
                          spacing: 8,
                          runSpacing: 8,
                          children: message.imagePaths!.map((imagePath) {
                            return ClipRRect(
                              borderRadius: BorderRadius.circular(12),
                              child: Image.file(
                                File(imagePath),
                                width: 200,
                                height: 200,
                                fit: BoxFit.cover,
                              ),
                            );
                          }).toList(),
                        ),
                      ),
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 16,
                        vertical: 12,
                      ),
                      decoration: BoxDecoration(
                        color: isUser ? userBubbleColor : aiBubbleColor,
                        borderRadius: BorderRadius.circular(18),
                      ),
                      child: SelectableText(
                        message.text,
                        style: TextStyle(
                          color: isUser ? Colors.white : null,
                          fontSize: 15,
                          height: 1.4,
                        ),
                        cursorColor: isUser
                            ? Colors.white
                            : const Color(0xFF10A37F),
                        selectionControls: materialTextSelectionControls,
                      ),
                    ),
                  ],
                ),
              ),
              if (isUser) ...[
                const SizedBox(width: 12),
                Container(
                  width: 32,
                  height: 32,
                  decoration: BoxDecoration(
                    color: Colors.grey[600],
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(
                    Icons.person,
                    color: Colors.white,
                    size: 16,
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  /// Построение Drawer со списком диалогов
  Widget _buildDrawer(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Drawer(
      child: Column(
        children: [
          DrawerHeader(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [
                  const Color(0xFFFF9800),
                  const Color(0xFFFFB74D).withOpacity(0.8),
                ],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                const Row(
                  children: [
                    Icon(
                      Icons.chat_bubble_outline,
                      size: 40,
                      color: Colors.white,
                    ),
                    SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        'Диалоги',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 28,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                Text(
                  '${_savedDialogs.length} сохраненных',
                  style: const TextStyle(color: Colors.white70, fontSize: 14),
                ),
              ],
            ),
          ),

          // Кнопка создания нового диалога
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            child: ElevatedButton.icon(
              onPressed: _newDialog,
              icon: const Icon(Icons.add),
              label: const Text('Новый диалог'),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFFFF9800),
                foregroundColor: Colors.white,
                minimumSize: const Size(double.infinity, 48),
              ),
            ),
          ),

          const Divider(),

          // Список сохраненных диалогов
          Expanded(
            child: _savedDialogs.isEmpty
                ? Center(
                    child: Padding(
                      padding: const EdgeInsets.all(24),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(
                            Icons.chat_outlined,
                            size: 64,
                            color: isDark ? Colors.grey[700] : Colors.grey[300],
                          ),
                          const SizedBox(height: 16),
                          Text(
                            'Нет сохраненных диалогов',
                            style: TextStyle(
                              fontSize: 16,
                              color: isDark
                                  ? Colors.grey[600]
                                  : Colors.grey[400],
                            ),
                            textAlign: TextAlign.center,
                          ),
                          const SizedBox(height: 8),
                          Text(
                            'Начните новый диалог и он автоматически сохранится',
                            style: TextStyle(
                              fontSize: 12,
                              color: isDark
                                  ? Colors.grey[700]
                                  : Colors.grey[500],
                            ),
                            textAlign: TextAlign.center,
                          ),
                        ],
                      ),
                    ),
                  )
                : ListView.builder(
                    itemCount: _savedDialogs.length,
                    itemBuilder: (context, index) {
                      final dialog = _savedDialogs[index];
                      final isActive = _currentDialog?.id == dialog.id;
                      final messageCount = dialog.messages
                          .where((m) => m.isUser)
                          .length;

                      return ListTile(
                        leading: Container(
                          width: 40,
                          height: 40,
                          decoration: BoxDecoration(
                            color: isActive
                                ? const Color(0xFFFF9800)
                                : (isDark
                                      ? Colors.grey[800]
                                      : Colors.grey[200]),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Icon(
                            Icons.chat,
                            color: isActive
                                ? Colors.white
                                : (isDark
                                      ? Colors.grey[400]
                                      : Colors.grey[600]),
                          ),
                        ),
                        title: Text(
                          dialog.title,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(
                            fontWeight: isActive
                                ? FontWeight.bold
                                : FontWeight.normal,
                            color: isActive ? const Color(0xFFFF9800) : null,
                          ),
                        ),
                        subtitle: Text(
                          '$messageCount сообщений • ${_formatDate(dialog.updatedAt)}',
                          style: TextStyle(
                            fontSize: 12,
                            color: isDark ? Colors.grey[500] : Colors.grey[600],
                          ),
                        ),
                        selected: isActive,
                        selectedTileColor: const Color(
                          0xFFFF9800,
                        ).withOpacity(0.1),
                        trailing: IconButton(
                          icon: const Icon(Icons.delete_outline, size: 20),
                          onPressed: () => _deleteDialog(dialog),
                          tooltip: 'Удалить',
                        ),
                        onTap: () => _loadDialog(dialog),
                      );
                    },
                  ),
          ),

          const Divider(),

          // Настройки внизу
          ListTile(
            leading: const Icon(Icons.save_outlined),
            title: const Text('Сохранить текущий'),
            enabled: _messages.isNotEmpty,
            onTap: () {
              _saveCurrentDialog();
              Navigator.pop(context);
            },
          ),
        ],
      ),
    );
  }

  /// Форматирование даты
  String _formatDate(DateTime date) {
    final now = DateTime.now();
    final diff = now.difference(date);

    if (diff.inMinutes < 1) return 'только что';
    if (diff.inHours < 1) return '${diff.inMinutes} мин назад';
    if (diff.inDays < 1) return '${diff.inHours} ч назад';
    if (diff.inDays < 7) return '${diff.inDays} дн назад';

    return '${date.day}.${date.month}.${date.year}';
  }

  /// Показать меню выбора режима промптов
  void _showPromptModesMenu() {
    showModalBottomSheet(
      context: context,
      builder: (context) {
        return Container(
          padding: const EdgeInsets.symmetric(vertical: 16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Заголовок
              Padding(
                padding: const EdgeInsets.symmetric(
                  horizontal: 16,
                  vertical: 8,
                ),
                child: Row(
                  children: [
                    const Icon(Icons.psychology, color: Color(0xFFFF9800)),
                    const SizedBox(width: 12),
                    const Expanded(
                      child: Text(
                        'Выберите режим промпта',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                    IconButton(
                      icon: const Icon(Icons.info_outline),
                      onPressed: () {
                        Navigator.pop(context);
                        _showPromptInfo(context);
                      },
                      tooltip: 'О режимах',
                    ),
                  ],
                ),
              ),
              const Divider(),
              // Список режимов
              ...PromptModes.all.map((mode) {
                final isSelected = _currentPromptMode.id == mode.id;
                return ListTile(
                  leading: Text(
                    mode.emoji,
                    style: const TextStyle(fontSize: 28),
                  ),
                  title: Text(
                    mode.name,
                    style: TextStyle(
                      fontWeight: isSelected
                          ? FontWeight.bold
                          : FontWeight.normal,
                      color: isSelected ? const Color(0xFFFF9800) : null,
                    ),
                  ),
                  subtitle: Text(
                    mode.description,
                    style: const TextStyle(fontSize: 12),
                  ),
                  trailing: isSelected
                      ? const Icon(Icons.check_circle, color: Color(0xFFFF9800))
                      : null,
                  selected: isSelected,
                  selectedTileColor: const Color(0xFFFF9800).withOpacity(0.1),
                  onTap: () {
                    _changePromptMode(mode);
                    Navigator.pop(context);
                  },
                );
              }),
            ],
          ),
        );
      },
    );
  }

  /// Показать информацию о режимах
  void _showPromptInfo(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('О режимах промптов'),
        content: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text(
                'Различные режимы изменяют поведение AI, давая ему разные инструкции и роли:',
                style: TextStyle(fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              ...PromptModes.all.map((mode) {
                return Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Text(
                            mode.emoji,
                            style: const TextStyle(fontSize: 20),
                          ),
                          const SizedBox(width: 8),
                          Text(
                            mode.name,
                            style: const TextStyle(fontWeight: FontWeight.bold),
                          ),
                        ],
                      ),
                      const SizedBox(height: 4),
                      Text(
                        mode.description,
                        style: TextStyle(fontSize: 13, color: Colors.grey[600]),
                      ),
                    ],
                  ),
                );
              }),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Закрыть'),
          ),
        ],
      ),
    );
  }

  /// Построение панели reply
  Widget? _buildReplyPanel() {
    if (_replyToMessage == null) return null;

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(
        color: const Color(0xFFFF9800).withOpacity(0.1),
        border: const Border(
          left: BorderSide(color: Color(0xFFFF9800), width: 4),
        ),
      ),
      child: Row(
        children: [
          const Icon(Icons.reply, size: 20, color: Color(0xFFFF9800)),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  _replyToMessage!.isUser
                      ? 'Ваше сообщение'
                      : 'Шридхар Махарадж',
                  style: const TextStyle(
                    fontWeight: FontWeight.bold,
                    fontSize: 12,
                    color: Color(0xFFFF9800),
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  _replyToMessage!.text.length > 50
                      ? '${_replyToMessage!.text.substring(0, 50)}...'
                      : _replyToMessage!.text,
                  style: const TextStyle(fontSize: 13),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
          IconButton(
            icon: const Icon(Icons.close, size: 20),
            onPressed: _cancelReply,
            tooltip: 'Отменить',
          ),
        ],
      ),
    );
  }
}
