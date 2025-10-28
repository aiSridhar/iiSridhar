# Инструкция по деплою в App Store

## Шридхар Махарадж ИИ (isridhar)

### Информация о приложении

- **Bundle ID**: com.sridharmaharaj
- **SKU**: com.sridharmaharaj
- **Apple ID**: 1481472115
- **Team ID**: Ваш Apple Developer Team ID
- **Версия**: 1.0.0
- **Build**: 1

### 1. Подготовка к сборке

#### Настройки Xcode

1. Откройте проект в Xcode:
```bash
cd /Users/anton/proj/ai.nativemind.net/libs/isridhar
open ios/Runner.xcworkspace
```

2. В Xcode:
   - Выберите Runner в Project Navigator
   - В Target Runner → Signing & Capabilities:
     - Установите Team: ваш Apple Developer Team
     - Bundle Identifier: com.sridharmaharaj (уже настроено)
     - Signing Certificate: Apple Development/Distribution

#### Сертификаты

Убедитесь, что у вас есть:
- ✅ iOS Distribution Certificate
- ✅ App Store Provisioning Profile для com.sridharmaharaj

### 2. Сборка для App Store

#### Опция А: Через Xcode

```bash
cd /Users/anton/proj/ai.nativemind.net/libs/isridhar
flutter build ios --release
open ios/Runner.xcworkspace
```

В Xcode:
1. Product → Scheme → Edit Scheme → Run → Build Configuration: Release
2. Product → Destination → Any iOS Device
3. Product → Archive
4. После архивирования: Distribute App → App Store Connect → Upload

#### Опция Б: Через командную строку

```bash
# Сборка
flutter build ios --release

# Создание архива (IPA)
cd ios
xcodebuild -workspace Runner.xcworkspace \
           -scheme Runner \
           -sdk iphoneos \
           -configuration Release \
           -archivePath $PWD/build/Runner.xcarchive \
           clean archive

# Экспорт IPA
xcodebuild -exportArchive \
           -archivePath $PWD/build/Runner.xcarchive \
           -exportOptionsPlist ExportOptions.plist \
           -exportPath $PWD/build

# Загрузка в App Store Connect
xcrun altool --upload-app --type ios \
             --file build/isridhar.ipa \
             --apiKey YOUR_API_KEY \
             --apiIssuer YOUR_ISSUER_ID
```

### 3. Создание ExportOptions.plist

Создайте файл `ios/ExportOptions.plist`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>method</key>
    <string>app-store</string>
    <key>teamID</key>
    <string>YOUR_TEAM_ID</string>
    <key>uploadSymbols</key>
    <true/>
    <key>uploadBitcode</key>
    <false/>
</dict>
</plist>
```

### 4. App Store Connect

1. Перейдите на https://appstoreconnect.apple.com/apps/1481472115
2. Заполните метаданные:
   - Название: Шридхар Махарадж ИИ
   - Подзаголовок: Духовный наставник с ИИ
   - Категория: Lifestyle
   - Описание: см. APP_STORE_METADATA.md
   - Ключевые слова: meditation, yoga, spiritual, AI, mindfulness, peace
   - Скриншоты: 5-8 скриншотов (требуются для iPhone 6.7" и iPad)

3. Приватность:
   - ✅ Отметить "Приложение не собирает данные"
   - Политика конфиденциальности: https://nativemind.net/privacy-policy-sridhar

4. TestFlight:
   - Загрузите build
   - Добавьте внешних тестировщиков
   - Подождите Apple Review для TestFlight

5. Submit for Review:
   - Заполните все обязательные поля
   - Добавьте скриншоты
   - Submit

### 5. Требования для App Store

#### Скриншоты

Необходимые размеры:
- iPhone 6.7": 1290 x 2796 px (обязательно)
- iPhone 6.5": 1242 x 2688 px
- iPhone 5.5": 1242 x 2208 px  
- iPad Pro 12.9": 2048 x 2732 px (обязательно для iPad)

#### Иконки

- App Icon: 1024x1024 px (уже настроено в Assets.xcassets)

### 6. Чеклист перед отправкой

- [ ] Все скриншоты готовы
- [ ] Иконка 1024x1024 установлена
- [ ] Описание на русском и английском заполнено
- [ ] Политика конфиденциальности доступна
- [ ] Контактная информация указана
- [ ] Тестирование на реальном устройстве пройдено
- [ ] Все пермишены (камера, фото, микрофон) обоснованы
- [ ] Build успешно загружен в TestFlight
- [ ] Версия и build number корректны (1.0.0 / 1)

### 7. После одобрения

1. App будет доступен в App Store через 24-48 часов
2. Мониторьте отзывы и крэш-репорты
3. Подготовьте следующее обновление

### 8. Обновление версии

Для следующих версий:

1. Обновите версию в `pubspec.yaml`:
```yaml
version: 1.0.1+2  # version+build
```

2. Соберите и загрузите новую версию
3. В App Store Connect создайте новую версию
4. Submit for Review

---

**Важно**: Перед первой загрузкой убедитесь, что:
- У вас есть активный Apple Developer account ($99/год)
- Приложение с Bundle ID com.sridharmaharaj создано в App Store Connect
- У вас есть права на публикацию

## Troubleshooting

### Проблема: "No such module 'flutter_llama'"
```bash
cd ios
pod install
pod update
```

### Проблема: "Code signing failed"
- Проверьте Team ID в Xcode
- Обновите provisioning profiles
- Используйте `flutter clean` и пересоберите

### Проблема: "Library not loaded"
- Убедитесь что все .a библиотеки скопированы
- Проверьте podspec конфигурацию
- Очистите Derived Data в Xcode

## Контакты

Email: licensing@nativemind.net  
Website: https://nativemind.net

