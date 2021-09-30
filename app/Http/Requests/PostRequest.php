<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->path() == 'post/create'){
        return true;
      }else{
        return false;
      }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'plan.0.plan_title' => 'required',
            'plan.0.main_transportation' => 'required',
            'plan.0.plan_information' => 'required',
            'spot.*.spot_title' => 'required',
            'spot.*.spot_duration' => 'required',
            'spot.*.spot_address' => 'required',
            'spot.*.spot_image' => 'required',
            'spot.*.spot_information' => 'required',
        ];
    }
}
