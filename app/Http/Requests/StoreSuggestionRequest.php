<?php
namespace App\Http\Requests;

use App\Enums\SuggestionType;
use App\Models\Bus;
use App\Models\RouteNode;
use App\Models\Station;
use App\Models\TransitRoute;
use App\Services\BusService;
use App\Services\TransitRouteService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'suggestable_type' => ['required', 'string', Rule::in(['Trip', 'Route', 'Station', 'Bus', 'Stop'])],
            'suggestable_id'   => ['nullable', 'integer'],
            'type'             => ['nullable', 'string', Rule::in(array_column(SuggestionType::cases(), 'value'))],
            'proposed_data'    => ['required', 'array'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('suggestable_type');
            $data = $this->input('proposed_data', []);

            if ($type === 'Bus') {
                if (empty($data['bus_name'])) {
                    $validator->errors()->add('proposed_data.bus_name', 'Bus name is required.');
                }

                if (empty($data['bus_number'])) {
                    $validator->errors()->add('proposed_data.bus_number', 'Bus number is required.');
                } else {
                    // regex check
                    if (! preg_match(BusService::INDIAN_BUS_NUMBER_REGEX, $data['bus_number'])) {
                        $validator->errors()->add('proposed_data.bus_number', 'Invalid bus number format.');
                    }

                    // unique check (manual)
                    $exists = Bus::where('bus_number', $data['bus_number'])->exists();
                    if ($exists) {
                        $validator->errors()->add('proposed_data.bus_number', 'Bus number already exists.');
                    }
                }

                if (empty($data['bus_color'])) {
                    $validator->errors()->add('proposed_data.bus_color', 'Bus color is required.');
                } elseif (! in_array($data['bus_color'], ['White', 'Blue', 'Red', 'Green'])) {
                    $validator->errors()->add('proposed_data.bus_color', 'Invalid bus color.');
                }

                if (empty($data['bus_category'])) {
                    $validator->errors()->add('proposed_data.bus_category', 'Bus category is required.');
                } elseif (! in_array($data['bus_category'], ['Sleeper', 'Seater', 'AC', 'Ordinary'])) {
                    $validator->errors()->add('proposed_data.bus_category', 'Invalid bus category.');
                }

                if (empty($data['operator_type'])) {
                    $validator->errors()->add('proposed_data.operator_type', 'Bus type is required.');
                } elseif (! in_array($data['operator_type'], ['Private', 'Government'])) {
                    $validator->errors()->add('proposed_data.operator_type', 'Invalid bus type.');
                }
            } else if ($type === 'Station') {
                if (empty($data['state_id'])) {
                    $validator->errors()->add('proposed_data.state_id', 'State ID is required.');
                } else {
                    $exists = \App\Models\State::where('id', $data['state_id'])->exists();
                    if (! $exists) {
                        $validator->errors()->add('proposed_data.state_id', 'State not found.');
                    }
                }

                if (empty($data['district_id'])) {
                    $validator->errors()->add('proposed_data.district_id', 'District ID is required.');
                } else {
                    $exists = \App\Models\District::where('id', $data['district_id'])->first();
                    if (! $exists || $data['state_id'] != $exists->state_id) {
                        $validator->errors()->add('proposed_data.district_id', 'District not found.');
                    }
                }

                if (empty($data['city_id'])) {
                    $validator->errors()->add('proposed_data.city_id', 'City ID is required.');
                } else {
                    $exists = \App\Models\City::where('id', $data['city_id'])->first();
                    if (! $exists || $data['district_id'] != $exists->district_id) {
                        $validator->errors()->add('proposed_data.city_id', 'District not found.');
                    }
                }

                if (empty($data['name'])) {
                    $validator->errors()->add('proposed_data.name', 'Station name is required.');
                } else {
                    $exist = \App\Models\Station::where('city_id', $data['city_id'])->where('name', $data['name'])->exists();
                    if ($exist) {
                        $validator->errors()->add('proposed_data.name', 'Station with this name already exists in the city.');
                    }
                }

                if (empty($data['type'])) {
                    $validator->errors()->add('proposed_data.type', 'Station type is required.');
                } elseif (! in_array($data['type'], ['Hub', 'Stop', 'Terminal'])) {
                    $validator->errors()->add('proposed_data.type', 'Invalid station type.');
                }
            } else if ($type === 'Route') {
                if (empty($data['origin_id'])) {
                    $validator->errors()->add('proposed_data.origin_id', 'Origin ID is required.');
                } else {
                    $exists = \App\Models\Station::where('id', $data['origin_id'])->exists();
                    if (! $exists) {
                        $validator->errors()->add('proposed_data.origin_id', 'Origin station not found.');
                    }
                }

                if (empty($data['destination_id'])) {
                    $validator->errors()->add('proposed_data.destination_id', 'Destination ID is required.');
                } else {
                    $exists = \App\Models\Station::where('id', $data['destination_id'])->exists();
                    if (! $exists) {
                        $validator->errors()->add('proposed_data.destination_id', 'Destination station not found.');
                    }
                }

                $routeExist = \App\Models\TransitRoute::where('origin_id', $data['origin_id'])
                    ->where('destination_id', $data['destination_id'])
                    ->where('path_signature', $data['path_signature'])
                    ->exists();
                if ($routeExist) {
                    $validator->errors()->add('proposed_data', 'An identical route already exists.');
                }

                if (empty($data['path_signature'])) {
                    $validator->errors()->add('proposed_data.path_signature', 'Path signature is required.');
                }

                if (empty($data['distance'])) {
                    $validator->errors()->add('proposed_data.distance', 'Distance is required.');
                }
            } else if ($type === 'Trip') {
                if (empty($data['bus_id'])) {
                    $validator->errors()->add('proposed_data.bus_id', 'Bus ID is required.');
                } else {
                    $exists = Bus::where('id', $data['bus_id'])->exists();
                    if (! $exists) {
                        $validator->errors()->add('proposed_data.bus_id', 'Bus not found.');
                    }
                }

                if (empty($data['route_id'])) {
                    $validator->errors()->add('proposed_data.route_id', 'Route ID is required.');
                } else {
                    $exists = TransitRoute::where('id', $data['route_id'])->exists();
                    if (! $exists) {
                        $validator->errors()->add('proposed_data.route_id', 'Route not found.');
                    }
                }

                if (empty($data['departure_time'])) {
                    $validator->errors()->add('proposed_data.departure_time', 'Departure time is required.');
                } else if (! preg_match('/^\d{2}:\d{2}$/', $data['departure_time'])) {
                    $validator->errors()->add('proposed_data.departure_time', 'Invalid time format (H:i).');
                }

                if (empty($data['arrival_time'])) {
                    $validator->errors()->add('proposed_data.arrival_time', 'Arrival time is required.');
                } else if (! preg_match('/^\d{2}:\d{2}$/', $data['arrival_time'])) {
                    $validator->errors()->add('proposed_data.arrival_time', 'Invalid time format (H:i).');
                }

                if (empty($data['days_of_week'])) {
                    $validator->errors()->add('proposed_data.days_of_week', 'Day index is required.');
                } else if (! is_array($data['days_of_week']) || count($data['days_of_week']) !== 7) {
                    $validator->errors()->add('proposed_data.days_of_week', 'Days of week must be an array with 7 boolean values.');
                }

                $tripExist = \App\Models\Trip::where('bus_id', $data['bus_id'])
                    ->where('route_id', $data['route_id'])
                    ->where('departure_time', $data['departure_time'])
                    ->where('arrival_time', $data['arrival_time'])
                    ->exists();
                if ($tripExist) {
                    $validator->errors()->add('proposed_data', 'An identical trip already exists.');
                }
            } else if ($type === 'Stop') {
                if (empty($data['route_id'])) {
                    $validator->errors()->add('proposed_data.route_id', 'Route ID is required.');
                } else {
                    $exists = TransitRoute::where('id', $data['route_id'])->exists();
                    if (! $exists) {
                        $validator->errors()->add('proposed_data.route_id', 'Route not found.');
                    }
                }

                if (empty($data['before_node_id'])) {
                    $validator->errors()->add('proposed_data.before_node_id', 'Before node ID is required.');
                } else {
                    $exists = RouteNode::where('id', $data['before_node_id'])->exists();
                    if (! $exists) {
                        $validator->errors()->add('proposed_data.before_node_id', 'Route node not found.');
                    }
                }

                if (empty($data['station_id'])) {
                    $validator->errors()->add('proposed_data.station_id', 'Station ID is required.');
                } else {
                    $exists = Station::where('id', $data['station_id'])->exists();
                    if (! $exists) {
                        $validator->errors()->add('proposed_data.station_id', 'Station not found.');
                    }
                }

                if (empty($data['distance_from_origin'])) {
                    $validator->errors()->add('proposed_data.distance_from_origin', 'Distance from origin is required.');
                }

                $routeStop = \App\Models\RouteNode::where('route_id', $data['route_id'])
                    ->where('station_id', $data['station_id'])
                    ->exists();
                if ($routeStop) {
                    $validator->errors()->add('proposed_data', 'An identical route stop already exists.');
                }
            }
        });
    }

    /**
     * Optional: sanitize / normalize input
     */
    protected function prepareForValidation()
    {
        if ($this->has('suggestable_type')) {
            $this->merge(['suggestable_type' => ucFirst(trim($this->suggestable_type))]);
        }

        if ($this->has('proposed_data.bus_number')) {
            $this->merge([
                'proposed_data' => array_merge(
                    $this->proposed_data ?? [],
                    [
                        'bus_number' => strtoupper($this->proposed_data['bus_number']),
                    ]
                )
            ]);
        }

        if ($this->has('proposed_data.name')) {
            $this->merge([
                'proposed_data' => array_merge(
                    $this->proposed_data ?? [],
                    [
                        'name' => ucwords(strtolower(trim($this->proposed_data['name']))),
                    ]
                )
            ]);
        }

        if ($this->has('proposed_data.path_signature')) {
            $routeService = app(TransitRouteService::class);
            $this->merge([
                'proposed_data' => array_merge(
                    $this->proposed_data ?? [],
                    [
                        'path_signature' => $routeService->normalizeSignature($this->proposed_data['path_signature']),
                    ]
                )
            ]);
        }
    }
}
