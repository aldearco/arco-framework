<?php

namespace Arco\Database\Drivers;

use PDO;

class PDODriver implements DatabaseDriver {
    /**
     * Native PHP PDO pointer
     *
     * @var PDO|null
     */
    protected ?PDO $pdo;

    /**
     * @inheritDoc
     */
    public function connect(
        string $protocol,
        string $host,
        int $port,
        string $database,
        string $username,
        string $password
    ) {
        $dsn = "$protocol:host=$host;port=$port;dbname=$database;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * @inheritDoc
     */
    public function close() {
        $this->pdo = null;
    }

    /**
     * @inheritDoc
     */
    public function statement(string $query, array $bind = []): mixed {
        $statement = $this->pdo->prepare($query);
        $statement->execute($bind);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
