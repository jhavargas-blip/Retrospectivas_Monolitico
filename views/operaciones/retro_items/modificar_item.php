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

$sprint_id = isset($_POST['sprint_id']) ? (int)$_POST['sprint_id'] : 0;

$result = $controller->update_item($_POST);
if (!$result) {
    header("Location: ../item_form.php?id=" . $_POST['id'] . "&categoria=" . $_POST['categoria'] . "&sprint_id=$sprint_id&error=1");
} else {
    header("Location: ../retrospectiva.php?sprint_id=$sprint_id");
}