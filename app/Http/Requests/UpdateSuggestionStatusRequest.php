<?php
namespace App\Http\Requests;

use App\Enums\SuggestionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSuggestionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'      => ['required', 'string', Rule::in(array_column(SuggestionStatus::cases(), 'value'))],
            'review_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
