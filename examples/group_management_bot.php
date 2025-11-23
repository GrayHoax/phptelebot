<?php
/**
 * Group Management Bot Example
 *
 * This example demonstrates how to create a bot for managing Telegram groups
 *
 * Features:
 * - Welcome new members
 * - Remove members
 * - Ban/unban users
 * - Pin/unpin messages
 * - Set group rules
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTelebot\Helpers\KeyboardBuilder;

$bot = new PHPTelebot('YOUR_BOT_TOKEN', 'YOUR_BOT_USERNAME');

// Store group rules (in production, use database)
$groupRules = [];

// Welcome new members
$bot->on('new_chat_members', function () {
    $message = Bot::message();

    foreach ($message['new_chat_members'] as $member) {
        $name = $member['first_name'];
        $chatTitle = $message['chat']['title'];

        Bot::sendMessage(
            "Welcome to {$chatTitle}, {$name}! 👋\n\n" .
            "Please read the rules: /rules"
        );
    }
});

// Handle member leaving
$bot->on('left_chat_member', function () {
    $message = Bot::message();
    $name = $message['left_chat_member']['first_name'];

    Bot::sendMessage("{$name} has left the group. Goodbye! 👋");
});

// Admin commands - Ban user
$bot->cmd('/ban', function ($username) {
    $message = Bot::message();

    // Check if user is admin
    if (!isAdmin($message['from']['id'], $message['chat']['id'])) {
        return Bot::sendMessage('You must be an admin to use this command.');
    }

    if (empty($username)) {
        return Bot::sendMessage('Usage: /ban @username or reply to user message with /ban');
    }

    // If replying to a message, ban that user
    if (isset($message['reply_to_message'])) {
        $userId = $message['reply_to_message']['from']['id'];

        try {
            Bot::banChatMember($message['chat']['id'], ['user_id' => $userId]);
            return Bot::sendMessage('User has been banned.');
        } catch (Exception $e) {
            return Bot::sendMessage('Failed to ban user: ' . $e->getMessage());
        }
    }

    return Bot::sendMessage('Please reply to a message from the user you want to ban.');
});

// Admin commands - Unban user
$bot->cmd('/unban', function ($userId) {
    $message = Bot::message();

    if (!isAdmin($message['from']['id'], $message['chat']['id'])) {
        return Bot::sendMessage('You must be an admin to use this command.');
    }

    if (empty($userId)) {
        return Bot::sendMessage('Usage: /unban user_id');
    }

    try {
        Bot::unbanChatMember(['chat_id' => $message['chat']['id'], 'user_id' => $userId]);
        return Bot::sendMessage('User has been unbanned.');
    } catch (Exception $e) {
        return Bot::sendMessage('Failed to unban user: ' . $e->getMessage());
    }
});

// Pin message
$bot->cmd('/pin', function () {
    $message = Bot::message();

    if (!isAdmin($message['from']['id'], $message['chat']['id'])) {
        return Bot::sendMessage('You must be an admin to use this command.');
    }

    if (isset($message['reply_to_message'])) {
        try {
            Bot::pinChatMessage($message['reply_to_message']['message_id'], [
                'chat_id' => $message['chat']['id'],
                'disable_notification' => true
            ]);
            return Bot::sendMessage('Message pinned!');
        } catch (Exception $e) {
            return Bot::sendMessage('Failed to pin message: ' . $e->getMessage());
        }
    }

    return Bot::sendMessage('Reply to a message with /pin to pin it.');
});

// Set group rules
$bot->cmd('/setrules', function ($rules) use (&$groupRules) {
    $message = Bot::message();

    if (!isAdmin($message['from']['id'], $message['chat']['id'])) {
        return Bot::sendMessage('You must be an admin to use this command.');
    }

    if (empty($rules)) {
        return Bot::sendMessage('Usage: /setrules Your rules here...');
    }

    $groupRules[$message['chat']['id']] = $rules;

    return Bot::sendMessage('Group rules have been updated!');
});

// Show group rules
$bot->cmd('/rules', function () use ($groupRules) {
    $message = Bot::message();
    $chatId = $message['chat']['id'];

    if (isset($groupRules[$chatId])) {
        return Bot::sendMessage("📜 Group Rules:\n\n" . $groupRules[$chatId]);
    }

    return Bot::sendMessage('No rules have been set for this group yet.');
});

// Admin panel
$bot->cmd('/admin', function () {
    $message = Bot::message();

    if (!isAdmin($message['from']['id'], $message['chat']['id'])) {
        return Bot::sendMessage('You must be an admin to use this command.');
    }

    $keyboard = KeyboardBuilder::inline()
        ->addButton('Ban User', 'admin_ban')
        ->addButton('Unban User', 'admin_unban')
        ->addRow()
        ->addButton('Set Rules', 'admin_setrules')
        ->addButton('Mute User', 'admin_mute')
        ->addRow()
        ->addButton('View Stats', 'admin_stats')
        ->build();

    return Bot::sendMessage('Admin Panel:', ['reply_markup' => $keyboard]);
});

// Handle admin panel callbacks
$bot->on('callback', function ($data) {
    switch ($data) {
        case 'admin_ban':
            Bot::answerCallbackQuery('To ban a user, reply to their message with /ban');
            break;
        case 'admin_stats':
            $message = Bot::message();
            try {
                $count = Bot::getChatMemberCount($message['message']['chat']['id']);
                $response = json_decode($count, true);
                $memberCount = $response['result'];
                Bot::answerCallbackQuery('');
                Bot::sendMessage("👥 Total members: {$memberCount}");
            } catch (Exception $e) {
                Bot::answerCallbackQuery('Failed to get stats');
            }
            break;
        default:
            Bot::answerCallbackQuery('Feature coming soon!');
    }
});

/**
 * Check if user is admin in the chat
 *
 * @param int $userId User ID
 * @param int|string $chatId Chat ID
 * @return bool
 */
function isAdmin($userId, $chatId)
{
    try {
        $result = Bot::getChatMember(['chat_id' => $chatId, 'user_id' => $userId]);
        $member = json_decode($result, true);

        return in_array($member['result']['status'], ['creator', 'administrator']);
    } catch (Exception $e) {
        return false;
    }
}

$bot->run();
