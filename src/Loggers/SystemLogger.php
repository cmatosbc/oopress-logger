<?php

namespace Oopress\Loggers;

class SyslogLogger extends AbstractLogger
{
    public function __construct(string $logName = 'AppLogs')
    {
        openlog($logName, LOG_PID | LOG_PERROR, LOG_USER);
    }

    protected function dispatch(string $level, string $message, array $context = []): void
    {
        $syslogLevel = $this->mapLogLevel($level);
        $logMessage = $this->interpolate($message, $context);
        syslog($syslogLevel, $logMessage);
    }

    private function mapLogLevel(string $level): int
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
                return LOG_CRIT;
            case LogLevel::ERROR:
                return LOG_ERR;
            case LogLevel::WARNING:
                return LOG_WARNING;
            case LogLevel::NOTICE:
                return LOG_NOTICE;
            case LogLevel::INFO:
                return LOG_INFO;
            case LogLevel::DEBUG:
                return LOG_DEBUG;
            default:
                return LOG_INFO;
        }
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
