<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $availableComboIds = collect(config('combos.items'))->pluck('id')->all();

        return [
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,id',
            'combos' => 'sometimes|array',
            'combos.*.id' => ['required_with:combos', 'string', Rule::in($availableComboIds)],
            'combos.*.quantity' => 'required_with:combos|integer|min:1',
        ];
    }
}