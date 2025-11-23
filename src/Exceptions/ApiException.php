<?php
/**
 * ApiException.php
 *
 * Exception for Telegram API errors
 *
 * @author PHPTelebot Contributors
 * @link https://github.com/radyakaze/phptelebot
 * @license GPL-3.0
 */

namespace PHPTelebot\Exceptions;

/**
 * Exception thrown when Telegram API returns an error
 */
class ApiException extends TelebotException
{
    /**
     * API error description from Telegram
     *
     * @var string
     */
    protected $apiDescription;

    /**
     * API parameters that caused the error
     *
     * @var array
     */
    protected $apiParameters;

    /**
     * ApiException constructor
     *
     * @param string $message Exception message
     * @param int $code Telegram API error code
     * @param string $apiDescription Description from Telegram API
     * @param array $apiParameters Parameters that caused the error
     * @param \Throwable|null $previous Previous exception
     */
    public function __construct(
        $message = 'Telegram API Error',
        $code = 0,
        $apiDescription = '',
        array $apiParameters = [],
        $previous = null
    ) {
        $this->apiDescription = $apiDescription;
        $this->apiParameters = $apiParameters;

        $context = [
            'api_description' => $apiDescription,
            'parameters' => $apiParameters
        ];

        parent::__construct($message, $code, $context, $previous);
    }

    /**
     * Get Telegram API error description
     *
     * @return string
     */
    public function getApiDescription()
    {
        return $this->apiDescription;
    }

    /**
     * Get parameters that caused the error
     *
     * @return array
     */
    public function getApiParameters()
    {
        return $this->apiParameters;
    }
}
