<?php

namespace App\Http\Requests\Task;

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
            'category_id' => 'nullable',
            'level_id' => 'nullable',
            'description' => 'nullable',
            'type_request' => 'integer|in:0,1', // o yoki 1 db xsoblidi (true or false)
            'user_id' => 'required',
            'old_status_id' => 'required',
            'new_status_id' => 'required',
        ];
    }
}
