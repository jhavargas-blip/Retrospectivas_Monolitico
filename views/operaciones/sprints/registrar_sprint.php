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

$controller = ControllersConfig::get_sprint_controller();

$id = $controller->save_sprint($_POST);
if ($id === false || $id === 0) {
    header("Location: ../../sprint_form.php?error=1");
} else {
    header("Location: ../../sprints.php?success=1");
}