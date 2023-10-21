<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use DB;

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
            'full_name' => 'required|max:50',
            'email' => 'required|email|unique:user,email',
            'dateofbirth' => 'required|date',
            'locale' => 'required|in:en,id',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'country_code' => 'required',
            'phone' => 'required|numeric|digits_between:6,20',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('messages.response_email_already_exist'),
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => __('messages.name'),
            'dateofbirth' => __('messages.dateofbirth'),
            'locale' => __('messages.locale'),
            'password' => __('messages.password'),
            'confirm_password' => __('messages.confirm_password'),
            'country_code' => __('messages.country_code'),
            'phone' => __('messages.phone'),
        ];
    }

    public function validated($key = null, $default = null)
    {
        $passwordHash = bcrypt($this->password);
        $userID = DB::table('user')->max('ID_user') + 1;
        $this->merge(
            [
                'ID_user'       => $userID,
                'password'      => $passwordHash,
                'new_password'  => $passwordHash,
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
