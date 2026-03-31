<?php

namespace App\Repositories\Eloquent;

use App\Models\Trip;
use App\Repositories\Interfaces\TripRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TripRepository implements TripRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = Trip::query()->with(['bus.operator', 'route.origin', 'route.destination']);

        if (!empty($filters['route_id'])) {
            $query->where('route_id', $filters['route_id']);
        }

        if (!empty($filters['bus_id'])) {
            $query->where('bus_id', $filters['bus_id']);
        }

        if (!empty($filters['operator_id'])) {
            $query->whereHas('bus', fn($q) => $q->where('operator_id', $filters['operator_id']));
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['active_only']) && (bool) $filters['active_only']) {
            $query->where('status', 'Active');
        }

        if (isset($filters['day_index'])) {
            $dayIndex = (int) $filters['day_index'];
            $query->whereRaw("JSON_EXTRACT(days_of_week, '$[$dayIndex]') = true");
        }

        return $query->orderBy('departure_time')->paginate($perPage);
    }

    public function find(int $id)
    {
        return Trip::with(['bus.operator', 'route.origin', 'route.destination'])->find($id);
    }

    public function create(array $data)
    {
        return Trip::create($data);
    }

    public function update(int $id, array $data)
    {
        /** @var \App\Models\Trip|null $trip */
        $trip = Trip::find($id);
        if (!$trip) {
            return null;
        }

        $trip->update($data);
        return $trip->fresh(['bus.operator', 'route.origin', 'route.destination']);
    }

    public function delete(int $id)
    {
        /** @var \App\Models\Trip|null $trip */
        $trip = Trip::find($id);
        if (!$trip) {
            return false;
        }

        return $trip->delete();
    }

    public function existsDuplicate(int $busId, int $routeId, string $departureTime, ?int $excludeId = null): bool
    {
        $query = Trip::query()
            ->where('bus_id', $busId)
            ->where('route_id', $routeId)
            ->where('departure_time', $departureTime)
            ->whereNull('deleted_at');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function hasOverlap(int $busId, string $departureTime, string $arrivalTime, ?int $excludeId = null): bool
    {
        $query = Trip::query()
            ->where('bus_id', $busId)
            ->whereNull('deleted_at')
            ->whereIn('status', ['Active', 'Delayed'])
            ->whereRaw('? < arrival_time AND ? > departure_time', [$departureTime, $arrivalTime]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function tripBusesByWaitTime(int $from, int $to)
    {
        $waitInSec = 45;

        return DB::table('trips as ts')
            ->join('buses as b', 'b.id', '=', 'ts.bus_id')
            ->join('routes as r', 'r.id', '=', 'ts.route_id')
            ->join('route_nodes as rn_from', fn($join) => $join->on('rn_from.route_id', '=', 'ts.route_id')->where('rn_from.station_id', $from))
            ->join('route_nodes as rn_to', fn($join) => $join->on('rn_to.route_id', '=', 'ts.route_id')->where('rn_to.station_id', $to))

            ->whereColumn('rn_from.stop_sequence', '<', 'rn_to.stop_sequence')
            ->where('ts.status', 'Active')

            ->select([
                'ts.id as trip_id',
                'b.bus_name',
                'b.bus_number',
                'b.bus_color',
                'r.distance as total_distance_km',

                // distance between stops
                DB::raw("(rn_to.distance_from_origin - rn_from.distance_from_origin) as trip_distance_km"),

                // check if the trip is running today
                DB::raw("JSON_EXTRACT(ts.days_of_week, CONCAT('$[', DAYOFWEEK(CURDATE()) - 1, ']')) as is_running_today"),

                // total trip seconds (overnight safe)
                // DB::raw("
                //     TIME_TO_SEC(
                //         IF(
                //             ts.arrival_time >= ts.departure_time,
                //             TIMEDIFF(ts.arrival_time, ts.departure_time),
                //             TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                //         )
                //     ) as total_trip_seconds
                // "),

                // wait BETWEEN from → to (exclude both ends)
                DB::raw("GREATEST((rn_to.stop_sequence - rn_from.stop_sequence - 1), 0) * $waitInSec as wait_between_seconds"),

                // wait BEFORE from (exclude origin stop)
                DB::raw("GREATEST((rn_from.stop_sequence - 1), 0) * $waitInSec as wait_before_from_seconds"),

                // wait Total (exclude origin and end stop)
                // DB::raw("GREATEST((rn_to.stop_sequence - 2), 0) * $waitInSec as total_wait_seconds"),

                // average speed in km/h
                DB::raw("
                    ROUND(
                        (
                            r.distance /
                            NULLIF(
                                (
                                    TIME_TO_SEC(
                                        IF(
                                            ts.arrival_time >= ts.departure_time,
                                            TIMEDIFF(ts.arrival_time, ts.departure_time),
                                            TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                        )
                                    )
                                    +
                                    (GREATEST((rn_to.stop_sequence - rn_from.stop_sequence - 1), 0) * $waitInSec)
                                ),
                            0)
                        ) * 3600,
                        2
                    ) as speed_kmh
                "),

                // raw departure (A → FROM + wait before FROM)
                DB::raw("
                    ADDTIME(
                        ts.departure_time,
                        SEC_TO_TIME(
                            (
                                rn_from.distance_from_origin /
                                (
                                    r.distance /
                                    NULLIF(
                                        TIME_TO_SEC(
                                            IF(
                                                ts.arrival_time >= ts.departure_time,
                                                TIMEDIFF(ts.arrival_time, ts.departure_time),
                                                TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                            )
                                        ),
                                    0)
                                )
                            )
                            +
                            (GREATEST((rn_from.stop_sequence - 1), 0) * $waitInSec)
                        )
                    ) as departure_time_raw
                "),

                // formatted departure
                DB::raw("
                    DATE_FORMAT(
                        ADDTIME(
                            ts.departure_time,
                            SEC_TO_TIME(
                                (
                                    rn_from.distance_from_origin /
                                    (
                                        r.distance /
                                        NULLIF(
                                            TIME_TO_SEC(
                                                IF(
                                                    ts.arrival_time >= ts.departure_time,
                                                    TIMEDIFF(ts.arrival_time, ts.departure_time),
                                                    TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                                )
                                            ),
                                        0)
                                    )
                                )
                                +
                                (GREATEST((rn_from.stop_sequence - 1), 0) * $waitInSec)
                            )
                        ),
                        '%h:%i %p'
                    ) as departure_time
                "),

                // formatted arrival (A → TO + wait before TO)
                DB::raw("
                    DATE_FORMAT(
                        ADDTIME(
                            ts.departure_time,
                            SEC_TO_TIME(
                                (
                                    rn_to.distance_from_origin /
                                    (
                                        r.distance /
                                        NULLIF(
                                            TIME_TO_SEC(
                                                IF(
                                                    ts.arrival_time >= ts.departure_time,
                                                    TIMEDIFF(ts.arrival_time, ts.departure_time),
                                                    TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                                )
                                            ),
                                        0)
                                    )
                                )
                                +
                                (GREATEST((rn_to.stop_sequence - rn_from.stop_sequence - 1), 0) * $waitInSec)
                            )
                        ),
                        '%h:%i %p'
                    ) as arrival_time
                "),

                // time taken (FROM → TO + only intermediate wait)
                DB::raw("
                    SEC_TO_TIME(
                        (
                            (
                                (rn_to.distance_from_origin - rn_from.distance_from_origin) / r.distance
                            ) *
                            TIME_TO_SEC(
                                IF(
                                    ts.arrival_time >= ts.departure_time,
                                    TIMEDIFF(ts.arrival_time, ts.departure_time),
                                    TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                )
                            )
                        )
                        +
                        (GREATEST((rn_to.stop_sequence - rn_from.stop_sequence - 1), 0) * $waitInSec)
                    ) as time_taken_raw
                "),

                // formatted time_taken
                DB::raw("
                    CONCAT(
                        FLOOR(
                            (
                                (
                                    (
                                        (rn_to.distance_from_origin - rn_from.distance_from_origin) / r.distance
                                    ) *
                                    TIME_TO_SEC(
                                        IF(
                                            ts.arrival_time >= ts.departure_time,
                                            TIMEDIFF(ts.arrival_time, ts.departure_time),
                                            TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                        )
                                    )
                                )
                                +
                                (GREATEST((rn_to.stop_sequence - rn_from.stop_sequence - 1), 0) * $waitInSec)
                            ) / 3600
                        ),
                        'h ',
                        FLOOR(
                            MOD(
                                (
                                    (
                                        (
                                            (rn_to.distance_from_origin - rn_from.distance_from_origin) / r.distance
                                        ) *
                                        TIME_TO_SEC(
                                            IF(
                                                ts.arrival_time >= ts.departure_time,
                                                TIMEDIFF(ts.arrival_time, ts.departure_time),
                                                TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                            )
                                        )
                                    )
                                    +
                                    (GREATEST((rn_to.stop_sequence - rn_from.stop_sequence - 1), 0) * $waitInSec)
                                ),
                                3600
                            ) / 60
                        ),
                        'm'
                    ) as time_taken
                ")
            ])

            ->orderByRaw("CASE  WHEN departure_time_raw >= CURTIME() THEN 0 ELSE 1 END")
            ->orderBy('departure_time_raw')
            ->get();
    }

    public function tripBusesWithoutWait(int $from, int $to)
    {
        return DB::table('trips as ts')
            ->join('buses as b', 'b.id', '=', 'ts.bus_id')
            ->join('routes as r', 'r.id', '=', 'ts.route_id')
            ->join('route_nodes as rn_from', fn($join) => $join->on('rn_from.route_id', '=', 'ts.route_id')->where('rn_from.station_id', $from))
            ->join('route_nodes as rn_to', fn($join) => $join->on('rn_to.route_id', '=', 'ts.route_id')->where('rn_to.station_id', $to))

            ->whereColumn('rn_from.stop_sequence', '<', 'rn_to.stop_sequence')
            ->where('ts.status', 'Active')

            ->select([
                'ts.id as trip_id',
                'b.bus_name',
                'b.bus_number',
                'b.bus_color',
                'r.distance as total_distance_km',

                // distance between the two stations
                DB::raw("(rn_to.distance_from_origin - rn_from.distance_from_origin) as trip_distance_km"),

                // check if the trip is running today
                DB::raw("JSON_EXTRACT(ts.days_of_week, CONCAT('$[', DAYOFWEEK(CURDATE()) - 1, ']')) as is_running_today"),

                // total trip seconds
                // DB::raw("
                //     TIME_TO_SEC(
                //         IF(
                //             ts.arrival_time >= ts.departure_time,
                //             TIMEDIFF(ts.arrival_time, ts.departure_time),
                //             TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                //         )
                //     ) as total_trip_seconds
                // "),

                // speed (km/h)
                DB::raw("
                    ROUND(
                        (
                            r.distance /
                            NULLIF(
                                TIME_TO_SEC(
                                    IF(
                                        ts.arrival_time >= ts.departure_time,
                                        TIMEDIFF(ts.arrival_time, ts.departure_time),
                                        TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                    )
                                ),
                            0)
                        ) * 3600,
                        2
                    ) as speed_kmh
                "),

                // raw departure time (for sorting)
                DB::raw("
                    ADDTIME(
                        ts.departure_time,
                        SEC_TO_TIME(
                            rn_from.distance_from_origin /
                            (
                                r.distance /
                                NULLIF(
                                    TIME_TO_SEC(
                                        IF(
                                            ts.arrival_time >= ts.departure_time,
                                            TIMEDIFF(ts.arrival_time, ts.departure_time),
                                            TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                        )
                                    ),
                                0)
                            )
                        )
                    ) as departure_time_raw
                "),

                // formatted departure
                DB::raw("
                    DATE_FORMAT(
                        ADDTIME(
                            ts.departure_time,
                            SEC_TO_TIME(
                                rn_from.distance_from_origin /
                                (
                                    r.distance /
                                    NULLIF(
                                        TIME_TO_SEC(
                                            IF(
                                                ts.arrival_time >= ts.departure_time,
                                                TIMEDIFF(ts.arrival_time, ts.departure_time),
                                                TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                            )
                                        ),
                                    0)
                                )
                            )
                        ),
                        '%h:%i %p'
                    ) as departure_time
                "),

                // formatted arrival
                DB::raw("
                    DATE_FORMAT(
                        ADDTIME(
                            ts.departure_time,
                            SEC_TO_TIME(
                                rn_to.distance_from_origin /
                                (
                                    r.distance /
                                    NULLIF(
                                        TIME_TO_SEC(
                                            IF(
                                                ts.arrival_time >= ts.departure_time,
                                                TIMEDIFF(ts.arrival_time, ts.departure_time),
                                                TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                            )
                                        ),
                                    0)
                                )
                            )
                        ),
                        '%h:%i %p'
                    ) as arrival_time
                "),

                // time taken to travel between the two stations
                DB::raw("
                    SEC_TO_TIME(
                        (
                            (rn_to.distance_from_origin - rn_from.distance_from_origin) / r.distance
                        ) *
                        TIME_TO_SEC(
                            IF(
                                ts.arrival_time >= ts.departure_time,
                                TIMEDIFF(ts.arrival_time, ts.departure_time),
                                TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                            )
                        )
                    ) as time_taken_raw
                "),

                DB::raw("
                    CONCAT(
                        FLOOR(
                            (
                                (
                                    (rn_to.distance_from_origin - rn_from.distance_from_origin) / r.distance
                                ) *
                                TIME_TO_SEC(
                                    IF(
                                        ts.arrival_time >= ts.departure_time,
                                        TIMEDIFF(ts.arrival_time, ts.departure_time),
                                        TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                    )
                                )
                            ) / 3600
                        ),
                        'h ',
                        FLOOR(
                            MOD(
                                (
                                    (
                                        (rn_to.distance_from_origin - rn_from.distance_from_origin) / r.distance
                                    ) *
                                    TIME_TO_SEC(
                                        IF(
                                            ts.arrival_time >= ts.departure_time,
                                            TIMEDIFF(ts.arrival_time, ts.departure_time),
                                            TIMEDIFF(ADDTIME(ts.arrival_time, '24:00:00'), ts.departure_time)
                                        )
                                    )
                                ),
                                3600
                            ) / 60
                        ),
                        'm'
                    ) as time_taken
                ")
            ])

            ->orderByRaw("CASE WHEN departure_time_raw >= SUBTIME(CURTIME(), '00:15:00') THEN 0 ELSE 1 END") // upcoming first, past later
            ->orderBy('departure_time_raw')
            ->get();
    }
}
