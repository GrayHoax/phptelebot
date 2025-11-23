<?php
/**
 * WebhookException.php
 *
 * Exception for webhook validation errors
 *
 * @author PHPTelebot Contributors
 * @link https://github.com/radyakaze/phptelebot
 * @license GPL-3.0
 */

namespace PHPTelebot\Exceptions;

/**
 * Exception thrown when webhook validation fails
 */
class WebhookException extends TelebotException
{
    /**
     * WebhookException constructor
     *
     * @param string $message Exception message
     * @param array $context Additional context
     * @param \Throwable|null $previous Previous exception
     */
    public function __construct($message = 'Webhook validation failed', array $context = [], $previous = null)
    {
        parent::__construct($message, 403, $context, $previous);
    }
}
