<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanEditRequest extends FormRequest
{
  /*
  |--------------------------------------------------------------------------
  | PlanEditRequest FormRequest
  |--------------------------------------------------------------------------
  |
  | プランアウトライン更新のPOSTリクエストに対し
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


    /**
     * JSON形式リクエストをJSONからディコード処理し、以下形式に変換
     * $data = [
     *     'request' => JSON形式 Data,
     *     'planOutline' => JSONディコード後 Data]
     *
     * @return void
     */
    protected function prepareForValidation()
    {
      $newData = $this->all();

      // HttpリクエストをPHPの連想配列の形にディコード
      $data = json_decode($newData['request'],true);
      // ディコード後情報を配列にセット
      $this->merge([
        'planOutline' => $data['planOutline'],
      ]);
    }
}
