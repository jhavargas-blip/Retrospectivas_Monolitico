<?php

namespace app\controllers\sprints;

use app\controllers\mappers\SprintMapperController;
use app\models\entities\Sprint;
use app\models\repositories\sprint_repository\SprintRepositoryInterface;
use Exception;

class SprintController implements SprintControllerInterface
{
    private SprintRepositoryInterface $sprint_model;

    public function __construct(SprintRepositoryInterface $sprint_model)
    {
        $this->sprint_model = $sprint_model;
    }
    public function get_sprints_all(): array
    {
        return $this->sprint_model->get_sprints();
    }
    public function get_sprint(int $id): ?Sprint
    {
        try {
            return $this->sprint_model->find_sprint_by_id($id);
        } catch (Exception $ex) {
            if ($ex->getCode() === 2) {
                return null;
            }
            throw $ex;
        }
    }
    public function save_sprint(array $request): int|false
    {
        if (!$this->is_valid($request)) {
            return false;
        }

        $sprint = SprintMapperController::to_entity($request);
        return $this->sprint_model->save_new_sprint($sprint);
    }
    public function update_sprint(array $request): bool
    {
        if (empty($request['id']) || !$this->is_valid($request)) {
            return false;
        }

        $sprint = SprintMapperController::to_entity($request);
        return $this->sprint_model->update_sprint($sprint);
    }
    public function delete_sprint(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        return $this->sprint_model->delete_sprint($id);
    }
    private function is_valid(array $request): bool {
        $nombre = trim($request['nombre'] ?? '');
        $inicio = $request['fecha_inicio'] ?? '';
        $fin = $request['fecha_fin'] ?? '';

        if ($nombre === '' || $inicio === '' || $fin === '') {
            return false;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $inicio)) {
            return false;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fin)) {
            return false;
        }

        return $inicio <= $fin;
    }
}