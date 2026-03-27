<?php
namespace App\Repositories\Eloquent;

use App\Models\RouteNode;
use App\Repositories\Interfaces\RouteNodeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RouteNodeRepository implements RouteNodeRepositoryInterface
{
    public function listByRoute(int $routeId)
    {
        return RouteNode::with('station')->where('route_id', $routeId)->orderBy('stop_sequence')->get();
    }

    public function findInRoute(int $routeId, int $id)
    {
        return RouteNode::with('station')->where('route_id', $routeId)->where('id', $id)->first();
    }

    public function create(array $data)
    {
        return RouteNode::create($data);
    }

    public function update(int $id, array $data)
    {
        $node = RouteNode::find($id);
        if (! $node) {
            return null;
        }

        $node->update($data);
        return $node->fresh('station');
    }

    public function upsertBatch(array $data)
    {
        RouteNode::upsert(
            $data,
            ['id'],
            ['station_id', 'stop_sequence', 'distance_from_origin', 'is_active', 'updated_at']
        );
    }

    public function delete(int $id)
    {
        $node = RouteNode::find($id);
        if (! $node) {
            return false;
        }

        return $node->delete();
    }

    public function deleteByRoute(int $routeId)
    {
        return RouteNode::where('route_id', $routeId)->delete();
    }

    public function deleteRemovedNodesByRoute(int $routeId, array $nodes)
    {
        return RouteNode::where('route_id', $routeId)->whereNotIn('id', $nodes)->delete();
    }

    public function shiftAllsequence(int $routeId)
    {
        return RouteNode::where('route_id', $routeId)->update(['stop_sequence' => DB::raw('stop_sequence + 1000')]);
    }
}
