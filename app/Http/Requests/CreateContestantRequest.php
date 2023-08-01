<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Event;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator as FacadesValidator;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CreateContestantRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        FacadesValidator::extend('valid_event_id', function ($attribute, $value, $parameters, $validator) {
            return Event::where('ID_event', $value)
                        ->where('registration_type', 'open')
                        // ->where('start_registration', '<=', now())
                        // ->where('end_registration', '>=', now())
                        ->exists();
        }, __('messages.response_event_date_invalid'));
        return [
            'ID_event' => [
                'required',
                'exists:event_list,ID_event',
                'valid_event_id',
            ],
            'ID_competition.*' => 'required|exists:competition_list,ID_competition',
            'ID_user' => 'required|exists:user,ID_user',
        ];
    }

    public function messages(): array
    {
        return [
            'ID_event.valid_event_id' => __('messages.response_event_date_invalid'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json([
                'status' => 'Validation failed',
                'messages' => $errors,
                'code' => 422
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
