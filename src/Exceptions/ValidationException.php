<?php
/**
 * ValidationException.php
 *
 * Exception for validation errors
 *
 * @author PHPTelebot Contributors
 * @link https://github.com/radyakaze/phptelebot
 * @license GPL-3.0
 */

namespace PHPTelebot\Exceptions;

/**
 * Exception thrown when parameter validation fails
 */
class ValidationException extends TelebotException
{
    /**
     * Field that failed validation
     *
     * @var string
     */
    protected $field;

    /**
     * Expected value or type
     *
     * @var mixed
     */
    protected $expected;

    /**
     * Actual value received
     *
     * @var mixed
     */
    protected $actual;

    /**
     * ValidationException constructor
     *
     * @param string $field Field name that failed validation
     * @param mixed $expected Expected value or type
     * @param mixed $actual Actual value received
     * @param string $message Custom error message
     * @param \Throwable|null $previous Previous exception
     */
    public function __construct($field, $expected = null, $actual = null, $message = '', $previous = null)
    {
        $this->field = $field;
        $this->expected = $expected;
        $this->actual = $actual;

        if (empty($message)) {
            $message = "Validation failed for field '{$field}'";
            if ($expected !== null) {
                $message .= ". Expected: " . $this->formatValue($expected);
            }
            if ($actual !== null) {
                $message .= ", Got: " . $this->formatValue($actual);
            }
        }

        $context = [
            'field' => $field,
            'expected' => $expected,
            'actual' => $actual
        ];

        parent::__construct($message, 0, $context, $previous);
    }

    /**
     * Format value for display
     *
     * @param mixed $value
     * @return string
     */
    private function formatValue($value)
    {
        if (is_array($value)) {
            return 'array';
        }
        if (is_object($value)) {
            return get_class($value);
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_null($value)) {
            return 'null';
        }
        return (string)$value;
    }

    /**
     * Get field name
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get expected value
     *
     * @return mixed
     */
    public function getExpected()
    {
        return $this->expected;
    }

    /**
     * Get actual value
     *
     * @return mixed
     */
    public function getActual()
    {
        return $this->actual;
    }
}
