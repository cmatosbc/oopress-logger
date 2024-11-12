<?php
    
namespace Oopress\Loggers;
    
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
  
abstract class AbstractLogger implements LoggerInterface
{
    protected const LOG_LEVELS = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    ];
   
    protected function log(string $level, string $message, array $context = []): void
    {
        if (!in_array($level, self::LOG_LEVELS)) {
            throw new \InvalidArgumentException("Invalid log level: $level");
        }

        $this->dispatch($level, $message, $context);
    }
    
    abstract protected function dispatch(string $level, string $message, array $context = []): void;
}
