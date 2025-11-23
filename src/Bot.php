<?php
/**
 * Bot.php.
 *
 *
 * @author Radya <radya@gmx.com>
 *
 * @link https://github.com/radyakaze/phptelebot
 *
 * @license GPL-3.0
 */

/**
 * Class Bot.
 */
class Bot
{
    /**
     * Bot response debug.
     * 
     * @var string
     */
    public static $debug = '';

    /**
     * Send request to telegram api server.
     *
     * @param string $action
     * @param array  $data   [optional]
     *
     * @return array|bool
     */
    public static function send($action = 'sendMessage', $data = [])
    {
        $upload = false;
        $actionUpload = ['sendPhoto', 'sendAudio', 'sendDocument', 'sendSticker', 'sendVideo', 'sendVoice', 'sendAnimation', 'sendVideoNote'];

        if (in_array($action, $actionUpload)) {
            $field = str_replace('send', '', strtolower($action));

            if (is_file($data[$field])) {
                $upload = true;
                $data[$field] = call_user_func(self::class . '::curlFile', $data[$field]);
            }
        }

        $needChatId = ['sendMessage', 'forwardMessage', 'sendPhoto', 'sendAudio', 'sendDocument', 'sendSticker', 'sendVideo', 'sendVoice', 'sendAnimation', 'sendVideoNote', 'sendLocation', 'sendVenue', 'sendContact', 'sendChatAction', 'editMessageText', 'editMessageCaption', 'editMessageReplyMarkup', 'sendGame', 'sendPoll', 'sendDice', 'sendMediaGroup', 'copyMessage', 'pinChatMessage', 'unpinChatMessage', 'sendChecklist', 'editMessageChecklist'];
        if (in_array($action, $needChatId) && !isset($data['chat_id'])) {
            $getUpdates = PHPTelebot::$getUpdates;
            if (isset($getUpdates['callback_query'])) {
                $getUpdates = $getUpdates['callback_query'];
            }
            $data['chat_id'] = $getUpdates['message']['chat']['id'];
            // Reply message
            if (!isset($data['reply_to_message_id']) && isset($data['reply']) && $data['reply'] === true) {
                $data['reply_to_message_id'] = $getUpdates['message']['message_id'];
                unset($data['reply']);
            }
        }

        if (isset($data['reply_markup']) && is_array($data['reply_markup'])) {
            $data['reply_markup'] = json_encode($data['reply_markup']);
        }

        $ch = curl_init();
        $telegram_api_server = (empty(getenv("TELEGRAM_SERVER_ENDPOINT"))) ? 'https://api.telegram.org' : getenv("TELEGRAM_SERVER_ENDPOINT");
        $options = [
            CURLOPT_URL => $telegram_api_server . '/bot'.PHPTelebot::$token.'/'.$action,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        ];

        if (is_array($data)) {
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        if ($upload !== false) {
            $options[CURLOPT_HTTPHEADER] = ['Content-Type: multipart/form-data'];
        }

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo curl_error($ch)."\n";
        }
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (PHPTelebot::$debug && $action != 'getUpdates') {
            //self::class::$debug .= 'Method: '.$action."\n";
            //self::class::$debug .= 'Data: '.str_replace("Array\n", '', print_r($data, true))."\n";
            //self::class::$debug .= 'Response: '.$result."\n";
        }

        $request_payload = json_decode($result);
        if ($request_payload === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error while parsing json');
        }
        
        if ($httpcode == 401) {
            throw new Exception('Incorect bot token');
        } else {
            if (!$request_payload->ok) {
                throw new Exception($request_payload->description, $request_payload->error_code);
            } else {
                return $result;
            }
        }
    }

    /**
     * Answer Inline.
     *
     * @param array $results
     * @param array $options
     *
     * @return string
     */
    public static function answerInlineQuery($results, $options = [])
    {
        if (!empty($options)) {
            $data = $options;
        }

        if (!isset($options['inline_query_id'])) {
            $get = PHPTelebot::$getUpdates;
            $data['inline_query_id'] = $get['inline_query']['id'];
        }

        $data['results'] = json_encode($results);

        return call_user_func(self::class . '::send', 'answerInlineQuery', $data);
    }

    /**
     * Answer Callback.
     *
     * @param string $text
     * @param array  $options [optional]
     *
     * @return string
     */
    public static function answerCallbackQuery($text, $options = [])
    {
        if (!empty($text)) $options['text'] = $text;

        if (!isset($options['callback_query_id'])) {
            $get = PHPTelebot::$getUpdates;
            $options['callback_query_id'] = $get['callback_query']['id'];
        }

        return call_user_func(self::class . '::send', 'answerCallbackQuery', $options);
    }

    /**
     * Create curl file.
     *
     * @param string $path
     *
     * @return string
     */
    private static function curlFile($path)
    {
        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create($path);
        } else {
            // Use the old style if using an older version of PHP
            return "@$path";
        }
    }

    /**
     * Get message properties.
     *
     * @return array
     */
    public static function message()
    {
        $get = PHPTelebot::$getUpdates;
        if (isset($get['message'])) {
            return $get['message'];
        } elseif (isset($get['callback_query'])) {
            return $get['callback_query'];
        } elseif (isset($get['inline_query'])) {
            return $get['inline_query'];
        } elseif (isset($get['edited_message'])) {
            return $get['edited_message'];
        } elseif (isset($get['channel_post'])) {
            return $get['channel_post'];
        } elseif (isset($get['edited_channel_post'])) {
            return $get['edited_channel_post'];
        } elseif (isset($get['my_chat_member'])) {
            return $get['my_chat_member'];
        } elseif (isset($get['chat_member'])) {
            return $get['chat_member'];
        } elseif (isset($get['chat_join_request'])) {
            return $get['chat_join_request'];
        } elseif (isset($get['pre_checkout_query'])) {
            return $get['pre_checkout_query'];
        } elseif (isset($get['shipping_query'])) {
            return $get['shipping_query'];
        } elseif (isset($get['poll'])) {
            return $get['poll'];
        } elseif (isset($get['poll_answer'])) {
            return $get['poll_answer'];
        } elseif (isset($get['message_reaction'])) {
            return $get['message_reaction'];
        } elseif (isset($get['message_reaction_count'])) {
            return $get['message_reaction_count'];
        } elseif (isset($get['chat_boost'])) {
            return $get['chat_boost'];
        } elseif (isset($get['removed_chat_boost'])) {
            return $get['removed_chat_boost'];
        } elseif (isset($get['business_connection'])) {
            return $get['business_connection'];
        } elseif (isset($get['business_message'])) {
            return $get['business_message'];
        } elseif (isset($get['edited_business_message'])) {
            return $get['edited_business_message'];
        } elseif (isset($get['deleted_business_messages'])) {
            return $get['deleted_business_messages'];
        } else {
            return [];
        }
    }

    /**
     * Mesage type.
     *
     * @return string
     */
    public static function type()
    {
        $getUpdates = PHPTelebot::$getUpdates;

        if (isset($getUpdates['message']['text'])) {
            return 'text';
        } elseif (isset($getUpdates['message']['photo'])) {
            return 'photo';
        } elseif (isset($getUpdates['message']['video'])) {
            return 'video';
        } elseif (isset($getUpdates['message']['animation'])) {
            return 'animation';
        } elseif (isset($getUpdates['message']['video_note'])) {
            return 'video_note';
        } elseif (isset($getUpdates['message']['audio'])) {
            return 'audio';
        } elseif (isset($getUpdates['message']['voice'])) {
            return 'voice';
        } elseif (isset($getUpdates['message']['document'])) {
            return 'document';
        } elseif (isset($getUpdates['message']['sticker'])) {
            return 'sticker';
        } elseif (isset($getUpdates['message']['venue'])) {
            return 'venue';
        } elseif (isset($getUpdates['message']['location'])) {
            return 'location';
        } elseif (isset($getUpdates['message']['contact'])) {
            return 'contact';
        } elseif (isset($getUpdates['message']['poll'])) {
            return 'poll';
        } elseif (isset($getUpdates['message']['dice'])) {
            return 'dice';
        } elseif (isset($getUpdates['inline_query'])) {
            return 'inline';
        } elseif (isset($getUpdates['callback_query'])) {
            return 'callback';
        } elseif (isset($getUpdates['message']['new_chat_members'])) {
            return 'new_chat_members';
        } elseif (isset($getUpdates['message']['new_chat_member'])) {
            return 'new_chat_member';
        } elseif (isset($getUpdates['message']['left_chat_member'])) {
            return 'left_chat_member';
        } elseif (isset($getUpdates['message']['new_chat_title'])) {
            return 'new_chat_title';
        } elseif (isset($getUpdates['message']['new_chat_photo'])) {
            return 'new_chat_photo';
        } elseif (isset($getUpdates['message']['delete_chat_photo'])) {
            return 'delete_chat_photo';
        } elseif (isset($getUpdates['message']['group_chat_created'])) {
            return 'group_chat_created';
        } elseif (isset($getUpdates['message']['channel_chat_created'])) {
            return 'channel_chat_created';
        } elseif (isset($getUpdates['message']['supergroup_chat_created'])) {
            return 'supergroup_chat_created';
        } elseif (isset($getUpdates['message']['migrate_to_chat_id'])) {
            return 'migrate_to_chat_id';
        } elseif (isset($getUpdates['message']['migrate_from_chat_id '])) {
            return 'migrate_from_chat_id ';
        } elseif (isset($getUpdates['message']['pinned_message'])) {
            return 'pinned_message';
        } elseif (isset($getUpdates['message']['forum_topic_created'])) {
            return 'forum_topic_created';
        } elseif (isset($getUpdates['message']['forum_topic_edited'])) {
            return 'forum_topic_edited';
        } elseif (isset($getUpdates['message']['forum_topic_closed'])) {
            return 'forum_topic_closed';
        } elseif (isset($getUpdates['message']['forum_topic_reopened'])) {
            return 'forum_topic_reopened';
        } elseif (isset($getUpdates['message']['general_forum_topic_hidden'])) {
            return 'general_forum_topic_hidden';
        } elseif (isset($getUpdates['message']['general_forum_topic_unhidden'])) {
            return 'general_forum_topic_unhidden';
        } elseif (isset($getUpdates['message']['video_chat_scheduled'])) {
            return 'video_chat_scheduled';
        } elseif (isset($getUpdates['message']['video_chat_started'])) {
            return 'video_chat_started';
        } elseif (isset($getUpdates['message']['video_chat_ended'])) {
            return 'video_chat_ended';
        } elseif (isset($getUpdates['message']['video_chat_participants_invited'])) {
            return 'video_chat_participants_invited';
        } elseif (isset($getUpdates['message']['web_app_data'])) {
            return 'web_app_data';
        } elseif (isset($getUpdates['message']['write_access_allowed'])) {
            return 'write_access_allowed';
        } elseif (isset($getUpdates['edited_message'])) {
            return 'edited';
        } elseif (isset($getUpdates['message']['game'])) {
            return 'game';
        } elseif (isset($getUpdates['channel_post'])) {
            return 'channel';
        } elseif (isset($getUpdates['edited_channel_post'])) {
            return 'edited_channel';
        } elseif (isset($getUpdates['my_chat_member'])) {
            return 'my_chat_member';
        } elseif (isset($getUpdates['chat_member'])) {
            return 'chat_member';
        } elseif (isset($getUpdates['chat_join_request'])) {
            return 'chat_join_request';
        } elseif (isset($getUpdates['pre_checkout_query'])) {
            return 'pre_checkout_query';
        } elseif (isset($getUpdates['shipping_query'])) {
            return 'shipping_query';
        } elseif (isset($getUpdates['poll'])) {
            return 'poll_update';
        } elseif (isset($getUpdates['poll_answer'])) {
            return 'poll_answer';
        } elseif (isset($getUpdates['message_reaction'])) {
            return 'message_reaction';
        } elseif (isset($getUpdates['message_reaction_count'])) {
            return 'message_reaction_count';
        } elseif (isset($getUpdates['chat_boost'])) {
            return 'chat_boost';
        } elseif (isset($getUpdates['removed_chat_boost'])) {
            return 'removed_chat_boost';
        } elseif (isset($getUpdates['business_connection'])) {
            return 'business_connection';
        } elseif (isset($getUpdates['business_message'])) {
            return 'business_message';
        } elseif (isset($getUpdates['edited_business_message'])) {
            return 'edited_business_message';
        } elseif (isset($getUpdates['deleted_business_messages'])) {
            return 'deleted_business_messages';
        } else {
            return 'unknown';
        }
    }

    /**
     * Create an action.
     *
     * @param string $name
     * @param array  $args
     *
     * @return array
     */
    public static function __callStatic($action, $args)
    {
        $param = [];
        $firstParam = [
            // Sending messages
            'sendMessage' => 'text',
            'sendPhoto' => 'photo',
            'sendVideo' => 'video',
            'sendAnimation' => 'animation',
            'sendVideoNote' => 'video_note',
            'sendAudio' => 'audio',
            'sendVoice' => 'voice',
            'sendDocument' => 'document',
            'sendSticker' => 'sticker',
            'sendVenue' => 'venue',
            'sendPoll' => 'question',
            'sendDice' => 'emoji',
            'sendChatAction' => 'action',
            // File operations
            'setWebhook' => 'url',
            'getUserProfilePhotos' => 'user_id',
            'getFile' => 'file_id',
            // Chat management
            'getChat' => 'chat_id',
            'leaveChat' => 'chat_id',
            'getChatAdministrators' => 'chat_id',
            'getChatMembersCount' => 'chat_id',
            'getChatMemberCount' => 'chat_id',
            'setChatTitle' => 'chat_id',
            'setChatDescription' => 'chat_id',
            'pinChatMessage' => 'message_id',
            'unpinChatMessage' => 'chat_id',
            'setChatPhoto' => 'photo',
            'deleteChatPhoto' => 'chat_id',
            'exportChatInviteLink' => 'chat_id',
            // Member management
            'banChatMember' => 'chat_id',
            'unbanChatMember' => 'chat_id',
            'restrictChatMember' => 'chat_id',
            'promoteChatMember' => 'chat_id',
            'setChatAdministratorCustomTitle' => 'chat_id',
            'banChatSenderChat' => 'chat_id',
            'unbanChatSenderChat' => 'chat_id',
            'getChatMember' => 'chat_id',
            'kickChatMember' => 'chat_id',
            // Games
            'sendGame' => 'game_short_name',
            'getGameHighScores' => 'user_id',
            // Stickers
            'uploadStickerFile' => 'user_id',
            'getStickerSet' => 'name',
            'deleteStickerSet' => 'name',
            // Payments
            'createInvoiceLink' => 'title',
            'answerShippingQuery' => 'shipping_query_id',
            'answerPreCheckoutQuery' => 'pre_checkout_query_id',
            // Bot commands
            'deleteMyCommands' => 'scope',
            'getMyCommands' => 'scope',
            // Business methods
            'setBusinessAccountName' => 'name',
            'setBusinessAccountUsername' => 'username',
            'setBusinessAccountBio' => 'bio',
            'getBusinessConnection' => 'business_connection_id',
            // Forum topics
            'createForumTopic' => 'chat_id',
            'editForumTopic' => 'chat_id',
            'closeForumTopic' => 'chat_id',
            'reopenForumTopic' => 'chat_id',
            'deleteForumTopic' => 'chat_id',
            'unpinAllForumTopicMessages' => 'chat_id',
            'getForumTopicIconStickers' => '',
            // Reactions
            'setMessageReaction' => 'chat_id',
            // Other methods
            'copyMessage' => 'chat_id',
            'deleteMessage' => 'chat_id',
            'stopPoll' => 'chat_id',
            'getUserChatBoosts' => 'chat_id',
            'getCustomEmojiStickers' => 'custom_emoji_ids',
            'approveChatJoinRequest' => 'chat_id',
            'declineChatJoinRequest' => 'chat_id',
        ];

        if (!isset($firstParam[$action])) {
            if (isset($args[0]) && is_array($args[0])) {
                $param = $args[0];
            }
        } else {
            if ($firstParam[$action] !== '' && isset($args[0])) {
                $param[$firstParam[$action]] = $args[0];
            }
            if (isset($args[1]) && is_array($args[1])) {
                $param = array_merge($param, $args[1]);
            }
        }

        return call_user_func(self::class . '::send', $action, $param);
    }
}
