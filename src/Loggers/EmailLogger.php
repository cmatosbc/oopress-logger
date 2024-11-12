<?php

namespace Oopress\Loggers;

use Psr\Log\LogLevel;

class EmailLogger extends AbstractLogger
{
    private string $recipientEmail;

    public function __construct(string $recipientEmail)
    {
        $this->recipientEmail = $recipientEmail;
    }

    protected function dispatch(string $level, string $message, array $context = []): void
    {
        $subject = 'Log Alert: ' . strtoupper($level);
        $body = $this->interpolate($message, $context);
        wp_mail($this->recipientEmail, $subject, $body);
    }

    private function interpolate(string $message, array $context): string
    {
        $replacements = [];
        foreach ($context as $key => $value) {
            $replacements['{' . $key . '}'] = $value;
        }

        return strtr($message, $replacements);
    }
}
