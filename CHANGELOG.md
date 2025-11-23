# Changelog

All notable changes to PHPTelebot will be documented in this file.

## [2.0.0] - 2025-11-23

### Added - Major Telegram Bot API Update

#### New Media Methods
- `sendAnimation()` - Send GIF or H.264/MPEG-4 AVC video without sound (up to 50 MB)
- `sendVideoNote()` - Send rounded square mp4 video messages (up to 1 minute)
- `sendMediaGroup()` - Send a group of photos, videos, documents or audios as an album
- `sendPoll()` - Send native polls with up to 12 options
- `sendDice()` - Send animated emoji dice

#### New Chat Management Methods
- `setChatPhoto()` - Set chat photo
- `deleteChatPhoto()` - Delete chat photo
- `setChatTitle()` - Set chat title
- `setChatDescription()` - Set chat description
- `pinChatMessage()` - Pin a message in chat
- `unpinChatMessage()` - Unpin a specific message or the most recent one
- `unpinAllChatMessages()` - Unpin all messages in chat
- `setChatPermissions()` - Set default chat permissions
- `exportChatInviteLink()` - Export chat invite link
- `createChatInviteLink()` - Create an additional invite link
- `editChatInviteLink()` - Edit a non-primary invite link
- `revokeChatInviteLink()` - Revoke an invite link

#### New Member Management Methods
- `banChatMember()` - Ban a user from group/supergroup (replaces kickChatMember)
- `unbanChatMember()` - Unban a previously banned user
- `restrictChatMember()` - Restrict a user in a supergroup
- `promoteChatMember()` - Promote or demote a user
- `setChatAdministratorCustomTitle()` - Set custom title for administrator
- `banChatSenderChat()` - Ban a channel chat in a supergroup or channel
- `unbanChatSenderChat()` - Unban a previously banned channel chat
- `getChatMemberCount()` - Get the number of members (new name for getChatMembersCount)
- `approveChatJoinRequest()` - Approve a chat join request
- `declineChatJoinRequest()` - Decline a chat join request

#### Forum Topics Support (Bot API 6.0+)
- `createForumTopic()` - Create a topic in a forum supergroup
- `editForumTopic()` - Edit name and icon of a topic
- `closeForumTopic()` - Close an open topic
- `reopenForumTopic()` - Reopen a closed topic
- `deleteForumTopic()` - Delete a forum topic
- `unpinAllForumTopicMessages()` - Clear the list of pinned messages in a topic
- `editGeneralForumTopic()` - Edit the name of the General topic
- `closeGeneralForumTopic()` - Close the General topic
- `reopenGeneralForumTopic()` - Reopen the General topic
- `hideGeneralForumTopic()` - Hide the General topic
- `unhideGeneralForumTopic()` - Unhide the General topic
- `unpinAllGeneralForumTopicMessages()` - Clear pinned messages in General topic
- `getForumTopicIconStickers()` - Get custom emoji stickers for forum topics

#### Business Account Management (Bot API 2024-2025)
- `setBusinessAccountName()` - Change business account name
- `setBusinessAccountUsername()` - Change business account username
- `setBusinessAccountBio()` - Change business account bio
- `setBusinessAccountProfilePhoto()` - Change business account profile photo
- `removeBusinessAccountProfilePhoto()` - Remove business account profile photo
- `readBusinessMessage()` - Mark messages as read on behalf of business account
- `deleteBusinessMessages()` - Delete messages on behalf of business account
- `getBusinessConnection()` - Get information about business connection

#### Checklist Support (Bot API 9.x, 2025)
- `sendChecklist()` - Send checklists on behalf of business accounts
- `editMessageChecklist()` - Edit checklist messages

#### Payment & Stars Methods
- `getMyStarBalance()` - Get bot's current balance of Telegram Stars
- `getStarTransactions()` - Get the list of bot's Star transactions
- `refundStarPayment()` - Refund a successful Star payment
- `createInvoiceLink()` - Create a link for an invoice
- `answerShippingQuery()` - Reply to shipping queries
- `answerPreCheckoutQuery()` - Respond to pre-checkout queries

#### Sticker Management Methods
- `uploadStickerFile()` - Upload a file for use in sticker set
- `createNewStickerSet()` - Create a new sticker set
- `addStickerToSet()` - Add a new sticker to a set
- `setStickerPositionInSet()` - Move a sticker in the set
- `deleteStickerFromSet()` - Delete a sticker from a set
- `setStickerSetThumbnail()` - Set thumbnail of sticker set
- `setStickerEmojiList()` - Change emoji list of a sticker
- `setStickerKeywords()` - Change search keywords for a sticker
- `setStickerMaskPosition()` - Change mask position for a mask sticker
- `setCustomEmojiStickerSetThumbnail()` - Set thumbnail for custom emoji set
- `deleteStickerSet()` - Delete a sticker set created by the bot
- `getStickerSet()` - Get a sticker set

#### Bot Configuration Methods
- `getMyCommands()` - Get current bot command list
- `setMyCommands()` - Set bot command list
- `deleteMyCommands()` - Delete bot command list
- `getMyName()` - Get bot name
- `setMyName()` - Change bot name
- `getMyDescription()` - Get bot description
- `setMyDescription()` - Change bot description
- `getMyShortDescription()` - Get bot short description
- `setMyShortDescription()` - Change bot short description
- `getChatMenuButton()` - Get current menu button
- `setChatMenuButton()` - Change bot's menu button
- `getMyDefaultAdministratorRights()` - Get default admin rights
- `setMyDefaultAdministratorRights()` - Set default admin rights

#### Other New Methods
- `copyMessage()` - Copy messages of any kind
- `deleteMessage()` - Delete a message
- `deleteMessages()` - Delete multiple messages simultaneously
- `stopPoll()` - Stop a poll which was sent by the bot
- `setMessageReaction()` - Set bot's reaction to a message
- `getUserChatBoosts()` - Get list of boosts added to a chat by user
- `getCustomEmojiStickers()` - Get information about custom emoji stickers
- `stopMessageLiveLocation()` - Stop updating a live location message
- `editMessageLiveLocation()` - Edit live location messages
- `editMessageMedia()` - Edit animation, audio, document, photo, or video messages
- `approveSuggestedPost()` - Approve an incoming suggested post
- `declineSuggestedPost()` - Decline a suggested post

#### New Event Types in Bot::type()

**Media Types:**
- `animation` - GIF or video without sound
- `video_note` - Circular video message
- `poll` - Native poll
- `dice` - Dice, darts, basketball, or other animated emoji

**Service Messages:**
- `pinned_message` - Message was pinned
- `new_chat_members` - Multiple members added (replaces new_chat_member in some cases)
- `web_app_data` - Data from Web App
- `write_access_allowed` - User allowed bot to write messages

**Forum Events:**
- `forum_topic_created` - Forum topic created
- `forum_topic_edited` - Forum topic edited
- `forum_topic_closed` - Forum topic closed
- `forum_topic_reopened` - Forum topic reopened
- `general_forum_topic_hidden` - General topic hidden
- `general_forum_topic_unhidden` - General topic unhidden

**Video Chat Events:**
- `video_chat_scheduled` - Video chat scheduled
- `video_chat_started` - Video chat started
- `video_chat_ended` - Video chat ended
- `video_chat_participants_invited` - Participants invited to video chat

**Member Updates:**
- `chat_member` - Chat member updated (differs from my_chat_member)
- `chat_join_request` - Request to join chat

**Business Updates:**
- `business_connection` - Bot connected to business account
- `business_message` - Message from connected business account
- `edited_business_message` - Edited business message
- `deleted_business_messages` - Messages deleted from business account

**Reactions & Boosts:**
- `message_reaction` - Reaction to a message changed
- `message_reaction_count` - Reactions on message changed
- `chat_boost` - Chat boost added
- `removed_chat_boost` - Boost removed from chat

**Payment Events:**
- `shipping_query` - Incoming shipping query
- `poll_update` - Poll state updated
- `poll_answer` - User changed their answer in a non-anonymous poll

#### Bot::message() Updates
- Added support for all new update types
- Better handling of business account messages
- Support for chat join requests
- Support for reactions and boosts

### New Helpers & Developer Experience

#### Exception Handling
- **Custom Exception Hierarchy** - Professional error handling with context
  - `TelebotException` - Base exception with context array support
  - `ApiException` - Telegram API errors with detailed error descriptions
  - `ValidationException` - Parameter validation errors with field information
  - `WebhookException` - Webhook validation failures
- Enhanced error messages in `Bot::send()` with specific exception types for cURL errors, JSON parsing, HTTP 401, and API errors

#### KeyboardBuilder Helper (`PHPTelebot\Helpers\KeyboardBuilder`)
- Fluent interface for building inline and reply keyboards
- **Inline keyboard methods:**
  - `addButton()` - Callback data buttons
  - `addUrlButton()` - URL buttons
  - `addWebAppButton()` - Web App buttons
  - `addLoginButton()` - Login buttons with auth options
  - `addSwitchInlineButton()` - Switch to inline mode buttons
- **Reply keyboard methods:**
  - `addTextButton()` - Simple text buttons
  - `addRequestContactButton()` - Request user's contact
  - `addRequestLocationButton()` - Request user's location
  - `addRequestPollButton()` - Request poll creation
  - `addWebAppButton()` - Web App button in reply keyboard
- **Keyboard modifiers:**
  - `oneTime()` - Hide keyboard after use
  - `resize()` - Resize keyboard vertically
  - `selective()` - Show to specific users only
  - `inputFieldPlaceholder()` - Set placeholder text
- **Static helpers:**
  - `KeyboardBuilder::remove()` - Remove keyboard
  - `KeyboardBuilder::forceReply()` - Force user to reply

#### WebAppHandler Helper (`PHPTelebot\Helpers\WebAppHandler`)
- **Web App Data Validation** - Secure HMAC-SHA256 validation of Web App init data
- Methods:
  - `validate($initData)` - Validate Web App data authenticity
  - `parse($initData)` - Parse and decode Web App data
  - `getUser($initData)` - Extract user information
  - `getChat($initData)` - Extract chat information
  - `extractFromMessage($message)` - Static helper to extract data from messages
  - `createButton($text, $url)` - Static helper to create Web App buttons

#### MediaGroupHandler Helper (`PHPTelebot\Helpers\MediaGroupHandler`)
- **Fluent Interface for Albums** - Easy media group (album) creation and sending
- Methods:
  - `addPhoto($media, $options)` - Add photo to album
  - `addVideo($media, $options)` - Add video to album
  - `addDocument($media, $options)` - Add document to album
  - `addAudio($media, $options)` - Add audio to album
  - `build()` - Get media group array
  - `send($chatId, $options)` - Build and send in one call
  - `count()` - Get current media count
  - `clear()` - Clear all media
- Static helpers:
  - `MediaGroupHandler::create()` - Create new instance
  - `MediaGroupHandler::fromFiles($files)` - Create from file array
- Automatic validation (1-10 items per Telegram API limit)

#### WebhookValidator Helper (`PHPTelebot\Helpers\WebhookValidator`)
- **Comprehensive Webhook Security** - Multi-layer validation for webhook requests
- Validation checks:
  - HTTP method validation (POST only)
  - Content-Type validation (application/json)
  - IP address validation against Telegram server ranges
  - Secret token validation (X-Telegram-Bot-Api-Secret-Token header)
- IP ranges validated: `149.154.160.0/20` and `91.108.4.0/22`
- Methods:
  - `validate()` - Run all validations
  - `validateMethod()` - Check HTTP method
  - `validateContentType()` - Check content type
  - `validateIp()` - Check source IP
  - `validateSecretToken()` - Verify secret token

#### Examples & Documentation
- **New `/examples` directory** with production-ready bot examples:
  - `keyboard_builder_example.php` - 10 keyboard examples (inline, reply, URL, Web App, grid, etc.)
  - `webapp_bot.php` - Complete Web App integration with data validation
  - `group_management_bot.php` - Full-featured group management bot with admin commands
  - `mediagroup_example.php` - 7 media group examples (albums, mixed media, documents)
- **Comprehensive examples README** - Usage instructions, prerequisites, and code snippets
- All examples demonstrate best practices with error handling and proper validation

### Changed
- Updated framework version to 2.0.0
- Improved Bot::send() to support new media upload types (sendAnimation, sendVideoNote)
- Extended $needChatId array with new methods requiring chat_id
- Enhanced Bot::__callStatic() with comprehensive parameter mapping for 50+ new methods
- **composer.json updates:**
  - Added PSR-4 autoloading for `PHPTelebot\` namespace
  - Added `ext-json` requirement
  - Added PHPUnit to require-dev
  - Added suggest section for `psr/log` and `monolog/monolog`
  - Updated package description and keywords

### Technical Improvements
- Support for `message_thread_id` parameter (forum topics)
- Support for `business_connection_id` parameter (business accounts)
- Support for `allow_paid_broadcast` parameter
- Support for `direct_messages_topic_id` parameter
- Support for `message_effect_id` parameter
- Support for `protect_content` parameter
- Support for `show_caption_above_media` parameter

### Compatibility
- Maintains backward compatibility with existing PHPTelebot 1.x code
- All existing methods continue to work as before
- New methods are available through the same Bot:: static interface

### Documentation
- Added CHANGELOG.md to track version changes
- README.md will be updated with new method examples and event types

---

## [1.3.0] - Previous Version

All previous releases and their features.
