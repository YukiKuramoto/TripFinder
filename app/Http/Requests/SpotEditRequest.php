<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpotEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      // if($this->path() == 'post/spotedit/*'){
      //   return true;
      // }else{
      //   return false;
      // }
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
          // 'dayInfo.*.spotInfo.*.spot_address' => 'required',
          // 'dayInfo.*.spotInfo.*.spot_duration' => 'required',
          // 'dayInfo.*.spotInfo.*.spot_title' => 'required',
          // 'dayInfo.*.spotInfo.*.spot_information' => 'required',
          // 'dayInfo.*.spotInfo.*.spot_image' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
      $newData = $this->all();
      // dd($newData);
      $data = json_decode($newData['request'],true);
      // dd($data);
      $this->merge([
        'dayInfo' => $data['dayInfo'],
      ]);
    }
}
