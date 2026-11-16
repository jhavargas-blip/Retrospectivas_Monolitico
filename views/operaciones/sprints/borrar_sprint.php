<?php
require "../../models/database/RetrospectiveDBInterface.php";
require "../../models/database/RetrospectiveDB.php";
require "../../models/entities/Sprint.php";
require "../../models/mappers/SprintMapper.php";
require "../../models/repositories/sprint_repository/SprintRepositoryInterface.php";
require "../../models/repositories/sprint_repository/SprintRepository.php";
require "../../models/config/ModelsConfig.php";

require "../../controllers/mappers/SprintMapperController.php";
require "../../controllers/sprints/SprintControllerInterface.php";
require "../../controllers/sprints/SprintController.php";
require "../../controllers/config/ControllersConfig.php";

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_sprint_controller();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $controller->delete_sprint($id);
}
header("Location: ../sprints.php?success=1");