<?php

namespace app\models\config;

use app\models\database\RetrospectiveDB;
use app\models\database\RetrospectiveDBInterface;
use app\models\repositories\sprint_repository\SprintRepository;
use app\models\repositories\sprint_repository\SprintRepositoryInterface;

class ModelsConfig {
    public static function get_db(): RetrospectiveDBInterface{
        return new RetrospectiveDB();
    }

    public static function get_sprint_repository(): SprintRepositoryInterface{
        return new SprintRepository(self::get_db());
    }
}