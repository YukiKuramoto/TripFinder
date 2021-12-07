<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if(strpos($this->path(),'post/planedit/') == 0){
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
        ];
    }

    protected function prepareForValidation()
    {
      $newData = $this->all();
      $data = json_decode($newData['request'],true);
      $this->merge([
        'planOutline' => $data['planOutline'],
      ]);
    }
}
