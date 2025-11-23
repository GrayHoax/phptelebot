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

### Changed
- Updated framework version to 2.0.0
- Improved Bot::send() to support new media upload types (sendAnimation, sendVideoNote)
- Extended $needChatId array with new methods requiring chat_id
- Enhanced Bot::__callStatic() with comprehensive parameter mapping for 50+ new methods

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
