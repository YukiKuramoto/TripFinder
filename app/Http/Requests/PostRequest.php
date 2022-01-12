<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
  /*
  |--------------------------------------------------------------------------
  | PostRequest FormRequest
  |--------------------------------------------------------------------------
  |
  | プランデータ新規投稿のPOSTリクエストに対し、
  | JSON形式のHttpリクエストデータのバリデーション、JSONディコード処理実行FormRequest
  |
  */

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


    /**
     * JSON形式リクエストをJSONからディコード処理し、以下形式に変換
     * $data = [
     *     'request' => JSON形式 Data,
     *     'planOutline' => JSONディコード後 Data,
     *     'dayInfo' => JSONディコード後 Data,
     *     '××_××_××' => 画像データ]
     *
     * @return void
     */
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
