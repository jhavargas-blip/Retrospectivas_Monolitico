<?php

namespace app\controllers\retro_items;

use app\models\entities\RetroItem;

interface RetroItemControllerInterface
{
    public function get_items_by_sprint(int $sprint_id): array;
    public function get_items_by_category(int $sprint_id, string $categoria): array;
    public function save_item(array $request): int;
    public function update_item(array $request): bool;
    public function get_item(int $id): RetroItem;
    public function delete_item(int $id): bool;
    public function get_acciones_anteriores(int $current_sprint_id): array;
    public function count_by_category(int $sprint_id): array;
}