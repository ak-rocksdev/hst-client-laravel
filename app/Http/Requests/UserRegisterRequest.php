<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UserRegisterRequest extends FormRequest
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
        return [
            'full_name' => 'required',
            'email' => 'required|email|unique:user,email',
            'dateofbirth' => 'required|date',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'country_code' => 'required',
            'phone' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('messages.response_email_already_exist'),
        ];
    }

    public function validated($key = null, $default = null)
    {
        $passwordHash = bcrypt($this->password);
        $this->merge(
            [
                'password'      => $passwordHash,
                'phone'         => $this->country_code . $this->phone,
                'password_version' => 1,
                'flag'          => 0,
            ]
        );
        return $this->all();
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
