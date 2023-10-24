<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UserUpdateRequest extends FormRequest
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
            // 'email' => 'required|email|unique:user,email',
            'dateofbirth' => 'required|date',
            'locale' => 'required|in:en,id',
            'country_code' => 'required',
            'phone' => 'required|numeric|digits_between:6,20',
            'instagram' => 'nullable|max:30',
            'country' => 'required',
            'states' => 'required_unless:country,ID',
            'city' => 'required_unless:country,ID',
            'province' => 'required_if:country,ID',
            'indoCity' => 'required_if:country,ID',
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => __('messages.name'),
            'dateofbirth' => __('messages.dateofbirth'),
            'locale' => __('messages.locale'),
            'country_code' => __('messages.country_code'),
            'phone' => __('messages.phone'),
            'country' => __('messages.country'),
            'states' => __('messages.states'),
            'city' => __('messages.city'),
            'province' => __('messages.province'),
            'indoCity' => __('messages.city'),
        ];
    }

    // custom message on states required_unless
    public function messages(): array
    {
        return [
            'states.required_unless' => __('messages.states').' '.__('messages.required'),
            'city.required_unless' => __('messages.city').' '.__('messages.required'),
            'province.required_if' => __('messages.province').' '.__('messages.required'),
            'indoCity.required_if' => __('messages.city').' '.__('messages.required'),
        ];
    }

    public function validated($key = null, $default = null)
    {
        $getIDuserByEmail = \App\Models\User::where('email', $this->email)->first();
        // get validated data
        $validated = $this->all();

        $user = [
            'ID_user' => $getIDuserByEmail->ID_user,
            "full_name" => $validated['full_name'],
            "nick_name" => $validated['nick_name'],
            "email" => $validated['email'],
            "dateofbirth" => $validated['dateofbirth'],
            "locale" => $validated['locale'],
            "stance" => $this->stance == 'regular' ? 1 : 2,
            "country_code" => $validated['country_code'],
            "phone" => $validated['phone'],
            "country_code_id" => $validated['country_code_id'],
            "instagram" => $validated['instagram'],
        ];
        
        $origin = [
            "user_id" => $getIDuserByEmail->ID_user,
            "country_id" => $this->country,
            "country_name" => $this->country_name,
            "state_id" => $this->states,
            "state_name" => $this->state_name,
            "city_id" => $this->city,
            "city_name" => $this->city_name,
            "indo_province_id" => $this->province,
            "indo_province_name" => $this->indo_province_name,
            "indo_city_id" => $this->indoCity,
            "indo_city_name" => $this->indo_city_name,
        ];

        
        $return = [
                'user' => $user,
                'user_origin' => $origin
            ];
        return $return;
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
