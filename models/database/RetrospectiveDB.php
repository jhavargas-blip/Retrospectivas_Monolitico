<?php

namespace app\models\database;

use mysqli;

class RetrospectiveDB implements RetrospectiveDBInterface
{
    private string $host_db = "localhost";
    private string $user_db = "root";
    private string $pwd_db = "";
    private string $name_db = "registro_retro_db";
    private int $port_db = 3306;
    private $conex = null;

    public function __construct()
    {
        $this->conex = new mysqli(
            $this->host_db,
            $this->user_db,
            $this->pwd_db,
            $this->name_db,
            $this->port_db
        );

        if ($this->conex->connect_error) {
            die("Error de conexión: " . $this->conex->connect_error);
        }
        $this->conex->set_charset("utf8");
    }

    public function query_select(string $sql): array
    {
        $result = $this->conex->query($sql);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                array_push($rows, $row);
            }
            $result->free();
        }
        return $rows;
    }

    public function query(string $sql): bool
    {
        $result = $this->conex->query($sql);
        return $result;
    }

    public function get_last_insert_id(): int
    {
        return $this->conex->insert_id;
    }

    public function close(): void
    {
        if ($this->conex) {
            $this->conex->close();
        }
    }
}