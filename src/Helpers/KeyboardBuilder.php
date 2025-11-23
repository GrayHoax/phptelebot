<?php
/**
 * KeyboardBuilder.php
 *
 * Fluent interface for building Telegram keyboards
 *
 * @author PHPTelebot Contributors
 * @link https://github.com/radyakaze/phptelebot
 * @license GPL-3.0
 */

namespace PHPTelebot\Helpers;

/**
 * Keyboard Builder - Fluent interface for creating Telegram keyboards
 *
 * Supports both Inline and Reply keyboards
 *
 * @example
 * ```php
 * $keyboard = KeyboardBuilder::inline()
 *     ->addButton('Button 1', 'callback_data_1')
 *     ->addButton('Button 2', 'callback_data_2')
 *     ->addRow()
 *     ->addUrlButton('Visit Site', 'https://example.com')
 *     ->build();
 * ```
 */
class KeyboardBuilder
{
    /**
     * Keyboard type: inline or reply
     *
     * @var string
     */
    protected $type;

    /**
     * Current keyboard rows
     *
     * @var array
     */
    protected $keyboard = [];

    /**
     * Current row index
     *
     * @var int
     */
    protected $currentRow = 0;

    /**
     * One-time keyboard flag (reply keyboards only)
     *
     * @var bool
     */
    protected $oneTime = false;

    /**
     * Resize keyboard flag (reply keyboards only)
     *
     * @var bool
     */
    protected $resize = true;

    /**
     * Selective flag
     *
     * @var bool
     */
    protected $selective = false;

    /**
     * Input field placeholder (reply keyboards only)
     *
     * @var string|null
     */
    protected $inputFieldPlaceholder = null;

    /**
     * Private constructor
     *
     * @param string $type Keyboard type ('inline' or 'reply')
     */
    private function __construct($type)
    {
        $this->type = $type;
        $this->keyboard = [[]];
    }

    /**
     * Create inline keyboard builder
     *
     * @return self
     */
    public static function inline()
    {
        return new self('inline');
    }

    /**
     * Create reply keyboard builder
     *
     * @return self
     */
    public static function reply()
    {
        return new self('reply');
    }

    /**
     * Add callback button (inline keyboards only)
     *
     * @param string $text Button text
     * @param string $callbackData Callback data
     * @return self
     */
    public function addButton($text, $callbackData)
    {
        if ($this->type !== 'inline') {
            throw new \InvalidArgumentException('Callback buttons are only supported in inline keyboards');
        }

        $this->keyboard[$this->currentRow][] = [
            'text' => $text,
            'callback_data' => $callbackData
        ];

        return $this;
    }

    /**
     * Add URL button
     *
     * @param string $text Button text
     * @param string $url URL to open
     * @return self
     */
    public function addUrlButton($text, $url)
    {
        if ($this->type !== 'inline') {
            throw new \InvalidArgumentException('URL buttons are only supported in inline keyboards');
        }

        $this->keyboard[$this->currentRow][] = [
            'text' => $text,
            'url' => $url
        ];

        return $this;
    }

    /**
     * Add Web App button
     *
     * @param string $text Button text
     * @param string $url Web App URL
     * @return self
     */
    public function addWebAppButton($text, $url)
    {
        $button = ['text' => $text, 'web_app' => ['url' => $url]];

        $this->keyboard[$this->currentRow][] = $button;

        return $this;
    }

    /**
     * Add login button (inline keyboards only)
     *
     * @param string $text Button text
     * @param string $url Login URL
     * @param array $options Additional options (forward_text, bot_username, request_write_access)
     * @return self
     */
    public function addLoginButton($text, $url, array $options = [])
    {
        if ($this->type !== 'inline') {
            throw new \InvalidArgumentException('Login buttons are only supported in inline keyboards');
        }

        $loginUrl = array_merge(['url' => $url], $options);

        $this->keyboard[$this->currentRow][] = [
            'text' => $text,
            'login_url' => $loginUrl
        ];

        return $this;
    }

    /**
     * Add switch inline query button
     *
     * @param string $text Button text
     * @param string $query Query to insert
     * @param bool $currentChat Switch in current chat (true) or choose chat (false)
     * @return self
     */
    public function addSwitchInlineButton($text, $query = '', $currentChat = true)
    {
        if ($this->type !== 'inline') {
            throw new \InvalidArgumentException('Switch inline buttons are only supported in inline keyboards');
        }

        $key = $currentChat ? 'switch_inline_query_current_chat' : 'switch_inline_query';

        $this->keyboard[$this->currentRow][] = [
            'text' => $text,
            $key => $query
        ];

        return $this;
    }

    /**
     * Add text button (reply keyboards only)
     *
     * @param string $text Button text
     * @return self
     */
    public function addTextButton($text)
    {
        if ($this->type !== 'reply') {
            throw new \InvalidArgumentException('Text buttons are only supported in reply keyboards');
        }

        $this->keyboard[$this->currentRow][] = ['text' => $text];

        return $this;
    }

    /**
     * Add request contact button (reply keyboards only)
     *
     * @param string $text Button text
     * @return self
     */
    public function addRequestContactButton($text)
    {
        if ($this->type !== 'reply') {
            throw new \InvalidArgumentException('Request contact buttons are only supported in reply keyboards');
        }

        $this->keyboard[$this->currentRow][] = [
            'text' => $text,
            'request_contact' => true
        ];

        return $this;
    }

    /**
     * Add request location button (reply keyboards only)
     *
     * @param string $text Button text
     * @return self
     */
    public function addRequestLocationButton($text)
    {
        if ($this->type !== 'reply') {
            throw new \InvalidArgumentException('Request location buttons are only supported in reply keyboards');
        }

        $this->keyboard[$this->currentRow][] = [
            'text' => $text,
            'request_location' => true
        ];

        return $this;
    }

    /**
     * Add request poll button (reply keyboards only)
     *
     * @param string $text Button text
     * @param string|null $type Poll type ('quiz' or 'regular', null for any)
     * @return self
     */
    public function addRequestPollButton($text, $type = null)
    {
        if ($this->type !== 'reply') {
            throw new \InvalidArgumentException('Request poll buttons are only supported in reply keyboards');
        }

        $requestPoll = $type ? ['type' => $type] : new \stdClass(); // empty object

        $this->keyboard[$this->currentRow][] = [
            'text' => $text,
            'request_poll' => $requestPoll
        ];

        return $this;
    }

    /**
     * Start a new row
     *
     * @return self
     */
    public function addRow()
    {
        // Don't add empty rows
        if (!empty($this->keyboard[$this->currentRow])) {
            $this->currentRow++;
            $this->keyboard[$this->currentRow] = [];
        }

        return $this;
    }

    /**
     * Set one-time keyboard (reply keyboards only)
     *
     * @param bool $oneTime
     * @return self
     */
    public function oneTime($oneTime = true)
    {
        if ($this->type !== 'reply') {
            throw new \InvalidArgumentException('oneTime option is only supported in reply keyboards');
        }

        $this->oneTime = $oneTime;
        return $this;
    }

    /**
     * Set resize keyboard (reply keyboards only)
     *
     * @param bool $resize
     * @return self
     */
    public function resize($resize = true)
    {
        if ($this->type !== 'reply') {
            throw new \InvalidArgumentException('resize option is only supported in reply keyboards');
        }

        $this->resize = $resize;
        return $this;
    }

    /**
     * Set selective (show keyboard to specific users only)
     *
     * @param bool $selective
     * @return self
     */
    public function selective($selective = true)
    {
        $this->selective = $selective;
        return $this;
    }

    /**
     * Set input field placeholder (reply keyboards only)
     *
     * @param string $placeholder
     * @return self
     */
    public function inputFieldPlaceholder($placeholder)
    {
        if ($this->type !== 'reply') {
            throw new \InvalidArgumentException('inputFieldPlaceholder is only supported in reply keyboards');
        }

        $this->inputFieldPlaceholder = $placeholder;
        return $this;
    }

    /**
     * Build and return keyboard array
     *
     * @return array
     */
    public function build()
    {
        // Remove empty last row
        if (empty($this->keyboard[$this->currentRow])) {
            array_pop($this->keyboard);
        }

        if ($this->type === 'inline') {
            return [
                'inline_keyboard' => $this->keyboard
            ];
        } else {
            $keyboard = [
                'keyboard' => $this->keyboard,
                'resize_keyboard' => $this->resize,
                'one_time_keyboard' => $this->oneTime
            ];

            if ($this->selective) {
                $keyboard['selective'] = true;
            }

            if ($this->inputFieldPlaceholder !== null) {
                $keyboard['input_field_placeholder'] = $this->inputFieldPlaceholder;
            }

            return $keyboard;
        }
    }

    /**
     * Create a remove keyboard markup
     *
     * @param bool $selective Remove for specific users only
     * @return array
     */
    public static function remove($selective = false)
    {
        $markup = ['remove_keyboard' => true];

        if ($selective) {
            $markup['selective'] = true;
        }

        return $markup;
    }

    /**
     * Create a force reply markup
     *
     * @param string|null $placeholder Input field placeholder
     * @param bool $selective Force reply for specific users only
     * @return array
     */
    public static function forceReply($placeholder = null, $selective = false)
    {
        $markup = ['force_reply' => true];

        if ($placeholder !== null) {
            $markup['input_field_placeholder'] = $placeholder;
        }

        if ($selective) {
            $markup['selective'] = true;
        }

        return $markup;
    }
}
