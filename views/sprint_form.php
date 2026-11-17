<?php
require __DIR__ . '/../models/database/RetrospectiveDBInterface.php';
require __DIR__ . '/../models/database/RetrospectiveDB.php';
require __DIR__ . '/../models/entities/Sprint.php';
require __DIR__ . '/../models/mappers/SprintMapper.php';
require __DIR__ . '/../models/repositories/sprint_repository/SprintRepositoryInterface.php';
require __DIR__ . '/../models/repositories/sprint_repository/SprintRepository.php';
require __DIR__ . '/../models/config/ModelsConfig.php';

require __DIR__ . '/../controllers/mappers/SprintMapperController.php';
require __DIR__ . '/../controllers/sprints/SprintControllerInterface.php';
require __DIR__ . '/../controllers/sprints/SprintController.php';
require __DIR__ . '/../controllers/config/ControllersConfig.php';

use app\controllers\config\ControllersConfig;

$titulo = "Crear Sprint";
$action = "operaciones/sprints/registrar_sprint.php";
$id = 0;
$nombre = "";
$fecha_inicio = "";
$fecha_fin = "";

if (!empty($_GET['id'])) {
    $titulo = "Modificar Sprint";
    $action = "operaciones/sprints/modificar_sprint.php";
    $id = $_GET['id'];
    $controller = ControllersConfig::get_sprint_controller();
    $sprint = $controller->get_sprint($id);
    $nombre = $sprint->get_nombre();
    $fecha_inicio = $sprint->get_fecha_inicio();
    $fecha_fin = $sprint->get_fecha_fin();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>
    <div class="app-container">
        <header class="header">
            <div class="header-content">
                <h1><i class="fas fa-calendar-alt"></i> <?php echo $titulo; ?></h1>
            </div>
        </header>

        <nav class="nav-tabs" role="tablist">
            <button class="tab-btn" role="tab" onclick="location.href='sprints.php'">
                <i class="fas fa-calendar-alt"></i> Sprints
            </button>
            <button class="tab-btn" role="tab" onclick="location.href='retrospectiva.php<?php echo !empty($id) ? '?sprint_id=' . (int)$id : ''; ?>'">
                <i class="fas fa-comments"></i> Ver retrospectiva del sprint
            </button>
            <button class="tab-btn" role="tab" onclick="location.href='historial.php'">
                <i class="fas fa-history"></i> Historial
            </button>
        </nav>

        <main class="main-content">
            <div class="panel-header">
                <h2><i class="fas fa-<?php echo empty($id) ? 'plus' : 'edit'; ?>"></i> <?php echo $titulo; ?></h2>
                <?php if (!empty($id)): ?>
                    <a href="retrospectiva.php?sprint_id=<?php echo (int)$id; ?>" class="btn btn-add">
                        <i class="fas fa-comments"></i> Ver retrospectiva
                    </a>
                <?php endif; ?>
                <a href="sprints.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>

            <?php if (!empty($_GET['error'])): ?>
                <div class="toast error" style="position: static; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-circle toast-icon"></i>
                    <span class="toast-message">Error al guardar los datos del sprint</span>
                </div>
            <?php endif; ?>

            <form action="<?php echo $action; ?>" method="post" class="retro-form">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre del Sprint <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required placeholder="Ej: Sprint 1 - Autenticación">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha Inicio <span class="required">*</span></label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha Fin <span class="required">*</span></label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin; ?>" required>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="sprints.php" class="btn btn-secondary">Cancelar</a>
                    <?php if (!empty($id)): ?>
                        <a href="operaciones/sprints/borrar_sprint.php?id=<?php echo (int)$id; ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este sprint? Se eliminarán también todos sus items de retrospectiva.');">
                            <i class="fas fa-trash"></i> Eliminar Sprint
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo !empty($id) ? 'Modificar Sprint' : 'Guardar Sprint'; ?>
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>