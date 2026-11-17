<?php

namespace app\models\entities;

class RetroItem
{
    private int $id;
    private int $sprint_id;
    private string $categoria;
    private string $descripcion;
    private ?bool $cumplida;
    private ?string $fecha_revision;
    private string $created_at;
    private string $updated_at;
    private ?string $sprint_nombre;
    private ?string $sprint_fecha_inicio;
    private ?string $sprint_fecha_fin;

    public function __construct(
        int $id,
        int $sprint_id,
        string $categoria,
        string $descripcion,
        ?bool $cumplida = null,
        ?string $fecha_revision = null,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->sprint_id = $sprint_id;
        $this->categoria = $categoria;
        $this->descripcion = $descripcion;
        $this->cumplida = $cumplida;
        $this->fecha_revision = $fecha_revision;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->sprint_nombre = null;
        $this->sprint_fecha_inicio = null;
        $this->sprint_fecha_fin = null;
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function get_sprint_id(): int
    {
        return $this->sprint_id;
    }

    public function get_categoria(): string
    {
        return $this->categoria;
    }

    public function get_descripcion(): string
    {
        return $this->descripcion;
    }

    public function get_cumplida(): ?bool
    {
        return $this->cumplida;
    }

    public function get_fecha_revision(): ?string
    {
        return $this->fecha_revision;
    }

    public function get_created_at(): string
    {
        return $this->created_at;
    }

    public function get_updated_at(): string
    {
        return $this->updated_at;
    }

    public function get_sprint_nombre(): ?string
    {
        return $this->sprint_nombre;
    }

    public function set_sprint_nombre(string $nombre): void
    {
        $this->sprint_nombre = $nombre;
    }

    public function get_sprint_fecha_inicio(): ?string
    {
        return $this->sprint_fecha_inicio;
    }

    public function set_sprint_fecha_inicio(string $fecha): void
    {
        $this->sprint_fecha_inicio = $fecha;
    }

    public function get_sprint_fecha_fin(): ?string
    {
        return $this->sprint_fecha_fin;
    }

    public function set_sprint_fecha_fin(string $fecha): void
    {
        $this->sprint_fecha_fin = $fecha;
    }

    public function set_sprint_id(int $sprint_id): void
    {
        $this->sprint_id = $sprint_id;
    }

    public function set_categoria(string $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function set_descripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function set_cumplida(?bool $cumplida): void
    {
        $this->cumplida = $cumplida;
    }

    public function set_fecha_revision(?string $fecha_revision): void
    {
        $this->fecha_revision = $fecha_revision;
    }
}