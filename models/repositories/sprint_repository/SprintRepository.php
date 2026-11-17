<?php

namespace app\models\repositories\sprint_repository;

use app\models\database\RetrospectiveDBInterface;
use app\models\entities\Sprint;
use app\models\mappers\SprintMapper;
use Exception;

class SprintRepository implements SprintRepositoryInterface
{
    private RetrospectiveDBInterface $sprint_db;

    public function __construct(RetrospectiveDBInterface $sprint_db)
    {
        $this->sprint_db = $sprint_db;
    }

    public function get_sprints(): array
    {
        $rows = $this->sprint_db->query_select(
            "SELECT * FROM sprints ORDER BY fecha_inicio DESC, id DESC"
        );

        return array_map(fn($row): Sprint => SprintMapper::to_entity($row), $rows);
    }

    public function find_sprint_by_id(int $id): Sprint
    {
        $rows = $this->sprint_db->query_select("SELECT * FROM sprints WHERE id = $id");

        if (count($rows) === 0) {
            throw new Exception("El Sprint no ha sido encontrado", 2);
        }

        return SprintMapper::to_entity($rows[0]);
    }

    public function save_new_sprint(Sprint $sprint): int|false
    {
        $sql = "INSERT INTO sprints (nombre, fecha_inicio, fecha_fin) VALUES ";
        $sql .= "('" . $sprint->get_nombre() . "', ";
        $sql .= "'" . $sprint->get_fecha_inicio() . "', ";
        $sql .= "'" . $sprint->get_fecha_fin() . "')";

        if (!$this->sprint_db->query($sql)) {
            return false;
        }

        return $this->sprint_db->get_last_insert_id();
    }

    public function update_sprint(Sprint $sprint): bool
    {
        $sql = "UPDATE sprints SET ";
        $sql .= "nombre = '" . $sprint->get_nombre() . "', ";
        $sql .= "fecha_inicio = '" . $sprint->get_fecha_inicio() . "', ";
        $sql .= "fecha_fin = '" . $sprint->get_fecha_fin() . "' ";
        $sql .= "WHERE id = " . $sprint->get_id();

        return $this->sprint_db->query($sql);
    }

    public function delete_sprint(int $id): bool
    {
        return $this->sprint_db->query("DELETE FROM sprints WHERE id = $id");
    }
}