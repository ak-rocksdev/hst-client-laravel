<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserPasswordUpdateRequest extends FormRequest
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
        $user = Auth::user();
        return [
            'old_password' => [
                'required',
                function($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->new_password)) {
                        $fail(__('validation.old_password_is_incorrect'));
                    }
                }
            ],
            'new_password' => 'required|min:6|different:old_password',
            'new_password_confirmation' => 'required|same:new_password',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['new_password'] = Hash::make($validated['new_password']);
        return $validated;
    }

    public function attributes(): array
    {
        return [
            'old_password' => __('messages.old_password'),
            'new_password' => __('messages.new_password'),
            'new_password_confirmation' => __('messages.confirm_new_password'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json([
                'status' => 'Validation failed',
                'messages' => $errors,
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
