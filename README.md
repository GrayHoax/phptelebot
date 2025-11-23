# PHPTelebot
Telegram bot framework written in PHP

**Version 2.0** - Now with full support for Telegram Bot API 9.2 (2025)

## Features

* Simple, easy to use.
* Support Long Polling and Webhook.
* Full support for latest Telegram Bot API (v9.2).
* Support for Forums, Business Accounts, Reactions, and more.
* 100+ Telegram Bot API methods available.
* Extensive event handling (40+ event types).

## Requirements

- [cURL](http://php.net/manual/en/book.curl.php)
- PHP 5.4+
- Telegram Bot API Token - Talk to [@BotFather](https://telegram.me/@BotFather) and generate one.

## Installation

### Using [Composer](https://getcomposer.org)

To install PHPTelebot with Composer, just add the following to your `composer.json` file:

```json
{
    "require": {
        "GrayHoax/phptelebot": "*"
    }
}
```

or by running the following command:

```shell
composer require GrayHoax/phptelebot
```

Composer installs autoloader at `./vendor/autoloader.php`. to include the library in your script, add:

```php
require_once 'vendor/autoload.php';
```

### Install from source

Download the PHP library from Github, then include `PHPTelebot.php` in your script:

```php
require_once '/path/to/phptelebot/src/PHPTelebot.php';
```


## Usage


### Creating a simple bot
```php
<?php

require_once './src/PHPTelebot.php';
$bot = new PHPTelebot('TOKEN', 'BOT_USERNAME'); // Bot username is optional, its required for handle command that contain username (/command@username) like on a group.

// Simple command
$bot->cmd('*', 'Hi, human! I am a bot.');

// Simple echo command
$bot->cmd('/echo|/say', function ($text) {
    if ($text == '') {
        $text = 'Command usage: /echo [text] or /say [text]';
    }
    return Bot::sendMessage($text);
});

// Simple whoami command
$bot->cmd('/whoami|!whoami', function () {
    // Get message properties
    $message = Bot::message();
    $name = $message['from']['first_name'];
    $userId = $message['from']['id'];
    $text = 'You are <b>'.$name.'</b> and your ID is <code>'.$userId.'</code>';
    $options = [
        'parse_mode' => 'html',
        'reply' => true
    ];

    return Bot::sendMessage($text, $options);
});

$bot->run();
```
Then run
```shell
php file.php
```

You can also see my other [sample](https://github.com/radyakaze/phptelebot/blob/master/sample.php).

*NOTE:*
- If function parameters is more than one, PHPTelebot will split text by space.
- If you don't set chat_id on options bot will send message to current chat.
- If you add option **reply => true**, bot will reply current message (Only work if you don't set custom chat_id and reply_to_mesage_id).

## Commands

Use `$bot->cmd(<command>, <function>)` to handle command.
```php
// simple answer
$bot->cmd('*', 'I am a bot');

// catch multiple commands
$bot->cmd('/start|/help', function () {
   // Do something here.
});

// call a function name
function googleSearch($search) {
   // Do something here.
}
$bot->cmd('/google', 'googleSearch');
```
Use **&#42;** to catch any command.

#### File upload
This code will send a photo to users when type command **/upload**.
```php
// Simple photo upload
$bot->cmd('/upload', function () {
    $file = '/path/to/photo.png'; // File path, file id, or url.
    return Bot::sendPhoto($file);
});
```

## Events

Use `$bot->on(<event>, <function>)` to handle all possible PHPTelebot events.

To handle inline message, just add:
```php
$bot->on('inline', function($text) {
    $results[] = [
        'type' => 'article',
        'id' => 'unique_id1',
        'title' => $text,
        'message_text' => 'Lorem ipsum dolor sit amet',
    ];
    $options = [
        'cache_time' => 3600,
    ];

    return Bot::answerInlineQuery($results, $options);
});
```
Also, you can catch multiple events:
```php
$bot->on('sticker|photo|document', function() {
  // Do something here.
 });
```

## Supported events:

### Media Events
- **&#42;** - any type of message
- **text** – text message
- **photo** – photo
- **video** – video file
- **animation** – GIF or video without sound
- **video_note** – circular video message
- **audio** – audio file
- **voice** – voice message
- **document** – document file (any kind)
- **sticker** – sticker
- **poll** – native poll
- **dice** – dice, darts, basketball, or other animated emoji

### Location & Contact
- **contact** – contact data
- **location** – location data
- **venue** – venue data

### Service Messages
- **pinned_message** – message was pinned
- **new_chat_members** – new members were added
- **new_chat_member** – new member was added
- **left_chat_member** – member was removed
- **new_chat_title** – new chat title
- **new_chat_photo** – new chat photo
- **delete_chat_photo** – chat photo was deleted
- **group_chat_created** – group has been created
- **channel_chat_created** – channel has been created
- **supergroup_chat_created** – supergroup has been created
- **migrate_to_chat_id** – group has been migrated to a supergroup
- **migrate_from_chat_id** – supergroup has been migrated from a group
- **web_app_data** – data from Web App
- **write_access_allowed** – user allowed bot to write messages

### Forum Events
- **forum_topic_created** – forum topic created
- **forum_topic_edited** – forum topic edited
- **forum_topic_closed** – forum topic closed
- **forum_topic_reopened** – forum topic reopened
- **general_forum_topic_hidden** – general topic hidden
- **general_forum_topic_unhidden** – general topic unhidden

### Video Chat Events
- **video_chat_scheduled** – video chat scheduled
- **video_chat_started** – video chat started
- **video_chat_ended** – video chat ended
- **video_chat_participants_invited** – participants invited to video chat

### Inline & Callback
- **inline** - inline message
- **callback** - callback message

### Business Account Events (2024-2025)
- **business_connection** – bot connected to business account
- **business_message** – message from connected business account
- **edited_business_message** – edited business message
- **deleted_business_messages** – messages deleted from business account

### Reactions & Boosts
- **message_reaction** – reaction to message changed
- **message_reaction_count** – reactions count changed
- **chat_boost** – chat boost added
- **removed_chat_boost** – boost removed

### Member & Chat Updates
- **my_chat_member** – bot's chat member status updated
- **chat_member** – chat member status updated
- **chat_join_request** – user requested to join chat

### Payments
- **pre_checkout_query** – pre-checkout query
- **shipping_query** – shipping query

### Other
- **edited** – edited message
- **edited_message** – edited message
- **game** - game
- **channel** - channel post
- **edited_channel** - edited channel post
- **poll_update** – poll state updated
- **poll_answer** – user changed poll answer

## Command with custom regex *(advanced)*

Create a command: */regex string number*
```php
$bot->regex('/^\/regex (.*) ([0-9])$/i', function($matches) {
    // Do something here.
});
```

## Methods

### PHPTelebot Methods
##### `cmd(<command>, <answer>)`
Handle a command.
##### `on(<event>, <answer>)`
Handles events.
##### `regex(<regex>, <answer>)`
Create custom regex for command.
##### `Bot::type()`
Return [message event](#supported-events) type.
##### `Bot::message()`
Get [message properties](https://core.telegram.org/bots/api#message).

### Telegram Methods
PHPTelebot uses standard [Telegram Bot API](https://core.telegram.org/bots/api#available-methods) method names. All methods support the same parameters as documented in the official API.

#### Sending Messages & Media
##### `Bot::getMe()` [?](https://core.telegram.org/bots/api#getme)
A simple method for testing your bot's auth token.
##### `Bot::sendMessage(<text>, <options>)` [?](https://core.telegram.org/bots/api#sendmessage)
Use this method to send text messages.
##### `Bot::forwardMessage(<options>)` [?](https://core.telegram.org/bots/api#forwardmessage)
Use this method to forward messages of any kind.
##### `Bot::copyMessage(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#copymessage)
Use this method to copy messages of any kind.
##### `Bot::sendPhoto(<file path | file id | url>, <options>)` [?](https://core.telegram.org/bots/api#sendphoto)
Use this method to send a photo.
##### `Bot::sendVideo(<file path | file id | url>, <options>)` [?](https://core.telegram.org/bots/api#sendvideo)
Use this method to send a video.
##### `Bot::sendAnimation(<file path | file id | url>, <options>)` [?](https://core.telegram.org/bots/api#sendanimation)
Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound).
##### `Bot::sendVideoNote(<file path | file id | url>, <options>)` [?](https://core.telegram.org/bots/api#sendvideonote)
Use this method to send video messages (rounded square mp4 videos).
##### `Bot::sendAudio(<file path | file id | url>, <options>)` [?](https://core.telegram.org/bots/api#sendaudio)
Use this method to send audio files.
##### `Bot::sendVoice(<file path | file id | url>, <options>)` [?](https://core.telegram.org/bots/api#sendvoice)
Use this method to send voice messages.
##### `Bot::sendDocument(<file path | file id | url>, <options>)` [?](https://core.telegram.org/bots/api#senddocument)
Use this method to send documents.
##### `Bot::sendSticker(<file path | file id | url>, <options>)` [?](https://core.telegram.org/bots/api#sendsticker)
Use this method to send stickers.
##### `Bot::sendMediaGroup(<media>, <options>)` [?](https://core.telegram.org/bots/api#sendmediagroup)
Use this method to send a group of photos, videos, documents or audios as an album.
##### `Bot::sendPoll(<question>, <options>)` [?](https://core.telegram.org/bots/api#sendpoll)
Use this method to send a native poll. Supports up to 12 options.
##### `Bot::sendDice(<emoji>, <options>)` [?](https://core.telegram.org/bots/api#senddice)
Use this method to send an animated emoji (dice, darts, basketball, etc.).
##### `Bot::sendLocation(<options>)` [?](https://core.telegram.org/bots/api#sendlocation)
Use this method to send point on the map.
##### `Bot::sendVenue(<options>)` [?](https://core.telegram.org/bots/api#sendvenue)
Use this method to send information about a venue.
##### `Bot::sendContact(<options>)` [?](https://core.telegram.org/bots/api#sendcontact)
Use this method to send phone contacts.
##### `Bot::sendChatAction(<action>, <options>)` [?](https://core.telegram.org/bots/api#sendchataction)
Use this method when you need to tell the user that something is happening on the bot's side.
##### `Bot::deleteMessage(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#deletemessage)
Use this method to delete a message.
##### `Bot::deleteMessages(<options>)` [?](https://core.telegram.org/bots/api#deletemessages)
Use this method to delete multiple messages simultaneously.
##### `Bot::getUserProfilePhotos(<user id>, <options>)` [?](https://core.telegram.org/bots/api#getuserprofilephotos)
Use this method to get a list of profile pictures for a user.
##### `Bot::getFile(<file id>)` [?](https://core.telegram.org/bots/api#getfile)
Use this method to get basic info about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size.
##### `Bot::answerInlineQuery(<array of results>, <options>)` [?](https://core.telegram.org/bots/api#answerinlinequery)
Use this method to send answers to an inline query.
##### `Bot::answerCallbackQuery(<text>, <options>)` [?](https://core.telegram.org/bots/api#answercallbackquery)
Use this method to send answers to callback queries sent from inline keyboards.
##### `Bot::getChat(<chat_id>)` [?](https://core.telegram.org/bots/api#getchat)
Use this method to get up to date information about the chat.
##### `Bot::leaveChat(<chat_id>)` [?](https://core.telegram.org/bots/api#leavechat)
Use this method for your bot to leave a group, supergroup or channel.
##### `Bot::setChatPhoto(<photo>, <options>)` [?](https://core.telegram.org/bots/api#setchatphoto)
Use this method to set a new profile photo for the chat.
##### `Bot::deleteChatPhoto(<chat_id>)` [?](https://core.telegram.org/bots/api#deletechatphoto)
Use this method to delete a chat photo.
##### `Bot::setChatTitle(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#setchattitle)
Use this method to change the title of a chat.
##### `Bot::setChatDescription(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#setchatdescription)
Use this method to change the description of a group, supergroup or channel.
##### `Bot::pinChatMessage(<message_id>, <options>)` [?](https://core.telegram.org/bots/api#pinchatmessage)
Use this method to add a message to the list of pinned messages in a chat.
##### `Bot::unpinChatMessage(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#unpinchatmessage)
Use this method to remove a message from the list of pinned messages in a chat.
##### `Bot::unpinAllChatMessages(<options>)` [?](https://core.telegram.org/bots/api#unpinallchatmessages)
Use this method to clear the list of pinned messages in a chat.
##### `Bot::getChatAdministrators(<chat_id>)` [?](https://core.telegram.org/bots/api#getchatadministrators)
Use this method to get a list of administrators in a chat.
##### `Bot::getChatMemberCount(<chat_id>)` [?](https://core.telegram.org/bots/api#getchatmembercount)
Use this method to get the number of members in a chat.
##### `Bot::getChatMember(<options>)` [?](https://core.telegram.org/bots/api#getchatmember)
Use this method to get information about a member of a chat.
##### `Bot::banChatMember(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#banchatmember)
Use this method to ban a user from a group, supergroup or channel.
##### `Bot::unbanChatMember(<options>)` [?](https://core.telegram.org/bots/api#unbanchatmember)
Use this method to unban a previously banned user in a supergroup or channel.
##### `Bot::restrictChatMember(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#restrictchatmember)
Use this method to restrict a user in a supergroup.
##### `Bot::promoteChatMember(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#promotechatmember)
Use this method to promote or demote a user in a supergroup or a channel.
##### `Bot::setChatAdministratorCustomTitle(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#setchatadministratorcustomtitle)
Use this method to set a custom title for an administrator.
##### `Bot::banChatSenderChat(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#banchatsenderchat)
Use this method to ban a channel chat in a supergroup or a channel.
##### `Bot::unbanChatSenderChat(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#unbanchatsenderchat)
Use this method to unban a previously banned channel chat.
##### `Bot::setChatPermissions(<options>)` [?](https://core.telegram.org/bots/api#setchatpermissions)
Use this method to set default chat permissions for all members.
##### `Bot::exportChatInviteLink(<chat_id>)` [?](https://core.telegram.org/bots/api#exportchatinvitelink)
Use this method to generate a new primary invite link for a chat.
##### `Bot::createChatInviteLink(<options>)` [?](https://core.telegram.org/bots/api#createchatinvitelink)
Use this method to create an additional invite link for a chat.
##### `Bot::editChatInviteLink(<options>)` [?](https://core.telegram.org/bots/api#editchatinvitelink)
Use this method to edit a non-primary invite link.
##### `Bot::revokeChatInviteLink(<options>)` [?](https://core.telegram.org/bots/api#revokechatinvitelink)
Use this method to revoke an invite link.
##### `Bot::approveChatJoinRequest(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#approvechatjoinrequest)
Use this method to approve a chat join request.
##### `Bot::declineChatJoinRequest(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#declinechatjoinrequest)
Use this method to decline a chat join request.

#### Forum Topics
##### `Bot::createForumTopic(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#createforumtopic)
Use this method to create a topic in a forum supergroup chat.
##### `Bot::editForumTopic(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#editforumtopic)
Use this method to edit name and icon of a topic.
##### `Bot::closeForumTopic(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#closeforumtopic)
Use this method to close an open topic in a forum supergroup chat.
##### `Bot::reopenForumTopic(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#reopenforumtopic)
Use this method to reopen a closed topic in a forum supergroup chat.
##### `Bot::deleteForumTopic(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#deleteforumtopic)
Use this method to delete a forum topic.
##### `Bot::unpinAllForumTopicMessages(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#unpinallforumtopicmessages)
Use this method to clear the list of pinned messages in a forum topic.
##### `Bot::editGeneralForumTopic(<options>)` [?](https://core.telegram.org/bots/api#editgeneralforumtopic)
Use this method to edit the name of the 'General' topic.
##### `Bot::closeGeneralForumTopic(<options>)` [?](https://core.telegram.org/bots/api#closegeneralforumtopic)
Use this method to close the 'General' topic in a forum supergroup chat.
##### `Bot::reopenGeneralForumTopic(<options>)` [?](https://core.telegram.org/bots/api#reopengeneralforumtopic)
Use this method to reopen the 'General' topic in a forum supergroup chat.
##### `Bot::hideGeneralForumTopic(<options>)` [?](https://core.telegram.org/bots/api#hidegeneralforumtopic)
Use this method to hide the 'General' topic in a forum supergroup chat.
##### `Bot::unhideGeneralForumTopic(<options>)` [?](https://core.telegram.org/bots/api#unhidegeneralforumtopic)
Use this method to unhide the 'General' topic in a forum supergroup chat.
##### `Bot::getForumTopicIconStickers()` [?](https://core.telegram.org/bots/api#getforumtopiconstickers)
Use this method to get custom emoji stickers for use as forum topic icons.

#### Reactions & Boosts
##### `Bot::setMessageReaction(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#setmessagereaction)
Use this method to change the chosen reactions on a message.
##### `Bot::getUserChatBoosts(<chat_id>, <options>)` [?](https://core.telegram.org/bots/api#getuserchatboosts)
Use this method to get the list of boosts added to a chat by a user.

#### Games
##### `Bot::sendGame(<game short name>, <options>)` [?](https://core.telegram.org/bots/api#sendgame)
Use this method to send a game.
##### `Bot::setGameScore(<options>)` [?](https://core.telegram.org/bots/api#setgamescore)
Use this method to set the score of the specified user in a game.
##### `Bot::getGameHighScores(<user id>, <options>)` [?](https://core.telegram.org/bots/api#getgamehighscores)
Use this method to get data for high score tables.

#### Stickers
##### `Bot::uploadStickerFile(<user_id>, <options>)` [?](https://core.telegram.org/bots/api#uploadstickerfile)
Use this method to upload a file with a sticker.
##### `Bot::createNewStickerSet(<options>)` [?](https://core.telegram.org/bots/api#createnewstickerset)
Use this method to create a new sticker set.
##### `Bot::addStickerToSet(<options>)` [?](https://core.telegram.org/bots/api#addstickertoset)
Use this method to add a new sticker to a set.
##### `Bot::deleteStickerFromSet(<options>)` [?](https://core.telegram.org/bots/api#deletestickerfromset)
Use this method to delete a sticker from a set.
##### `Bot::getStickerSet(<name>)` [?](https://core.telegram.org/bots/api#getstickerset)
Use this method to get a sticker set.
##### `Bot::deleteStickerSet(<name>)` [?](https://core.telegram.org/bots/api#deletestickerset)
Use this method to delete a sticker set.
##### `Bot::getCustomEmojiStickers(<custom_emoji_ids>)` [?](https://core.telegram.org/bots/api#getcustomemojistickers)
Use this method to get information about custom emoji stickers.

#### Payments & Stars
##### `Bot::getMyStarBalance()` [?](https://core.telegram.org/bots/api#getmystarbalance)
Use this method to get the bot's current balance of Telegram Stars.
##### `Bot::getStarTransactions(<options>)` [?](https://core.telegram.org/bots/api#getstartransactions)
Use this method to get the list of bot's Star transactions.
##### `Bot::refundStarPayment(<options>)` [?](https://core.telegram.org/bots/api#refundstarpayment)
Use this method to refund a successful Star payment.
##### `Bot::createInvoiceLink(<title>, <options>)` [?](https://core.telegram.org/bots/api#createinvoicelink)
Use this method to create a link for an invoice.
##### `Bot::answerShippingQuery(<shipping_query_id>, <options>)` [?](https://core.telegram.org/bots/api#answershippingquery)
Use this method to reply to shipping queries.
##### `Bot::answerPreCheckoutQuery(<pre_checkout_query_id>, <options>)` [?](https://core.telegram.org/bots/api#answerprecheckoutquery)
Use this method to respond to pre-checkout queries.

#### Bot Configuration
##### `Bot::getMyCommands(<options>)` [?](https://core.telegram.org/bots/api#getmycommands)
Use this method to get the current list of the bot's commands.
##### `Bot::setMyCommands(<options>)` [?](https://core.telegram.org/bots/api#setmycommands)
Use this method to change the list of the bot's commands.
##### `Bot::deleteMyCommands(<options>)` [?](https://core.telegram.org/bots/api#deletemycommands)
Use this method to delete the list of the bot's commands.

#### Business Account Management
##### `Bot::getBusinessConnection(<business_connection_id>)` [?](https://core.telegram.org/bots/api#getbusinessconnection)
Use this method to get information about the connection with a business account.
##### `Bot::setBusinessAccountName(<name>, <options>)` [?](https://core.telegram.org/bots/api#setbusinessaccountname)
Use this method to change the name of a business account.
##### `Bot::setBusinessAccountUsername(<username>, <options>)` [?](https://core.telegram.org/bots/api#setbusinessaccountusername)
Use this method to change the username of a business account.

For a complete list of all 100+ supported methods, see [CHANGELOG.md](CHANGELOG.md) or the [official Telegram Bot API documentation](https://core.telegram.org/bots/api).

## Webhook installation
Open via browser `https://api.telegram.org/bot<BOT TOKEN>/setWebhook?url=https://yourdomain.com/your_bot.php`
