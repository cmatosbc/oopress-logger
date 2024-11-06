<?php

use Psr\Log\LoggerInterface;

function withLoggedQuery(LoggerInterface $logger)
{
    return function (array $queryArgs) use ($logger) {
        $query = new \WP_Query($queryArgs);
        $query->prepare();
        $sql = $query->request;
        $params = $query->get_params();

        $logger->info("Executing WP_Query:", [
            'sql' => $sql,
            'params' => $params,
        ]);

        return $query;
    };
}
