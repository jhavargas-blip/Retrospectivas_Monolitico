<?php

namespace app\models\mappers;

use app\models\entities\Sprint;

class SprintMapper {
    public static function to_entity(array $row_db): Sprint {
        $sprint = new Sprint();
        $sprint->set_id((int) ($row_db['id'] ?? 0));
        $sprint->set_nombre((string) ($row_db['nombre'] ?? ''));
        $sprint->set_fecha_inicio((string) ($row_db['fecha_inicio'] ?? ''));
        $sprint->set_fecha_fin((string) ($row_db['fecha_fin'] ?? ''));
        $sprint->set_created_at(isset($row_db['created_at']) ? (string) ($row_db['created_at']) : null);
        $sprint->set_updated_at(isset($row_db['updated_at']) ? (string) ($row_db['updated_at']) : null);

        return $sprint;
    }
}