<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpotEditRequest extends FormRequest
{
  /*
  |--------------------------------------------------------------------------
  | SpotEditRequest FormRequest
  |--------------------------------------------------------------------------
  |
  | スポットデータ更新のPOSTリクエストに対し、
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
      if(strpos($this->path(),'post/spotedit/') == 0){
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
          'dayInfo.spot_address' => 'required',
          'dayInfo.spot_duration' => 'required',
          'dayInfo.spot_title' => 'required',
          'dayInfo.spot_information' => 'required',
          'dayInfo.spot_image' => 'required',
        ];
    }


    /**
     * JSON形式リクエストをJSONからディコード処理し、以下形式に変換
     * $data = [
     *     'request' => JSON形式 Data,
     *     'dayInfo' => JSONディコード後 Data,
     *     '××_××_××' => 画像データ]
     *
     * @return void
     */
    protected function prepareForValidation()
    {
      $newData = $this->all();
      $data = json_decode($newData['request'],true);
      // dd($data);
      $this->merge([
        'dayInfo' => $data['dayInfo'],
      ]);
    }
}
