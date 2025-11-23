# PHPTelebot Examples

This directory contains example bots demonstrating various features of PHPTelebot.

## Available Examples

### 1. Keyboard Builder Example (`keyboard_builder_example.php`)

Demonstrates how to use the `KeyboardBuilder` helper to create various types of keyboards:

- **Inline keyboards** with callback buttons
- **Reply keyboards** with custom buttons
- **URL buttons** for external links
- **Web App buttons** for Telegram Mini Apps
- **Request buttons** for contact, location, and polls
- **Switch inline buttons** for inline mode
- **Login buttons** for authentication
- **Force reply** and **remove keyboard** markups

**Usage:**
```bash
php keyboard_builder_example.php
```

**Commands:**
- `/inline` - Simple inline keyboard
- `/urls` - URL buttons
- `/reply` - Reply keyboard with contact/location requests
- `/webapp` - Web App button
- `/grid` - Grid keyboard (number pad)
- `/remove` - Remove keyboard
- `/force` - Force reply

---

### 2. Web App Bot (`webapp_bot.php`)

Shows how to integrate Telegram Web Apps with your bot:

- **Creating Web App buttons** using KeyboardBuilder
- **Validating Web App data** for security
- **Parsing Web App init data**
- **Extracting user and chat information**
- **Handling Web App messages**

**Usage:**
```bash
php webapp_bot.php
```

**Commands:**
- `/start` - Show Web App button
- `/validate` - Validate Web App data
- `/webapp_reply` - Reply keyboard with Web App

**Prerequisites:**
- You need a hosted Web App (HTML/JS)
- Web App URL must be HTTPS
- Configure the URL in the example

---

### 3. Group Management Bot (`group_management_bot.php`)

Complete example of a bot for managing Telegram groups:

**Features:**
- Welcome new members automatically
- Farewell messages for leaving members
- **Admin commands:**
  - `/ban` - Ban users from the group
  - `/unban` - Unban users
  - `/pin` - Pin messages
  - `/setrules` - Set group rules
  - `/rules` - Show group rules
  - `/admin` - Admin panel with inline keyboard

**Usage:**
```bash
php group_management_bot.php
```

**Setup:**
1. Add bot to your group
2. Make bot an administrator
3. Grant necessary permissions (ban users, pin messages, etc.)

---

### 4. MediaGroup Handler Example (`mediagroup_example.php`)

Demonstrates how to send media groups (albums) using `MediaGroupHandler`:

- **Photo albums** - Send multiple photos as an album
- **Mixed media** - Combine photos and videos
- **Documents** - Send document albums
- **From files** - Create albums from file array
- **With captions** - Add captions with HTML formatting
- **Inspect and validate** - Check media count before sending

**Usage:**
```bash
php mediagroup_example.php
```

**Commands:**
- `/album` - Send photo album from URLs
- `/files` - Send from local files
- `/mixed` - Mixed photos and videos
- `/docs` - Document album
- `/formatted` - Album with HTML captions
- `/inspect` - Inspect media group before sending

---

## Getting Started

### Prerequisites

- PHP 5.4 or higher
- cURL extension
- Composer (recommended)
- Telegram Bot Token from [@BotFather](https://t.me/BotFather)

### Installation

1. Clone or download PHPTelebot
2. Install dependencies:
   ```bash
   composer install
   ```
3. Replace `'YOUR_BOT_TOKEN'` with your actual bot token in the example files
4. Run the example:
   ```bash
   php examples/keyboard_builder_example.php
   ```

### Configuration

Each example needs your bot token. Replace this line in all examples:

```php
$bot = new PHPTelebot('YOUR_BOT_TOKEN');
```

With:

```php
$bot = new PHPTelebot('123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11');
```

### Running in Long Polling Mode

All examples run in Long Polling mode by default. Simply execute them from command line:

```bash
php examples/keyboard_builder_example.php
```

The bot will start listening for updates. Press `Ctrl+C` to stop.

### Running in Webhook Mode

To run examples in webhook mode:

1. Upload the example to a web server with HTTPS
2. Set webhook URL:
   ```bash
   curl "https://api.telegram.org/bot<YOUR_TOKEN>/setWebhook?url=https://yourdomain.com/examples/keyboard_builder_example.php"
   ```
3. The bot will now receive updates via webhook

---

## Using the Helpers

### KeyboardBuilder

```php
use PHPTelebot\Helpers\KeyboardBuilder;

// Inline keyboard
$keyboard = KeyboardBuilder::inline()
    ->addButton('Option 1', 'data_1')
    ->addButton('Option 2', 'data_2')
    ->addRow()
    ->addUrlButton('Visit Site', 'https://example.com')
    ->build();

Bot::sendMessage('Choose:', ['reply_markup' => $keyboard]);
```

### MediaGroupHandler

```php
use PHPTelebot\Helpers\MediaGroupHandler;

$album = MediaGroupHandler::create()
    ->addPhoto('photo1.jpg', ['caption' => 'Photo 1'])
    ->addPhoto('photo2.jpg', ['caption' => 'Photo 2'])
    ->send($chatId);
```

### WebAppHandler

```php
use PHPTelebot\Helpers\WebAppHandler;

$webApp = new WebAppHandler($botToken);

// Validate data
if ($webApp->validate($initData)) {
    $user = $webApp->getUser($initData);
    echo "User: " . $user['first_name'];
}
```

### WebhookValidator

```php
use PHPTelebot\Helpers\WebhookValidator;

$validator = new WebhookValidator($botToken, $secretToken);

try {
    $validator->validate();
    // Process webhook update
} catch (WebhookException $e) {
    // Invalid webhook request
    http_response_code(403);
    exit;
}
```

---

## Advanced Usage

### Error Handling

All helpers use custom exceptions for better error handling:

```php
use PHPTelebot\Exceptions\ApiException;
use PHPTelebot\Exceptions\ValidationException;
use PHPTelebot\Exceptions\WebhookException;

try {
    Bot::sendMessage('Hello!');
} catch (ApiException $e) {
    echo 'API Error: ' . $e->getMessage();
    echo 'API Description: ' . $e->getApiDescription();
} catch (ValidationException $e) {
    echo 'Validation Error: ' . $e->getMessage();
    echo 'Field: ' . $e->getField();
}
```

---

## Need Help?

- **Documentation:** [README.md](../README.md)
- **Telegram Bot API:** [https://core.telegram.org/bots/api](https://core.telegram.org/bots/api)
- **Issues:** [GitHub Issues](https://github.com/radyakaze/phptelebot/issues)
- **Sample Bot:** `sample.php` in the root directory

---

## Contributing

Found a bug or have an idea for a new example? Feel free to:

1. Fork the repository
2. Create your feature branch
3. Submit a pull request

---

## License

GPL-3.0 - See [LICENSE](../LICENSE) file for details.
