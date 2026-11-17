<?php

namespace app\controllers\mappers;

use app\models\entities\RetroItem;

class RetroItemMapperController
{
    public static function to_entity(array $request): RetroItem
    {
        $cumplida = null;
        if (isset($request['cumplida']) && $request['cumplida'] !== '') {
            $cumplida = (bool)$request['cumplida'];
        }

        $fecha_revision = null;
        if (isset($request['fecha_revision']) && $request['fecha_revision'] !== '') {
            $fecha_revision = $request['fecha_revision'];
        }

        return new RetroItem(
            empty($request['id']) ? 0 : (int)$request['id'],
            (int)$request['sprint_id'],
            $request['categoria'],
            $request['descripcion'],
            $cumplida,
            $fecha_revision
        );
    }
}