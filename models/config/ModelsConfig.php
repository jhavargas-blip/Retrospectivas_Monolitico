<?php

namespace app\models\config;

use app\models\database\RetrospectiveDB;
use app\models\database\RetrospectiveDBInterface;
use app\models\repositories\sprint_repository\SprintRepository;
use app\models\repositories\sprint_repository\SprintRepositoryInterface;
use app\models\repositories\retro_item_repository\RetroItemRepository;
use app\models\repositories\retro_item_repository\RetroItemRepositoryInterface;

class ModelsConfig
{
    public static function get_db(): RetrospectiveDBInterface
    {
        return new RetrospectiveDB();
    }

    public static function get_sprint_repository(): SprintRepositoryInterface
    {
        return new SprintRepository(ModelsConfig::get_db());
    }

    public static function get_retro_item_repository(): RetroItemRepositoryInterface
    {
        return new RetroItemRepository(ModelsConfig::get_db());
    }
}