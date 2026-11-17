<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Retrospectivas</title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>
    <div class="app-container">
        <header class="header">
            <div class="header-content">
                <h1><i class="fas fa-history"></i> Historial de Retrospectivas</h1>
                <p class="subtitle">Seguimiento de la mejora continua</p>
            </div>
        </header>

        <nav class="nav-tabs" role="tablist">
            <button class="tab-btn" role="tab" onclick="location.href='sprints.php'">
                <i class="fas fa-calendar-alt"></i> Sprints
            </button>
            <button class="tab-btn" role="tab" onclick="location.href='retrospectiva.php<?php echo $filter_sprint_id ? '?sprint_id=' . $filter_sprint_id : ''; ?>'">
                <i class="fas fa-comments"></i> Ver retrospectiva del sprint
            </button>
            <button class="tab-btn active" role="tab" onclick="location.href='historial.php'">
                <i class="fas fa-history"></i> Historial
            </button>
        </nav>

        <main class="main-content">
            <div class="panel-header">
                <h2><i class="fas fa-history"></i> Historial de Retrospectivas</h2>
            </div>

            <div class="historial-filters">
                <form method="get" action="historial.php">
                    <select id="filter-sprint" name="sprint_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Todos los sprints --</option>
                        <?php foreach ($sprints as $sprint): ?>
                            <option value="<?php echo $sprint->get_id(); ?>" <?php echo $filter_sprint_id == $sprint->get_id() ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sprint->get_nombre()); ?> 
                                (<?php echo date('d/m/Y', strtotime($sprint->get_fecha_inicio())); ?> - <?php echo date('d/m/Y', strtotime($sprint->get_fecha_fin())); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <div class="historial-content" id="historial-content">
                <?php if (empty($all_items)): ?>
                    <div class="empty-state">
                        <i class="fas fa-history"></i>
                        <h3>No hay retrospectivas registradas</h3>
                        <p>Crea un sprint y registra tu primera retrospectiva</p>
                    </div>
                <?php else: ?>
                    <?php 
                    // Sort sprints by date descending
                    $sprint_ids = array_keys($all_items);
                    usort($sprint_ids, function($a, $b) use ($sprint_controller) {
                        $sprint_a = $sprint_controller->get_sprint($a);
                        $sprint_b = $sprint_controller->get_sprint($b);
                        return strcmp($sprint_b->get_fecha_inicio(), $sprint_a->get_fecha_inicio());
                    });
                    
                    foreach ($sprint_ids as $sprint_id): 
                        $items = $all_items[$sprint_id];
                        $sprint = $sprint_controller->get_sprint($sprint_id);
                    ?>
                        <div class="historial-sprint">
                            <div class="historial-sprint-header">
                                <h3><?php echo htmlspecialchars($sprint->get_nombre()); ?></h3>
                                <div class="historial-sprint-dates">
                                    <i class="fas fa-calendar-alt"></i> 
                                    <?php echo date('d/m/Y', strtotime($sprint->get_fecha_inicio())); ?> - 
                                    <?php echo date('d/m/Y', strtotime($sprint->get_fecha_fin())); ?>
                                </div>
                                <a href="retrospectiva.php?sprint_id=<?php echo $sprint_id; ?>" class="btn btn-sm btn-add">
                                    <i class="fas fa-comments"></i> Ver retrospectiva
                                </a>
                            </div>
                            <div class="historial-categorias">
                                <?php
                                $categoryOrder = ['logro', 'impedimento', 'accion', 'comentario'];
                                $categoryLabels = [
                                    'logro' => 'Logros',
                                    'impedimento' => 'Impedimentos',
                                    'accion' => 'Acciones',
                                    'comentario' => 'Comentarios'
                                ];
                                $categoryIcons = [
                                    'logro' => 'trophy',
                                    'impedimento' => 'exclamation-triangle',
                                    'accion' => 'tasks',
                                    'comentario' => 'sticky-note'
                                ];

                                foreach ($categoryOrder as $cat):
                                    $cat_items = array_filter($items, fn($item) => $item->get_categoria() === $cat);
                                    if (empty($cat_items)) continue;
                                ?>
                                    <div class="historial-categoria">
                                        <div class="historial-categoria-header <?php echo $cat; ?>">
                                            <i class="fas fa-<?php echo $categoryIcons[$cat]; ?>"></i>
                                            <?php echo $categoryLabels[$cat]; ?> (<?php echo count($cat_items); ?>)
                                        </div>
                                        <div class="historial-items">
                                            <?php foreach ($cat_items as $item): ?>
                                                <div class="historial-item <?php echo $cat; ?> <?php echo $cat === 'accion' ? ($item->get_cumplida() ? 'cumplida' : 'no-cumplida') : ''; ?>">
                                                    <?php echo htmlspecialchars($item->get_descripcion()); ?>
                                                    <?php if ($cat === 'accion'): ?>
                                                        <div class="item-fecha">
                                                            <i class="fas fa-<?php echo $item->get_cumplida() ? 'check-circle' : 'times-circle'; ?>" style="color: <?php echo $item->get_cumplida() ? 'var(--success)' : 'var(--danger)'; ?>"></i>
                                                            <?php echo $item->get_cumplida() ? 'Cumplida' : 'Pendiente'; ?>
                                                            <?php if ($item->get_fecha_revision()): ?>
                                                                - Revisar: <?php echo date('d/m/Y', strtotime($item->get_fecha_revision())); ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="item-fecha"><?php echo date('d/m/Y H:i', strtotime($item->get_created_at())); ?></div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>