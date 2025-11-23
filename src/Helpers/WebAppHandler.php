<?php
/**
 * WebAppHandler.php
 *
 * Helper for working with Telegram Web Apps
 *
 * @author PHPTelebot Contributors
 * @link https://github.com/radyakaze/phptelebot
 * @license GPL-3.0
 */

namespace PHPTelebot\Helpers;

use PHPTelebot\Exceptions\ValidationException;

/**
 * WebApp Handler - Validates and processes Telegram Web App data
 *
 * @see https://core.telegram.org/bots/webapps
 */
class WebAppHandler
{
    /**
     * Bot token for validation
     *
     * @var string
     */
    private $botToken;

    /**
     * WebAppHandler constructor
     *
     * @param string $botToken Bot token
     */
    public function __construct($botToken)
    {
        $this->botToken = $botToken;
    }

    /**
     * Validate Web App data
     *
     * @param string $initData Init data from Web App
     * @return bool
     * @throws ValidationException
     */
    public function validate($initData)
    {
        parse_str($initData, $data);

        if (!isset($data['hash'])) {
            throw new ValidationException('hash', 'string', null, 'Hash is missing from init data');
        }

        $hash = $data['hash'];
        unset($data['hash']);

        // Create data-check-string
        $dataCheckArr = [];
        foreach ($data as $key => $value) {
            $dataCheckArr[] = $key . '=' . $value;
        }
        sort($dataCheckArr);
        $dataCheckString = implode("\n", $dataCheckArr);

        // Compute secret key
        $secretKey = hash_hmac('sha256', $this->botToken, 'WebAppData', true);

        // Compute hash
        $computedHash = hash_hmac('sha256', $dataCheckString, $secretKey);

        if ($hash !== $computedHash) {
            throw new ValidationException(
                'hash',
                $computedHash,
                $hash,
                'Web App data validation failed: hash mismatch'
            );
        }

        return true;
    }

    /**
     * Parse Web App init data
     *
     * @param string $initData Init data from Web App
     * @param bool $validateData Validate data before parsing
     * @return array Parsed data
     * @throws ValidationException
     */
    public function parse($initData, $validateData = true)
    {
        if ($validateData) {
            $this->validate($initData);
        }

        parse_str($initData, $data);

        // Parse JSON fields
        $jsonFields = ['user', 'receiver', 'chat', 'chat_instance'];
        foreach ($jsonFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = json_decode($data[$field], true);
            }
        }

        return $data;
    }

    /**
     * Get user data from Web App init data
     *
     * @param string $initData Init data from Web App
     * @return array|null User data or null if not present
     * @throws ValidationException
     */
    public function getUser($initData)
    {
        $data = $this->parse($initData);
        return isset($data['user']) ? $data['user'] : null;
    }

    /**
     * Get chat data from Web App init data
     *
     * @param string $initData Init data from Web App
     * @return array|null Chat data or null if not present
     * @throws ValidationException
     */
    public function getChat($initData)
    {
        $data = $this->parse($initData);
        return isset($data['chat']) ? $data['chat'] : null;
    }

    /**
     * Extract data from Web App message
     *
     * @param array $message Message array from Bot::message()
     * @return string|null Web App data or null if not present
     */
    public static function extractFromMessage(array $message)
    {
        if (isset($message['web_app_data']['data'])) {
            return $message['web_app_data']['data'];
        }

        return null;
    }

    /**
     * Create Web App keyboard button
     *
     * @param string $text Button text
     * @param string $url Web App URL
     * @return array Button array
     */
    public static function createButton($text, $url)
    {
        return [
            'text' => $text,
            'web_app' => ['url' => $url]
        ];
    }
}
