<?php
namespace App\Repositories\Interfaces;

interface RouteNodeRepositoryInterface
{
    public function listByRoute(int $routeId);
    public function findInRoute(int $routeId, int $id);
    public function create(array $data);

    public function update(int $id, array $data);
    public function upsertBatch(array $data);

    public function delete(int $id);
    public function deleteByRoute(int $routeId);
    public function deleteRemovedNodesByRoute(int $routeId, array $nodes);

    public function shiftAllsequence(int $routeId);
}
