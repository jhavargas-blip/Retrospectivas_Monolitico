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