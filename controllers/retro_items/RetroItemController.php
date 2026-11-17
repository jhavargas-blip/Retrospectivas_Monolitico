<?php

namespace app\controllers\retro_items;

use app\controllers\mappers\RetroItemMapperController;
use app\models\entities\RetroItem;
use app\models\repositories\retro_item_repository\RetroItemRepositoryInterface;

class RetroItemController implements RetroItemControllerInterface
{
    private RetroItemRepositoryInterface $retro_item_model;

    public function __construct(RetroItemRepositoryInterface $retro_item_model)
    {
        $this->retro_item_model = $retro_item_model;
    }

    public function get_items_by_sprint(int $sprint_id): array
    {
        return $this->retro_item_model->get_items_by_sprint($sprint_id);
    }

    public function get_items_by_category(int $sprint_id, string $categoria): array
    {
        return $this->retro_item_model->get_items_by_category($sprint_id, $categoria);
    }

    public function save_item(array $request): int
    {
        $item = RetroItemMapperController::to_entity($request);
        return $this->retro_item_model->save_new_item($item);
    }

    public function update_item(array $request): bool
    {
        $item = RetroItemMapperController::to_entity($request);
        return $this->retro_item_model->update_item($item);
    }

    public function get_item(int $id): RetroItem
    {
        return $this->retro_item_model->find_item_by_id($id);
    }

    public function delete_item(int $id): bool
    {
        return $this->retro_item_model->delete_item($id);
    }

    public function get_acciones_anteriores(int $current_sprint_id): array
    {
        return $this->retro_item_model->get_acciones_from_previous_sprints($current_sprint_id);
    }

    public function count_by_category(int $sprint_id): array
    {
        return $this->retro_item_model->count_by_category($sprint_id);
    }
}