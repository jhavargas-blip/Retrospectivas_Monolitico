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

$sprint_id = isset($_POST['sprint_id']) ? (int)$_POST['sprint_id'] : 0;

$id = $controller->save_item($_POST);
if ($id === false || $id === 0) {
    header("Location: ../item_form.php?categoria=" . $_POST['categoria'] . "&sprint_id=$sprint_id&error=1");
} else {
    header("Location: ../retrospectiva.php?sprint_id=$sprint_id");
}