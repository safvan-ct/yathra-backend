<?php
namespace App\Repositories\Interfaces;

interface TransitRouteRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function findByOriginDestinationAndSignature(int $originId, int $destinationId, string $pathSignature, ?int $excludeId = null);
}
