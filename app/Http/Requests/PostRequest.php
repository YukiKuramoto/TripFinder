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
          'planOutline.plan_title' => 'required',
          'planOutline.main_transportation' => 'required',
          'planOutline.plan_information' => 'required',
          'dayInfo.*.spotInfo.*.spot_address' => 'required',
          'dayInfo.*.spotInfo.*.spot_duration' => 'required',
          'dayInfo.*.spotInfo.*.spot_title' => 'required',
          'dayInfo.*.spotInfo.*.spot_information' => 'required',
          'dayInfo.*.spotInfo.*.spot_image' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
      $newData = $this->all();
      $data = json_decode($newData['request'],true);
      $this->merge([
        'planOutline' => $data['planOutline'],
        'dayInfo' => $data['dayInfo'],
      ]);
    }
}
