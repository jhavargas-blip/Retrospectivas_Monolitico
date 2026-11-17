<?php

namespace app\models\repositories\retro_item_repository;

use app\models\entities\RetroItem;

interface RetroItemRepositoryInterface
{
    public function get_items_by_sprint(int $sprint_id): array;
    public function get_items_by_category(int $sprint_id, string $categoria): array;
    public function find_item_by_id(int $id): RetroItem;
    public function save_new_item(RetroItem $item): int;
    public function update_item(RetroItem $item): bool;
    public function delete_item(int $id): bool;
    public function get_acciones_from_previous_sprints(int $current_sprint_id): array;
    public function count_by_category(int $sprint_id): array;
}