<?php

use Psr\Log\LoggerInterface;

function withLoggedQuery(LoggerInterface $logger)
{
    return function (array $queryArgs, string|bool $logTitle = false) use ($logger) {
        $query = new \WP_Query($queryArgs);
        $query->prepare();
        $sql = $query->request ?? 'Raw SQL query is empty.';
        $params = $query->get_params();

        $title = $logTitle ? $logTitle : 'Executing WP_Query:';

        $logger->info($title, [
            'sql' => $sql,
            'params' => $params,
        ]);

        return $query;
    };
}
