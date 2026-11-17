<?php

namespace app\models\entities;

class Sprint
{
    private int $id;
    private string $nombre;
    private string $fecha_inicio;  // 'Y-m-d'   (columna DATE)
    private string $fecha_fin;  // 'Y-m-d'   (columna DATE)
    private ?string $created_at;  // 'Y-m-d H:i:s' (TIMESTAMP, lo llena MySQL)
    private ?string $updated_at;   // 'Y-m-d H:i:s' (TIMESTAMP, lo llena MySQL)

    public function get_id(): int
    {
        return $this->id;
    }
    public function set_id(int $id): void
    {
        $this->id = $id;
    }

    public function get_nombre(): string
    {
        return $this->nombre;
    }
    public function set_nombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function get_fecha_inicio(): string
    {
        return $this->fecha_inicio;
    }
    public function set_fecha_inicio(string $fecha_inicio): void
    {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function get_fecha_fin(): string
    {
        return $this->fecha_fin;
    }
    public function set_fecha_fin(string $fecha_fin): void
    {
        $this->fecha_fin = $fecha_fin;
    }

    public function get_created_at(): ?string
    {
        return $this->created_at;
    }
    public function set_created_at(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function get_updated_at(): ?string
    {
        return $this->updated_at;
    }
    public function set_updated_at(?string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}