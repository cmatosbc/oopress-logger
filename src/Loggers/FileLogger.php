<?php

namespace Oopress\Loggers;

/**
 * FileLogger class for writing logs to a specified file.
 */
class FileLogger extends AbstractLogger
{
    /**
     * The path to the log file.
     *
     * @var string
     */
    private string $filePath;

    /**
     * Constructs a new FileLogger instance.
     *
     * @param string $filePath The path to the log file.
     */
    public function __construct(?string $filePath = null)
    {
        if (is_null($filePath) && defined('WP_CONTENT_DIR')) {
            $this->filePath = WP_CONTENT_DIR . '/cache/';
        }

        if ($filePath) {
            $this->filePath = $filePath;
        }

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, '');
        }
    }

    /**
     * Dispatches a log message to the file.
     *
     * @param string $level The log level (e.g., "debug", "info", "warning", "error").
     * @param string $message The log message.
     * @param array $context An array of additional context information.
     */
    protected function dispatch(string $level, string $message, array $context = []): void
    {
        $logMessage = '[' . strtoupper($level) . ']: ' . $message . PHP_EOL;
        file_put_contents($this->filePath, $logMessage, FILE_APPEND);
    }
}
