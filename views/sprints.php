<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Sprints</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="app-container">
        <header class="header">
            <div class="header-content">
                <h1><i class="fas fa-calendar-alt"></i> Gestión de Sprints</h1>
                <p class="subtitle">Registro de Retrospectivas Ágiles</p>
            </div>
        </header>

        <nav class="nav-tabs" role="tablist">
            <button class="tab-btn active" role="tab" onclick="location.href='sprints.php'">
                <i class="fas fa-calendar-alt"></i> Sprints
            </button>
            <button class="tab-btn" role="tab" onclick="location.href='retrospectiva.php'">
                <i class="fas fa-comments"></i> Ver retrospectiva del sprint
            </button>
            <button class="tab-btn" role="tab" onclick="location.href='historial.php'">
                <i class="fas fa-history"></i> Historial
            </button>
        </nav>

        <main class="main-content">
            <div class="panel-header">
                <h2><i class="fas fa-calendar-alt"></i> Lista de Sprints</h2>
                <a href="sprint_form.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Sprint
                </a>
            </div>

            <?php if (!empty($_GET['error'])): ?>
                <div class="toast error" style="position: static; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-circle toast-icon"></i>
                    <span class="toast-message">Error al guardar los datos del sprint</span>
                </div>
            <?php endif; ?>

            <?php if (!empty($_GET['success'])): ?>
                <div class="toast success" style="position: static; margin-bottom: 20px;">
                    <i class="fas fa-check-circle toast-icon"></i>
                    <span class="toast-message">Sprint guardado correctamente</span>
                </div>
            <?php endif; ?>

            <div class="sprints-list" id="sprints-list">
                <?php if (empty($sprints)): ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-plus"></i>
                        <h3>No hay sprints registrados</h3>
                        <p>Crea tu primer sprint para empezar a registrar retrospectivas</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($sprints as $sprint): ?>
                        <div class="sprint-card" data-sprint-id="<?php echo $sprint->get_id(); ?>">
                            <div class="sprint-card-header">
                                <h3><?php echo htmlspecialchars($sprint->get_nombre()); ?></h3>
                                <div class="sprint-card-actions">
                                    <a href="retrospectiva.php?sprint_id=<?php echo $sprint->get_id(); ?>" class="btn btn-sm btn-add">
                                        <i class="fas fa-comments"></i> Ver retrospectiva
                                    </a>
                                    <a href="sprint_form.php?id=<?php echo $sprint->get_id(); ?>" class="btn btn-secondary">
                                        <i class="fas fa-edit"></i> Modificar
                                    </a>
                                    <a href="operaciones/sprints/borrar_sprint.php?id=<?php echo $sprint->get_id(); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este sprint? Se eliminarán también todos sus items de retrospectiva.');">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </div>
                            <div class="sprint-card-dates">
                                <span><i class="fas fa-play"></i> <?php echo date('d/m/Y', strtotime($sprint->get_fecha_inicio())); ?></span>
                                <span><i class="fas fa-stop"></i> <?php echo date('d/m/Y', strtotime($sprint->get_fecha_fin())); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>