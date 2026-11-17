<?php
require __DIR__ . '/../../../models/database/RetrospectiveDBInterface.php';
require __DIR__ . '/../../../models/database/RetrospectiveDB.php';
require __DIR__ . '/../../../models/entities/Sprint.php';
require __DIR__ . '/../../../models/mappers/SprintMapper.php';
require __DIR__ . '/../../../models/repositories/sprint_repository/SprintRepositoryInterface.php';
require __DIR__ . '/../../../models/repositories/sprint_repository/SprintRepository.php';
require __DIR__ . '/../../../models/config/ModelsConfig.php';

require __DIR__ . '/../../../controllers/mappers/SprintMapperController.php';
require __DIR__ . '/../../../controllers/sprints/SprintControllerInterface.php';
require __DIR__ . '/../../../controllers/sprints/SprintController.php';
require __DIR__ . '/../../../controllers/config/ControllersConfig.php';

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_retro_item_controller();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sprint_id = isset($_GET['sprint_id']) ? (int)$_GET['sprint_id'] : 0;

if ($id > 0) {
    $controller->delete_item($id);
}
header("Location: ../retrospectiva.php?sprint_id=$sprint_id");