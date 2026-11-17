<?php

namespace app\models\repositories\retro_item_repository;

use app\models\database\RetrospectiveDBInterface;
use app\models\entities\RetroItem;
use app\models\mappers\RetroItemMapper;
use Exception;

class RetroItemRepository implements RetroItemRepositoryInterface
{
    private RetrospectiveDBInterface $db;

    public function __construct(RetrospectiveDBInterface $db)
    {
        $this->db = $db;
    }

    public function get_items_by_sprint(int $sprint_id): array
    {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = $sprint_id ORDER BY ";
        $sql .= " CASE categoria ";
        $sql .= "   WHEN 'logro' THEN 1 ";
        $sql .= "   WHEN 'impedimento' THEN 2 ";
        $sql .= "   WHEN 'accion' THEN 3 ";
        $sql .= "   WHEN 'comentario' THEN 4 ";
        $sql .= "   ELSE 5 ";
        $sql .= " END, created_at";

        $items_db = $this->db->query_select($sql);
        return array_map(
            fn($row): RetroItem => RetroItemMapper::to_entity($row),
            $items_db
        );
    }

    public function get_items_by_category(int $sprint_id, string $categoria): array
    {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = $sprint_id AND categoria = '$categoria' ORDER BY created_at";
        $items_db = $this->db->query_select($sql);
        return array_map(
            fn($row): RetroItem => RetroItemMapper::to_entity($row),
            $items_db
        );
    }

    public function find_item_by_id(int $id): RetroItem
    {
        $sql = "SELECT * FROM retro_items WHERE id = $id";
        $items = $this->db->query_select($sql);
        if (count($items) == 0) {
            throw new Exception("Item no encontrado", 2);
        }
        return RetroItemMapper::to_entity($items[0]);
    }

    public function save_new_item(RetroItem $item): int
    {
        $cumplida = $item->get_cumplida() !== null ? ($item->get_cumplida() ? 1 : 0) : 'NULL';
        $fecha_revision = $item->get_fecha_revision() ? "'" . $item->get_fecha_revision() . "'" : 'NULL';

        $sql = "INSERT INTO retro_items (sprint_id, categoria, descripcion, cumplida, fecha_revision) VALUES ";
        $sql .= "(" . $item->get_sprint_id() . ", '" . $item->get_categoria() . "', '" . $item->get_descripcion() . "', $cumplida, $fecha_revision)";
        $this->db->query($sql);
        return $this->db->get_last_insert_id();
    }

    public function update_item(RetroItem $item): bool
    {
        $cumplida = $item->get_cumplida() !== null ? ($item->get_cumplida() ? 1 : 0) : 'NULL';
        $fecha_revision = $item->get_fecha_revision() ? "'" . $item->get_fecha_revision() . "'" : 'NULL';

        $sql = "UPDATE retro_items SET ";
        $sql .= " sprint_id=" . $item->get_sprint_id() . ", ";
        $sql .= " categoria='" . $item->get_categoria() . "', ";
        $sql .= " descripcion='" . $item->get_descripcion() . "', ";
        $sql .= " cumplida=$cumplida, ";
        $sql .= " fecha_revision=$fecha_revision ";
        $sql .= " WHERE id=" . $item->get_id();
        return $this->db->query($sql);
    }

    public function delete_item(int $id): bool
    {
        $sql = "DELETE FROM retro_items WHERE id=$id";
        return $this->db->query($sql);
    }

    public function get_acciones_from_previous_sprints(int $current_sprint_id): array
    {
        $sql = "SELECT ri.*, s.nombre as sprint_nombre FROM retro_items ri ";
        $sql .= " JOIN sprints s ON ri.sprint_id = s.id ";
        $sql .= " WHERE ri.categoria = 'accion' ";
        $sql .= " AND s.id < $current_sprint_id ";
        $sql .= " ORDER BY s.id DESC LIMIT 100";

        $items_db = $this->db->query_select($sql);
        $items = array_map(
            fn($row): RetroItem => RetroItemMapper::to_entity($row),
            $items_db
        );

        // Add sprint_nombre to each item for display
        foreach ($items_db as $index => $row) {
            if (isset($row['sprint_nombre'])) {
                $items[$index]->set_sprint_nombre($row['sprint_nombre']);
            }
        }

        return $items;
    }

    public function count_by_category(int $sprint_id): array
    {
        $sql = "SELECT categoria, COUNT(*) as total FROM retro_items WHERE sprint_id = $sprint_id GROUP BY categoria";
        return $this->db->query_select($sql);
    }
}