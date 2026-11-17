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

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$sprint_id = isset($_POST['sprint_id']) ? (int)$_POST['sprint_id'] : 0;
$cumplida = isset($_POST['cumplida']) && $_POST['cumplida'] == '1';

if ($id > 0) {
    $item = $controller->get_item($id);
    $item->set_cumplida($cumplida);
    
    $request = [
        'id' => $item->get_id(),
        'sprint_id' => $item->get_sprint_id(),
        'categoria' => $item->get_categoria(),
        'descripcion' => $item->get_descripcion(),
        'cumplida' => $item->get_cumplida(),
        'fecha_revision' => $item->get_fecha_revision()
    ];
    
    $controller->update_item($request);
}
header("Location: ../retrospectiva.php?sprint_id=$sprint_id");