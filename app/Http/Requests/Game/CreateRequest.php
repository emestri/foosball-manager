<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'home_team_id' => 'required|integer|exists:teams,id',
            'guest_team_id' => 'required|integer|exists:teams,id',
            'location_id' => 'required|integer|exists:locations,id',
            'mode' => 'required|string|in:Single,BestOfThree,BestOfFive',
        ];
    }
}
