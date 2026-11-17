<?php

namespace app\controllers\config;

use app\controllers\retro_items\RetroItemController;
use app\controllers\retro_items\RetroItemControllerInterface;
use app\models\config\ModelsConfig;

class ControllersConfig
{
    public static function get_sprint_controller(): SprintControllerInterface
    {
        return new SprintController(ModelsConfig::get_sprint_repository());
    }

    public static function get_retro_item_controller(): RetroItemControllerInterface
    {
        return new RetroItemController(ModelsConfig::get_retro_item_repository());
    }
}