<?php
/**
 * TelebotException.php
 *
 * Base exception for PHPTelebot
 *
 * @author PHPTelebot Contributors
 * @link https://github.com/radyakaze/phptelebot
 * @license GPL-3.0
 */

namespace PHPTelebot\Exceptions;

use Exception;

/**
 * Base exception class for all PHPTelebot exceptions
 */
class TelebotException extends Exception
{
    /**
     * Additional context data
     *
     * @var array
     */
    protected $context = [];

    /**
     * TelebotException constructor
     *
     * @param string $message Exception message
     * @param int $code Error code
     * @param array $context Additional context data
     * @param \Throwable|null $previous Previous exception
     */
    public function __construct($message = '', $code = 0, array $context = [], $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get exception context
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Get formatted exception message with context
     *
     * @return string
     */
    public function getDetailedMessage()
    {
        $message = $this->getMessage();

        if (!empty($this->context)) {
            $message .= ' | Context: ' . json_encode($this->context);
        }

        return $message;
    }
}
