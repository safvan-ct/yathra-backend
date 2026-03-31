<?php
namespace App\Services;

use App\Models\Operator;
use App\Repositories\Interfaces\BusRepositoryInterface;
use Illuminate\Validation\ValidationException;

class BusService
{
    public const INDIAN_BUS_NUMBER_REGEX = '/^[A-Z]{2}\s?\d{1,2}\s?[A-Z]{0,2}\s?\d{3,4}$/i';

    public function __construct(
        protected BusRepositoryInterface $busRepository
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->busRepository->paginate($filters, $perPage);
    }

    public function get(int $id)
    {
        return $this->busRepository->find($id);
    }

    public function create(array $data)
    {
        $this->assertOperatorActive($data['operator_id']);

        $data['bus_number_code'] = $this->normalizeBusNumber($data['bus_number']);
        $data['bus_number']      = $this->formatBusNumber($data['bus_number_code']);

        $this->assertSeatsPositive($data['total_seats']);

        return $this->busRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (isset($data['operator_id'])) {
            $this->assertOperatorActive($data['operator_id']);
        }

        if (isset($data['bus_number'])) {
            $data['bus_number_code'] = $this->normalizeBusNumber($data['bus_number']);
            $data['bus_number']      = $this->formatBusNumber($data['bus_number_code']);
        }

        if (isset($data['total_seats'])) {
            $this->assertSeatsPositive($data['total_seats']);
        }

        return $this->busRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->busRepository->delete($id);
    }

    protected function assertOperatorActive(int $operatorId): void
    {
        $operator = Operator::find($operatorId);
        if (! $operator) {
            throw ValidationException::withMessages(['operator_id' => ['Operator not found.']]);
        }

        if (! $operator->is_active) {
            throw ValidationException::withMessages(['operator_id' => ['Cannot create/update bus for an inactive operator.']]);
        }
    }

    public function normalizeBusNumber(string $busNumber): string
    {
        $clean = strtoupper($busNumber);
        $clean = preg_replace('/[^A-Z0-9]/', ' ', $clean);
        $clean = preg_replace('/\s+/', ' ', trim($clean));

        if (! preg_match(self::INDIAN_BUS_NUMBER_REGEX, $clean)) {
            throw ValidationException::withMessages(['bus_number' => ['bus_number format is invalid.']]);
        }

        return str_replace(' ', '', $clean);
    }

    public function formatBusNumber(string $busNumber): string
    {
        $normalized = $this->normalizeBusNumber($busNumber);
        preg_match('/^([A-Z]{2})\s?(\d{1,2})\s?([A-Z]{0,2})\s?(\d{3,4})$/', $normalized, $matches);

        $number = sprintf('%s %02d %s %s', $matches[1], (int) $matches[2], $matches[3] ?? '', $matches[4]);
        return trim(preg_replace('/\s+/', ' ', $number));
    }

    protected function assertSeatsPositive(int $totalSeats): void
    {
        if ($totalSeats <= 0) {
            throw ValidationException::withMessages(['total_seats' => ['total_seats must be greater than 0.']]);
        }
    }
}
