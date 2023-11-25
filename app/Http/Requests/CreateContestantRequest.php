<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Event;
use App\Models\Competition;
use App\Models\User;
use App\Models\UserOrigin;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator as FacadesValidator;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Lang;

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
                        ->where('start_registration', '<=', now())
                        ->where('end_registration', '>=', now())
                        ->exists();
        }, __('messages.response_event_date_invalid'));

        FacadesValidator::extend('check_public_event', function ($attribute, $value, $parameters, $validator) {
            return Event::where('ID_event', $value)
                        // ->where('registration_type', 'open')
                        ->exists();
        }, __('messages.response_event_status_is_private'));

        FacadesValidator::extend('user_id_not_registered', function ($attribute, $value, $parameters, $validator) {
            $event = Event::where('ID_event', $validator->getData()['ID_event'])->first();
            $competitions = Competition::where('ID_event', $event->ID_event)
                ->leftJoin('contestant_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
                ->where('contestant_list.ID_user', $value)
                ->select('contestant_list.ID_user', 'competition_list.ID_competition')
                ->get();
            foreach($competitions as $competition) {
                if(in_array($competition->ID_competition, $validator->getData()['ID_competition'])) {
                    return false;
                }
            }
            return true;
        }, __('messages.response_competition_already_registered'));

        FacadesValidator::extend('max_join_competition', function ($attribute, $value, $parameters, $validator) {
            $event = Event::where('ID_event', $validator->getData()['ID_event'])->first();
            $competitions = Competition::where('ID_event', $event->ID_event)
                ->leftJoin('contestant_list', 'contestant_list.ID_competition', 'competition_list.ID_competition')
                ->where('contestant_list.ID_user', $value)
                ->select('contestant_list.ID_user', 'competition_list.ID_competition')
                ->get();
            if($competitions->count() + count($validator->getData()['ID_competition']) > $event->max_join_competition) {
                $message = Lang::get('messages.response_max_join_competition', [
                    'number1' => $event->max_join_competition,
                    'number2' => count($validator->getData()['ID_competition'])
                ]);
                $validator->setCustomMessages(['max_join_competition' => $message]);
                return false;
            }
            return true;
        });

        FacadesValidator::extend('user_profile_is_complete', function ($attribute, $value, $parameters, $validator) {
            $user = User::where('ID_user', $value)->first();
            if($user->full_name == null || $user->nick_name == null || $user->dateofbirth == null || $user->phone == null) {
                return false;
            }
            return true;
        }, __('messages.response_user_profile_is_not_complete'));
        
        // check user origin, if country_id = ID, then indo_province_id and indo_city_id must be filled. Else, state_id and city_id must be filled
        FacadesValidator::extend('user_origin_is_complete', function ($attribute, $value, $parameters, $validator) {
            $userOrigin = UserOrigin::where('user_id', $value)->first();
            if($userOrigin->country_id == 'ID') {
                if($userOrigin->indo_province_id == null || $userOrigin->indo_city_id == null) {
                    return false;
                }
            } else {
                if($userOrigin->state_id == null || $userOrigin->city_id == null) {
                    return false;
                }
            }
            return true;
        }, __('messages.response_user_origin_is_not_complete'));


        return [
            'ID_event' => [
                'required',
                'exists:event_list,ID_event',
                'valid_event_id',
                'check_public_event'
            ],
            'ID_competition.*' => 'required|exists:competition_list,ID_competition',
            'ID_user' => [
                'required',
                'exists:user,ID_user',
                'user_profile_is_complete',
                'user_origin_is_complete',
                'user_id_not_registered',
                'max_join_competition'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'ID_event.valid_event_id' => __('messages.response_event_date_invalid')
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
