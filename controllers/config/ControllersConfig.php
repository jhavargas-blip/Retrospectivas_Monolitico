<?php

namespace app\controllers\config;

use app\controllers\sprints\SprintController;
use app\controllers\sprints\SprintControllerInterface;
use app\models\config\ModelsConfig;

class ControllersConfig
{
    public static function get_sprint_controller(): SprintControllerInterface
    {
        return new SprintController(ModelsConfig::get_sprint_repository());
    }
}