<?php
require __DIR__ . '/../../../models/database/RetrospectiveDBInterface.php';
require __DIR__ . '/../../../models/database/RetrospectiveDB.php';
require __DIR__ . '/../../../models/entities/RetroItem.php';
require __DIR__ . '/../../../models/mappers/RetroItemMapper.php';
require __DIR__ . '/../../../models/repositories/retro_item_repository/RetroItemRepositoryInterface.php';
require __DIR__ . '/../../../models/repositories/retro_item_repository/RetroItemRepository.php';
require __DIR__ . '/../../../models/config/ModelsConfig.php';

require __DIR__ . '/../../../controllers/mappers/RetroItemMapperController.php';
require __DIR__ . '/../../../controllers/retro_items/RetroItemControllerInterface.php';
require __DIR__ . '/../../../controllers/retro_items/RetroItemController.php';
require __DIR__ . '/../../../controllers/config/ControllersConfig.php';

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_retro_item_controller();

$sprint_id = isset($_POST['sprint_id']) ? (int)$_POST['sprint_id'] : 0;

$result = $controller->update_item($_POST);
if (!$result) {
    header("Location: ../../item_form.php?id=" . $_POST['id'] . "&categoria=" . $_POST['categoria'] . "&sprint_id=$sprint_id&error=1");
} else {
    header("Location: ../../retrospectiva.php?sprint_id=$sprint_id");
}