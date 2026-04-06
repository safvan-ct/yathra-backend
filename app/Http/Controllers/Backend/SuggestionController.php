<?php
namespace App\Http\Controllers\Backend;

use App\Enums\SuggestionStatus;
use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\City;
use App\Models\District;
use App\Models\RouteNode;
use App\Models\State;
use App\Models\Station;
use App\Models\TransitRoute;
use App\Models\Trip;
use App\Repositories\Interfaces\SuggestionRepositoryInterface;
use App\Services\SuggestionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function __construct(
        protected SuggestionRepositoryInterface $suggestionRepository,
        protected SuggestionService $suggestionService
    ) {}

    public function index()
    {
        return view('backend.suggestions.index');
    }

    public function getList(Request $request)
    {
        $status  = $request->get('status', SuggestionStatus::Pending->value);
        $search  = $request->get('search');
        $filters = ['status' => $status, 'search' => $search];

        $suggestions = $this->suggestionRepository->paginate($filters, 15, true);

        return view('backend.suggestions.partials.list_card', compact('suggestions'))->render();
    }

    public function show($id)
    {
        $suggestion = $this->suggestionRepository->find($id);

        if (! $suggestion) {
            return response()->json(['error' => 'Suggestion not found'], 404);
        }

        $similarItems = collect();
        $icon         = 'ti-map-pin';
        $title        = 'Potential Existing Entries';

        $metadata = [];

        if ($suggestion->proposed_for === 'Bus') {
            $name   = $suggestion->proposed_data['bus_name'] ?? '';
            $number = $suggestion->proposed_data['bus_number'] ?? '';

            if (! empty($name) || ! empty($number)) {
                $similarItems = Bus::where('bus_name', 'LIKE', '%' . $name . '%')
                    ->orWhere('bus_number', 'LIKE', '%' . $number . '%')
                    ->take(5)->get()
                    ->map(fn($item) => ['primary' => $item->bus_name, 'secondary' => $item->bus_number]);

                $icon = 'ti-bus';
            }
        } elseif ($suggestion->proposed_for === 'Stop') {
            foreach (['state_id' => State::class, 'district_id' => District::class, 'city_id' => City::class] as $key => $model) {
                if (! empty($suggestion->proposed_data[$key])) {
                    $item = $model::find($suggestion->proposed_data[$key]);

                    if ($item) {
                        $metadata[$key] = $item->name;
                    }
                }
            }

            $name = $suggestion->proposed_data['name'] ?? '';
            $code = $suggestion->proposed_data['code'] ?? '';

            if (! empty($name) || ! empty($code)) {
                $similarItems = \App\Models\Station::where('name', 'LIKE', '%' . $name . '%')
                    ->orWhere('code', 'LIKE', '%' . $code . '%')
                    ->take(5)->get()
                    ->map(fn($item) => ['primary' => $item->name, 'secondary' => $item->local_name ?? $item->code]);

                $icon = 'ti-map-pin';
            }
        } elseif ($suggestion->proposed_for === 'Route') {
            $originId                = $suggestion->proposed_data['origin_id'] ?? null;
            $destinationId           = $suggestion->proposed_data['destination_id'] ?? null;
            $metadata['origin']      = Station::find($originId);
            $metadata['destination'] = Station::find($destinationId);

            if ($originId || $destinationId) {
                $q = TransitRoute::with(['origin', 'destination']);
                if ($originId) {
                    $q->where('origin_id', $originId);
                }

                if ($destinationId) {
                    $q->where('destination_id', $destinationId);
                }

                $similarItems = $q->take(5)->get()
                    ->map(fn($item) => [
                        'primary'   => ($item->origin->name ?? '') . ' to ' . ($item->destination->name ?? ''),
                        'secondary' => $item->path_signature . ' (' . (int) $item->distance . 'KM)',
                    ]);

                $icon = 'ti-route';
            }
        } elseif ($suggestion->proposed_for === 'Trip') {
            $busId             = $suggestion->proposed_data['bus_id'] ?? null;
            $routeId           = $suggestion->proposed_data['route_id'] ?? null;
            $metadata['bus']   = Bus::find($busId);
            $metadata['route'] = TransitRoute::with(['origin', 'destination'])->find($routeId);

            $days = $suggestion->proposed_data['days_of_week'] ?? null;

            if ($days !== null) {
                if (is_array($days)) {
                    $dayNames               = [];
                    $allDays                = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    $metadata['days_array'] = $days;

                    if (count($days) === 7) {
                        foreach ($days as $index => $active) {
                            if ($active == 1 && isset($allDays[$index])) {
                                $dayNames[] = $allDays[$index];
                            }
                        }

                        if (count($dayNames) === 7) {
                            $metadata['day_name'] = 'Daily';
                        } elseif (count($dayNames) === 0) {
                            $metadata['day_name'] = 'None';
                        } else {
                            $metadata['day_name'] = implode(', ', $dayNames);
                        }
                    } else {
                        $metadata['day_name'] = implode(', ', array_map(fn($d) => is_numeric($d) ? Carbon::now()->startOfWeek()->addDays($d)->format('D') : $d, $days));
                    }
                } elseif (is_numeric($days)) {
                    $metadata['day_name'] = Carbon::now()->startOfWeek()->addDays($days)->format('l');
                } else {
                    $metadata['day_name'] = $days;
                }
            }

            $bus   = $suggestion->proposed_data['bus_id'] ?? null;
            $route = $suggestion->proposed_data['route_id'] ?? null;

            if ($bus || $route) {
                $q = Trip::with(['bus', 'route.origin', 'route.destination']);
                if ($bus) {
                    $q->where('bus_id', $bus);
                }

                if ($route) {
                    $q->where('route_id', $route);
                }

                $similarItems = $q->take(24)->get()
                    ->map(fn($item) => [
                        'primary'   => ($item->bus->bus_name ?? 'Bus') . ' @ ' . Carbon::parse($item->departure_time)->format('h:i A'),
                        // 'primary'   => ($item->bus->bus_name ?? 'Bus') . ' @ ' . Carbon::parse($item->departure_time)->format('h:i A') . ' to ' . Carbon::parse($item->arrival_time)->format('h:i A'),
                        'secondary' => ($item->route->origin->name ?? '') . ' to ' . ($item->route->destination->name ?? ''),
                    ]);

                $icon = 'ti-clock';
            }
        } elseif ($suggestion->proposed_for === 'Route Stop') {
            $routeId             = $suggestion->proposed_data['route_id'] ?? null;
            $stationId           = $suggestion->proposed_data['station_id'] ?? null;
            $routeNodeId         = $suggestion->proposed_data['before_node_id'] ?? null;
            $stopSequence        = $suggestion->proposed_data['stop_sequence'] ?? 0;
            $metadata['route']   = TransitRoute::with(['origin', 'destination'])->find($routeId);
            $metadata['station'] = Station::find($stationId);

            if ($routeId) {
                $metadata['before_stop'] = RouteNode::with('station')->where('route_id', $routeId)->where('id', $routeNodeId)->first();
            }

            if ($routeId && $stationId) {
                $similarItems = RouteNode::with(['station', 'route'])
                    ->where('route_id', $routeId)
                    ->where('station_id', $stationId)
                    ->take(5)->get()
                    ->map(fn($item) => [
                        'primary'   => 'Station: ' . ($item->station->name ?? ''),
                        'secondary' => 'Seq: ' . $item->stop_sequence . ' | Dist: ' . $item->distance_from_origin . 'km',
                    ]);

                $icon = 'ti-map-pins';
            }
        }

        return view('backend.suggestions.partials.detail_card', compact('suggestion', 'similarItems', 'icon', 'title', 'metadata'))->render();
    }

    public function review(Request $request, $id)
    {
        $request->validate([
            'status'      => 'required|in:Approved,Rejected,Flagged',
            'review_note' => 'nullable|string',
        ]);

        $status  = SuggestionStatus::tryFrom($request->status);
        $adminId = auth('staff')->id();

        try {
            $this->suggestionService->reviewSuggestion($id, $status, $adminId, $request->review_note);
            return response()->json(['message' => 'Suggestion ' . strtolower($status->value) . ' successfully.']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
