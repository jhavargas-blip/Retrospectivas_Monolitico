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

$sprints = $sprint_controller->get_sprints_all();

$current_sprint_id = isset($_GET['sprint_id']) ? (int)$_GET['sprint_id'] : null;
$items = [];
$acciones_anteriores = [];

if ($current_sprint_id) {
    $items = $item_controller->get_items_by_sprint($current_sprint_id);
    $acciones_anteriores = $item_controller->get_acciones_anteriores($current_sprint_id);
}

// Group items by category
$items_by_category = [
    'logro' => [],
    'impedimento' => [],
    'accion' => [],
    'comentario' => [],
];

foreach ($items as $item) {
    $cat = $item->get_categoria();
    if (isset($items_by_category[$cat])) {
        $items_by_category[$cat][] = $item;
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Retrospectiva</title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>
    <div class="app-container">
        <header class="header">
            <div class="header-content">
                <h1><i class="fas fa-comments"></i> Nueva Retrospectiva</h1>
                <p class="subtitle">Mejora continua para equipos Scrum</p>
            </div>
        </header>

        <nav class="nav-tabs" role="tablist">
            <button class="tab-btn" role="tab" onclick="location.href='sprints.php'">
                <i class="fas fa-calendar-alt"></i> Sprints
            </button>
            <button class="tab-btn active" role="tab" onclick="location.href='retrospectiva.php<?php echo $current_sprint_id ? '?sprint_id=' . $current_sprint_id : ''; ?>'">
                <i class="fas fa-comments"></i> Ver retrospectiva del sprint
            </button>
            <button class="tab-btn" role="tab" onclick="location.href='historial.php'">
                <i class="fas fa-history"></i> Historial
            </button>
        </nav>

        <main class="main-content">
            <div class="panel-header">
                <h2><i class="fas fa-comments"></i> Registrar Retrospectiva</h2>
            </div>

            <!-- Sprint Selector -->
            <div class="sprint-selector">
                <label for="select-sprint">Seleccionar Sprint:</label>
                <select id="select-sprint" class="form-control" onchange="window.location.href='retrospectiva.php?sprint_id=' + this.value">
                    <option value="">-- Seleccione un sprint --</option>
                    <?php foreach ($sprints as $sprint): ?>
                        <option value="<?php echo $sprint->get_id(); ?>" <?php echo $current_sprint_id == $sprint->get_id() ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sprint->get_nombre()); ?> 
                            (<?php echo date('d/m/Y', strtotime($sprint->get_fecha_inicio())); ?> - <?php echo date('d/m/Y', strtotime($sprint->get_fecha_fin())); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Acciones Anteriores -->
            <?php if (!empty($acciones_anteriores)): ?>
                <div class="acciones-anteriores">
                    <h3><i class="fas fa-history"></i> Acciones del Sprint Anterior</h3>
                    <p class="help-text">Revise el cumplimiento de las acciones acordadas en la retrospectiva anterior:</p>
                    <div class="acciones-list">
                        <?php foreach ($acciones_anteriores as $accion): ?>
                            <div class="accion-anterior-item">
                                <form action="operaciones/retro_items/actualizar_accion_cumplida.php" method="post" style="display: flex; align-items: center; gap: 12px; width: 100%;">
                                    <input type="hidden" name="id" value="<?php echo $accion->get_id(); ?>">
                                    <input type="hidden" name="sprint_id" value="<?php echo $current_sprint_id; ?>">
                                    <input type="checkbox" class="accion-anterior-checkbox" 
                                        <?php echo $accion->get_cumplida() ? 'checked' : ''; ?> 
                                        name="cumplida" value="1" onchange="this.form.submit()">
                                    <div class="accion-anterior-texto"><?php echo htmlspecialchars($accion->get_descripcion()); ?></div>
                                    <div class="accion-anterior-fecha">
                                        Sprint: <?php echo htmlspecialchars($accion->get_sprint_nombre() ?? 'Anterior'); ?> - 
                                        <?php echo date('d/m/Y', strtotime($accion->get_created_at())); ?>
                                    </div>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($current_sprint_id): ?>
                <form action="operaciones/retro_items/registrar_item.php" method="post" class="retro-form" id="form-retrospectiva">
                    <input type="hidden" name="sprint_id" value="<?php echo $current_sprint_id; ?>">
                    
                    <!-- Logros -->
                    <div class="retro-section">
                        <div class="section-header">
                            <h3><i class="fas fa-trophy"></i> Logros (¿Qué funcionó bien?)</h3>
                            <a href="item_form.php?categoria=logro&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-sm btn-add">
                                <i class="fas fa-plus"></i> Agregar
                            </a>
                        </div>
                        <div class="items-container" id="logros-container">
                            <?php foreach ($items_by_category['logro'] as $item): ?>
                                <div class="retro-item categoria-logro" data-id="<?php echo $item->get_id(); ?>">
                                    <div class="item-icon"><i class="fas fa-trophy"></i></div>
                                    <div class="item-content">
                                        <div class="item-text"><?php echo htmlspecialchars($item->get_descripcion()); ?></div>
                                        <div class="item-meta">
                                            <span><i class="fas fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($item->get_created_at())); ?></span>
                                        </div>
                                    </div>
                                    <div class="item-actions">
                                        <a href="item_form.php?id=<?php echo $item->get_id(); ?>&categoria=logro&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a href="operaciones/retro_items/borrar_item.php?id=<?php echo $item->get_id(); ?>&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-icon" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este item?');"><i class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Impedimentos -->
                    <div class="retro-section">
                        <div class="section-header">
                            <h3><i class="fas fa-exclamation-triangle"></i> Impedimentos (¿Qué necesita mejorar?)</h3>
                            <a href="item_form.php?categoria=impedimento&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-sm btn-add">
                                <i class="fas fa-plus"></i> Agregar
                            </a>
                        </div>
                        <div class="items-container" id="impedimentos-container">
                            <?php foreach ($items_by_category['impedimento'] as $item): ?>
                                <div class="retro-item categoria-impedimento" data-id="<?php echo $item->get_id(); ?>">
                                    <div class="item-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                    <div class="item-content">
                                        <div class="item-text"><?php echo htmlspecialchars($item->get_descripcion()); ?></div>
                                        <div class="item-meta">
                                            <span><i class="fas fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($item->get_created_at())); ?></span>
                                        </div>
                                    </div>
                                    <div class="item-actions">
                                        <a href="item_form.php?id=<?php echo $item->get_id(); ?>&categoria=impedimento&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a href="operaciones/retro_items/borrar_item.php?id=<?php echo $item->get_id(); ?>&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-icon" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este item?');"><i class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="retro-section">
                        <div class="section-header">
                            <h3><i class="fas fa-tasks"></i> Acciones (Compromisos para el próximo sprint)</h3>
                            <a href="item_form.php?categoria=accion&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-sm btn-add">
                                <i class="fas fa-plus"></i> Agregar
                            </a>
                        </div>
                        <div class="items-container" id="acciones-container">
                            <?php foreach ($items_by_category['accion'] as $item): ?>
                                <div class="retro-item categoria-accion <?php echo $item->get_cumplida() ? 'cumplida' : ''; ?>" data-id="<?php echo $item->get_id(); ?>">
                                    <div class="item-icon"><i class="fas fa-tasks"></i></div>
                                    <div class="item-content">
                                        <div class="item-text"><?php echo htmlspecialchars($item->get_descripcion()); ?></div>
                                        <div class="item-meta">
                                            <?php if ($item->get_cumplida() !== null): ?>
                                                <span style="color: <?php echo $item->get_cumplida() ? 'var(--success)' : 'var(--danger)'; ?>">
                                                    <i class="fas fa-<?php echo $item->get_cumplida() ? 'check-circle' : 'times-circle'; ?>"></i> 
                                                    <?php echo $item->get_cumplida() ? 'Cumplida' : 'Pendiente'; ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($item->get_fecha_revision()): ?>
                                                <span><i class="fas fa-calendar-check"></i> Revisar: <?php echo date('d/m/Y', strtotime($item->get_fecha_revision())); ?></span>
                                            <?php endif; ?>
                                            <span><i class="fas fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($item->get_created_at())); ?></span>
                                        </div>
                                    </div>
                                    <div class="item-actions">
                                        <a href="item_form.php?id=<?php echo $item->get_id(); ?>&categoria=accion&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a href="operaciones/retro_items/borrar_item.php?id=<?php echo $item->get_id(); ?>&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-icon" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este item?');"><i class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Comentarios -->
                    <div class="retro-section">
                        <div class="section-header">
                            <h3><i class="fas fa-sticky-note"></i> Comentarios Adicionales</h3>
                            <a href="item_form.php?categoria=comentario&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-sm btn-add">
                                <i class="fas fa-plus"></i> Agregar
                            </a>
                        </div>
                        <div class="items-container" id="comentarios-container">
                            <?php foreach ($items_by_category['comentario'] as $item): ?>
                                <div class="retro-item categoria-comentario" data-id="<?php echo $item->get_id(); ?>">
                                    <div class="item-icon"><i class="fas fa-sticky-note"></i></div>
                                    <div class="item-content">
                                        <div class="item-text"><?php echo htmlspecialchars($item->get_descripcion()); ?></div>
                                        <div class="item-meta">
                                            <span><i class="fas fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($item->get_created_at())); ?></span>
                                        </div>
                                    </div>
                                    <div class="item-actions">
                                        <a href="item_form.php?id=<?php echo $item->get_id(); ?>&categoria=comentario&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a href="operaciones/retro_items/borrar_item.php?id=<?php echo $item->get_id(); ?>&sprint_id=<?php echo $current_sprint_id; ?>" class="btn btn-icon" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este item?');"><i class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <h3>Seleccione un sprint</h3>
                    <p>Elija un sprint del desplegable para comenzar a registrar la retrospectiva</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>