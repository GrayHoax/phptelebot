<?php
/**
 * WebhookValidator.php
 *
 * Validates Telegram webhook requests
 *
 * @author PHPTelebot Contributors
 * @link https://github.com/radyakaze/phptelebot
 * @license GPL-3.0
 */

namespace PHPTelebot\Helpers;

use PHPTelebot\Exceptions\WebhookException;

/**
 * Webhook Validator - Validates incoming webhook requests from Telegram
 *
 * Provides security by verifying that requests actually come from Telegram
 *
 * @see https://core.telegram.org/bots/webhooks#testing-your-bot-with-updates
 */
class WebhookValidator
{
    /**
     * Bot token (used for secret token validation)
     *
     * @var string
     */
    private $botToken;

    /**
     * Secret token for validation (optional, recommended)
     *
     * @var string|null
     */
    private $secretToken;

    /**
     * Telegram server IP ranges (for IP validation)
     *
     * @var array
     */
    private static $telegramIpRanges = [
        '149.154.160.0/20',
        '91.108.4.0/22',
    ];

    /**
     * WebhookValidator constructor
     *
     * @param string $botToken Bot token
     * @param string|null $secretToken Secret token (set with setWebhook)
     */
    public function __construct($botToken, $secretToken = null)
    {
        $this->botToken = $botToken;
        $this->secretToken = $secretToken;
    }

    /**
     * Validate webhook request
     *
     * @param array $options Validation options
     *                      - validateIp: Check if request comes from Telegram IP (default: true)
     *                      - validateSecret: Check secret token header (default: true if secret token is set)
     *                      - validateMethod: Check HTTP method is POST (default: true)
     *                      - validateContentType: Check content type is application/json (default: true)
     * @return bool True if validation passes
     * @throws WebhookException If validation fails
     */
    public function validate(array $options = [])
    {
        $defaults = [
            'validateIp' => true,
            'validateSecret' => ($this->secretToken !== null),
            'validateMethod' => true,
            'validateContentType' => true,
        ];

        $options = array_merge($defaults, $options);

        // Validate HTTP method
        if ($options['validateMethod']) {
            $this->validateMethod();
        }

        // Validate content type
        if ($options['validateContentType']) {
            $this->validateContentType();
        }

        // Validate IP address
        if ($options['validateIp']) {
            $this->validateIp();
        }

        // Validate secret token
        if ($options['validateSecret']) {
            $this->validateSecretToken();
        }

        return true;
    }

    /**
     * Validate HTTP method
     *
     * @return bool
     * @throws WebhookException
     */
    private function validateMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new WebhookException(
                'Invalid request method',
                ['expected' => 'POST', 'actual' => $_SERVER['REQUEST_METHOD']]
            );
        }

        return true;
    }

    /**
     * Validate content type
     *
     * @return bool
     * @throws WebhookException
     */
    private function validateContentType()
    {
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

        if (strpos($contentType, 'application/json') === false) {
            throw new WebhookException(
                'Invalid content type',
                ['expected' => 'application/json', 'actual' => $contentType]
            );
        }

        return true;
    }

    /**
     * Validate IP address
     *
     * @return bool
     * @throws WebhookException
     */
    private function validateIp()
    {
        $clientIp = $this->getClientIp();

        foreach (self::$telegramIpRanges as $range) {
            if ($this->ipInRange($clientIp, $range)) {
                return true;
            }
        }

        throw new WebhookException(
            'Request from unauthorized IP address',
            ['ip' => $clientIp, 'allowed_ranges' => self::$telegramIpRanges]
        );
    }

    /**
     * Validate secret token from header
     *
     * @return bool
     * @throws WebhookException
     */
    private function validateSecretToken()
    {
        $headerToken = isset($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'])
            ? $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN']
            : null;

        if ($headerToken === null) {
            throw new WebhookException(
                'Secret token header is missing',
                ['header' => 'X-Telegram-Bot-Api-Secret-Token']
            );
        }

        if ($headerToken !== $this->secretToken) {
            throw new WebhookException(
                'Invalid secret token',
                ['provided_token_length' => strlen($headerToken)]
            );
        }

        return true;
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    private function getClientIp()
    {
        // Check various headers for the real IP
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',  // Proxy
            'HTTP_X_REAL_IP',        // Nginx proxy
            'REMOTE_ADDR',           // Direct connection
        ];

        foreach ($headers as $header) {
            if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];

                // HTTP_X_FORWARDED_FOR can contain multiple IPs
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }

                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }

    /**
     * Check if IP is in range
     *
     * @param string $ip IP address to check
     * @param string $range IP range in CIDR notation
     * @return bool
     */
    private function ipInRange($ip, $range)
    {
        list($subnet, $mask) = explode('/', $range);

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = -1 << (32 - (int)$mask);

        return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
    }

    /**
     * Set Telegram IP ranges (for testing or custom configuration)
     *
     * @param array $ranges Array of IP ranges in CIDR notation
     * @return void
     */
    public static function setTelegramIpRanges(array $ranges)
    {
        self::$telegramIpRanges = $ranges;
    }

    /**
     * Get current Telegram IP ranges
     *
     * @return array
     */
    public static function getTelegramIpRanges()
    {
        return self::$telegramIpRanges;
    }
}
