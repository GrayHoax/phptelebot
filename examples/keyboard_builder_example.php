<?php
/**
 * Keyboard Builder Example
 *
 * This example demonstrates how to use KeyboardBuilder for creating various types of keyboards
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTelebot\Helpers\KeyboardBuilder;

$bot = new PHPTelebot('YOUR_BOT_TOKEN');

// Example 1: Simple inline keyboard
$bot->cmd('/inline', function () {
    $keyboard = KeyboardBuilder::inline()
        ->addButton('Button 1', 'callback_1')
        ->addButton('Button 2', 'callback_2')
        ->addRow()
        ->addButton('Button 3', 'callback_3')
        ->build();

    return Bot::sendMessage('Choose an option:', ['reply_markup' => $keyboard]);
});

// Example 2: URL buttons
$bot->cmd('/urls', function () {
    $keyboard = KeyboardBuilder::inline()
        ->addUrlButton('Visit Google', 'https://google.com')
        ->addUrlButton('Visit GitHub', 'https://github.com')
        ->addRow()
        ->addUrlButton('Telegram', 'https://telegram.org')
        ->build();

    return Bot::sendMessage('Useful links:', ['reply_markup' => $keyboard]);
});

// Example 3: Reply keyboard with contact and location requests
$bot->cmd('/reply', function () {
    $keyboard = KeyboardBuilder::reply()
        ->addRequestContactButton('📱 Share Contact')
        ->addRequestLocationButton('📍 Share Location')
        ->addRow()
        ->addRequestPollButton('📊 Create Poll')
        ->addRow()
        ->addTextButton('Cancel')
        ->oneTime()
        ->resize()
        ->build();

    return Bot::sendMessage('Please share your information:', ['reply_markup' => $keyboard]);
});

// Example 4: Web App button
$bot->cmd('/webapp', function () {
    $keyboard = KeyboardBuilder::inline()
        ->addWebAppButton('Open App', 'https://example.com/webapp')
        ->build();

    return Bot::sendMessage('Open the Web App:', ['reply_markup' => $keyboard]);
});

// Example 5: Switch inline query buttons
$bot->cmd('/switch', function () {
    $keyboard = KeyboardBuilder::inline()
        ->addSwitchInlineButton('Search in current chat', 'search query', true)
        ->addRow()
        ->addSwitchInlineButton('Search in another chat', 'search query', false)
        ->build();

    return Bot::sendMessage('Try inline mode:', ['reply_markup' => $keyboard]);
});

// Example 6: Mixed keyboard
$bot->cmd('/mixed', function () {
    $keyboard = KeyboardBuilder::inline()
        ->addButton('Callback', 'data_1')
        ->addUrlButton('URL', 'https://example.com')
        ->addRow()
        ->addWebAppButton('Web App', 'https://example.com/app')
        ->addSwitchInlineButton('Inline', '', true)
        ->build();

    return Bot::sendMessage('Mixed keyboard:', ['reply_markup' => $keyboard]);
});

// Example 7: Remove keyboard
$bot->cmd('/remove', function () {
    $keyboard = KeyboardBuilder::remove();

    return Bot::sendMessage('Keyboard removed!', ['reply_markup' => $keyboard]);
});

// Example 8: Force reply
$bot->cmd('/force', function () {
    $keyboard = KeyboardBuilder::forceReply('Type your message here...');

    return Bot::sendMessage('Please reply to this message:', ['reply_markup' => $keyboard]);
});

// Example 9: Login button
$bot->cmd('/login', function () {
    $keyboard = KeyboardBuilder::inline()
        ->addLoginButton('Login', 'https://example.com/auth', [
            'forward_text' => 'Login to Example',
            'request_write_access' => true
        ])
        ->build();

    return Bot::sendMessage('Click to login:', ['reply_markup' => $keyboard]);
});

// Example 10: Grid keyboard
$bot->cmd('/grid', function () {
    $keyboard = KeyboardBuilder::inline()
        ->addButton('1', 'num_1')
        ->addButton('2', 'num_2')
        ->addButton('3', 'num_3')
        ->addRow()
        ->addButton('4', 'num_4')
        ->addButton('5', 'num_5')
        ->addButton('6', 'num_6')
        ->addRow()
        ->addButton('7', 'num_7')
        ->addButton('8', 'num_8')
        ->addButton('9', 'num_9')
        ->addRow()
        ->addButton('0', 'num_0')
        ->build();

    return Bot::sendMessage('Number pad:', ['reply_markup' => $keyboard]);
});

// Handle callbacks
$bot->on('callback', function ($data) {
    Bot::answerCallbackQuery('You clicked: ' . $data);

    if (strpos($data, 'num_') === 0) {
        $number = str_replace('num_', '', $data);
        Bot::sendMessage('You selected number: ' . $number);
    }
});

$bot->run();
