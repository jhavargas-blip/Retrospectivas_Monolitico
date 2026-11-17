<?php

namespace app\controllers\mappers;

use app\models\entities\Sprint;

class SprintMapperController
{
    public static function to_entity(array $request): Sprint
    {
        $sprint = new Sprint();
        $sprint->set_id(empty($request['id']) ? 0 : (int) $request['id']);
        $sprint->set_nombre(trim($request['nombre'] ?? ''));
        $sprint->set_fecha_inicio($request['fecha_inicio'] ?? '');
        $sprint->set_fecha_fin($request['fecha_fin'] ?? '');

        return $sprint;
    }
}