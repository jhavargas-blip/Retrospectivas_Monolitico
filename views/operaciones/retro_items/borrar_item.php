<?php
require "../../models/database/RetrospectiveDBInterface.php";
require "../../models/database/RetrospectiveDB.php";
require "../../models/entities/RetroItem.php";
require "../../models/mappers/RetroItemMapper.php";
require "../../models/repositories/retro_item_repository/RetroItemRepositoryInterface.php";
require "../../models/repositories/retro_item_repository/RetroItemRepository.php";
require "../../models/config/ModelsConfig.php";

require "../../controllers/mappers/RetroItemMapperController.php";
require "../../controllers/retro_items/RetroItemControllerInterface.php";
require "../../controllers/retro_items/RetroItemController.php";
require "../../controllers/config/ControllersConfig.php";

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_retro_item_controller();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sprint_id = isset($_GET['sprint_id']) ? (int)$_GET['sprint_id'] : 0;

if ($id > 0) {
    $controller->delete_item($id);
}
header("Location: ../retrospectiva.php?sprint_id=$sprint_id");