<?php

namespace Arco\Database\Drivers;

interface DatabaseDriver {
    /**
     * Make connection with the Database
     *
     * @param string $protocol
     * @param string $host
     * @param integer $port
     * @param string $database
     * @param string $username
     * @param string $password
     * @return void
     */
    public function connect(string $protocol, string $host, int $port, string $database, string $username, string $password);

    /**
     * Return last inserted row's id
     */
    public function lastInsertId();

    /**
     * Close connection with the Database
     */
    public function close();

    /**
     * Execute statements in the Database service
     *
     * @param string $query
     * @param array $bind
     * @return mixed
     */
    public function statement(string $query, array $bind = []): mixed;
}
