<?php
/**
 * Web App Bot Example
 *
 * This example demonstrates how to use Telegram Web Apps with PHPTelebot
 *
 * @see https://core.telegram.org/bots/webapps
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTelebot\Helpers\KeyboardBuilder;
use PHPTelebot\Helpers\WebAppHandler;

$bot = new PHPTelebot('YOUR_BOT_TOKEN');
$webAppHandler = new WebAppHandler('YOUR_BOT_TOKEN');

// Start command - show Web App button
$bot->cmd('/start', function () {
    $keyboard = KeyboardBuilder::inline()
        ->addWebAppButton('Open Web App', 'https://your-webapp-url.com')
        ->build();

    return Bot::sendMessage('Click the button to open Web App:', [
        'reply_markup' => $keyboard
    ]);
});

// Handle Web App data
$bot->on('web_app_data', function () use ($webAppHandler) {
    $message = Bot::message();

    // Extract Web App data
    $webAppData = WebAppHandler::extractFromMessage($message);

    if ($webAppData) {
        // Process the data from Web App
        $processedData = json_decode($webAppData, true);

        return Bot::sendMessage(
            "Received data from Web App:\n" . print_r($processedData, true)
        );
    }

    return Bot::sendMessage('No data received from Web App');
});

// Validate Web App init data (for webhook endpoint)
$bot->cmd('/validate', function () use ($webAppHandler) {
    try {
        // Example init data (in real scenario, this comes from your Web App)
        $initData = "query_id=AAHdF6IQAAAAAN0XohDhrOrc&user=%7B%22id%22%3A279058397...";

        // Validate the data
        if ($webAppHandler->validate($initData)) {
            $userData = $webAppHandler->getUser($initData);

            return Bot::sendMessage(
                "Web App data is valid!\nUser: " . $userData['first_name']
            );
        }
    } catch (Exception $e) {
        return Bot::sendMessage('Validation failed: ' . $e->getMessage());
    }
});

// Example: Create reply keyboard with Web App button
$bot->cmd('/webapp_reply', function () {
    $keyboard = KeyboardBuilder::reply()
        ->addWebAppButton('Open Mini App', 'https://your-webapp-url.com')
        ->addRow()
        ->addTextButton('Cancel')
        ->resize()
        ->build();

    return Bot::sendMessage('Choose an action:', [
        'reply_markup' => $keyboard
    ]);
});

$bot->run();
