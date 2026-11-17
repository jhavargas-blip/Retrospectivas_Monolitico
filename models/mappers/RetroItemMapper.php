<?php

namespace app\models\mappers;

use app\models\entities\RetroItem;

class RetroItemMapper
{
    public static function to_entity(array $row_db): RetroItem
    {
        $cumplida = null;
        if (isset($row_db["cumplida"]) && $row_db["cumplida"] !== null) {
            $cumplida = (bool)$row_db["cumplida"];
        }

        $fecha_revision = $row_db["fecha_revision"] ?? null;
        if ($fecha_revision === '' || $fecha_revision === '0000-00-00') {
            $fecha_revision = null;
        }

        return new RetroItem(
            (int)$row_db["id"],
            (int)$row_db["sprint_id"],
            $row_db["categoria"],
            $row_db["descripcion"],
            $cumplida,
            $fecha_revision,
            $row_db["created_at"] ?? '',
            $row_db["updated_at"] ?? ''
        );
    }
}