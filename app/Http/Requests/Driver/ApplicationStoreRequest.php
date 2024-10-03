<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "full_name"=> ["required"],
            "track_num"=> ["required"],
            "eastern_time"=> ["required"],
            "phone"=> ["nullable"],
            "comment"=> ["nullable"]
        ];
    }
}
