<?php
require __DIR__ . '/../models/database/RetrospectiveDBInterface.php';
require __DIR__ . '/../models/database/RetrospectiveDB.php';
require __DIR__ . '/../models/entities/Sprint.php';
require __DIR__ . '/../models/entities/RetroItem.php';
require __DIR__ . '/../models/mappers/SprintMapper.php';
require __DIR__ . '/../models/mappers/RetroItemMapper.php';
require __DIR__ . '/../models/repositories/sprint_repository/SprintRepositoryInterface.php';
require __DIR__ . '/../models/repositories/sprint_repository/SprintRepository.php';
require __DIR__ . '/../models/repositories/retro_item_repository/RetroItemRepositoryInterface.php';
require __DIR__ . '/../models/repositories/retro_item_repository/RetroItemRepository.php';
require __DIR__ . '/../models/config/ModelsConfig.php';

require __DIR__ . '/../controllers/mappers/SprintMapperController.php';
require __DIR__ . '/../controllers/mappers/RetroItemMapperController.php';
require __DIR__ . '/../controllers/sprints/SprintControllerInterface.php';
require __DIR__ . '/../controllers/sprints/SprintController.php';
require __DIR__ . '/../controllers/retro_items/RetroItemControllerInterface.php';
require __DIR__ . '/../controllers/retro_items/RetroItemController.php';
require __DIR__ . '/../controllers/config/ControllersConfig.php';

use app\controllers\config\ControllersConfig;

$sprint_controller = ControllersConfig::get_sprint_controller();
$item_controller = ControllersConfig::get_retro_item_controller();

$categoria = $_GET['categoria'] ?? 'logro';
$sprint_id = isset($_GET['sprint_id']) ? (int)$_GET['sprint_id'] : 0;

$titulo = "Agregar Item";
$action = "operaciones/retro_items/registrar_item.php";
$id = 0;
$descripcion = "";
$cumplida = false;
$fecha_revision = "";

$labels = [
    'logro' => 'Logro',
    'impedimento' => 'Impedimento',
    'accion' => 'Acción',
    'comentario' => 'Comentario'
];

$icons = [
    'logro' => 'trophy',
    'impedimento' => 'exclamation-triangle',
    'accion' => 'tasks',
    'comentario' => 'sticky-note'
];

if (!empty($_GET['id'])) {
    $titulo = "Modificar Item";
    $action = "operaciones/retro_items/modificar_item.php";
    $id = $_GET['id'];
    $item = $item_controller->get_item($id);
    $categoria = $item->get_categoria();
    $sprint_id = $item->get_sprint_id();
    $descripcion = $item->get_descripcion();
    $cumplida = $item->get_cumplida() ?? false;
    $fecha_revision = $item->get_fecha_revision() ?? '';
}

$sprint = null;
if ($sprint_id) {
    $sprint = $sprint_controller->get_sprint($sprint_id);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> - <?php echo $labels[$categoria]; ?></title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>
    <div class="app-container">
        <header class="header">
            <div class="header-content">
                <h1><i class="fas fa-<?php echo $icons[$categoria]; ?>"></i> <?php echo $titulo; ?> - <?php echo $labels[$categoria]; ?></h1>
                <?php if ($sprint): ?>
                    <p class="subtitle">Sprint: <?php echo htmlspecialchars($sprint->get_nombre()); ?></p>
                <?php endif; ?>
            </div>
        </header>

        <nav class="nav-tabs" role="tablist">
            <button class="tab-btn" role="tab" onclick="location.href='sprints.php'">
                <i class="fas fa-calendar-alt"></i> Sprints
            </button>
            <button class="tab-btn active" role="tab" onclick="location.href='retrospectiva.php?sprint_id=<?php echo $sprint_id; ?>'">
                <i class="fas fa-comments"></i> Ver retrospectiva del sprint
            </button>
            <button class="tab-btn" role="tab" onclick="location.href='historial.php'">
                <i class="fas fa-history"></i> Historial
            </button>
        </nav>

        <main class="main-content">
            <div class="panel-header">
                <h2><i class="fas fa-<?php echo empty($id) ? 'plus' : 'edit'; ?>"></i> <?php echo $titulo; ?></h2>
                <?php if ($sprint_id): ?>
                    <a href="retrospectiva.php?sprint_id=<?php echo $sprint_id; ?>" class="btn btn-add">
                        <i class="fas fa-comments"></i> Ver retrospectiva
                    </a>
                <?php endif; ?>
                <a href="retrospectiva.php?sprint_id=<?php echo $sprint_id; ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>

            <?php if (!empty($_GET['error'])): ?>
                <div class="toast error" style="position: static; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-circle toast-icon"></i>
                    <span class="toast-message">Error al guardar el item</span>
                </div>
            <?php endif; ?>

            <form action="<?php echo $action; ?>" method="post" class="retro-form" style="max-width: 600px;">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="sprint_id" value="<?php echo $sprint_id; ?>">
                <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
                
                <div class="form-group">
                    <label for="descripcion">Descripción <span class="required">*</span></label>
                    <textarea id="descripcion" name="descripcion" required rows="4" placeholder="Describa el punto a tratar..."><?php echo htmlspecialchars($descripcion); ?></textarea>
                </div>

                <?php if ($categoria === 'accion'): ?>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="cumplida" value="1" <?php echo $cumplida ? 'checked' : ''; ?>>
                            <span class="checkmark"></span>
                            <span>¿Acción cumplida?</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="fecha_revision">Fecha de revisión</label>
                        <input type="date" id="fecha_revision" name="fecha_revision" value="<?php echo $fecha_revision; ?>">
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <a href="retrospectiva.php?sprint_id=<?php echo $sprint_id; ?>" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>