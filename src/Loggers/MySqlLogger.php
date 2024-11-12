<?php

namespace Oopress\Loggers;

/**
 * DatabaseLogger class for writing logs to a database table.
 */
class DatabaseLogger extends AbstractLogger
{
    /**
     * The WordPress database object.
     *
     * @var \wpdb
     */
    private \wpdb $wpdb;

    /**
     * The name of the database table for storing logs.
     *
     * @var string
     */
    private string $table;

    /**
     * Constructs a new DatabaseLogger instance.
     *
     * @param \wpdb $wpdb The WordPress database object.
     * @param string $table The name of the database table for storing logs.
     */
    public function __construct(\wpdb $wpdb = null, string $table = 'log_tracker')
    {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->table = $table;

        if (!get_option($this->table . '_db_created')) {
            $this->createTable();
        }
    }

    /**
     * Creates the database table for storing logs if it doesn't exist.
     */
    private function createTable(): void
    {
        $charsetCollate = $this->wpdb->get_charset_collate();
        $newTable = $this->wpdb->prefix . $this->table;

        $sql = "CREATE TABLE $newTable (
            id INT AUTO_INCREMENT PRIMARY KEY,
            level VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            context TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charsetCollate;";

        $this->wpdb->query($sql);

        update_option($this->table . '_db_created', true);
    }

    /**
     * Dispatches a log message to the database.
     *
     * @param string $level The log level (e.g., "debug", "info", "warning", "error").
     * @param string $message The log message.
     * @param array $context An array of additional context information.
     */
    protected function dispatch(string $level, string $message, array $context = []): void
    {
        $data = [
            'level' => $level,
            'message' => $message,
            'context' => json_encode($context),
        ];

        $this->wpdb->insert($this->wpdb->prefix . $this->table, $data);
    }
}
