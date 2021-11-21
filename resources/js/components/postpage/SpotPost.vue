<template>
    <div class="item">
      <div class="content-title">Day{{ spot.spot_day+1 }} - Spot{{ spotIndex + 1 }}</div>
        <div class="title-area">
          <div class="error-mark">*</div>
          スポットタイトル：
          <input type="text"
          class="user-input"
          v-model="spot.spot_title">
        </div>
        <div class="spot-detail-wrapper">
          <div class="spot-detail-area">
            <div class="spot-stay-area">
              <div class="spot-area-title">
                <div class="error-mark">*</div>
                滞在時間：
              </div>
              <select
              v-model="spot.spot_duration"
              class="user-input">
                <option value="0.5時間">0.5時間</option>
                <option value="1時間">1時間</option>
                <option value="1.5時間">1.5時間</option>
                <option value="2時間">2時間</option>
                <option value="2時間以上">2時間以上</option>
              </select>
            </div>
            <div class="spot-address-area">
              <div class="spot-area-title">
                <div class="error-mark">*</div>
                所在地：
              </div>
              <input type="text"
              class="user-input"
              v-model="spot.spot_address">
            </div>
            <div class="spot-tag-area">
              <div>
                ハッシュタグ
              </div>
              <input
              :id="'id' + spot.spot_count"
              class="hash-tag user-input"
              v-model="spot.spot_tag">
              <div class="tagsinput">
                <div class="tagsinput-child">
                  <input class="tagsinput-form" @blur="registarSpotTag()">
                </div>
                <div class="tags_clear">
                </div>
              </div>
            </div>
          </div>
          <div class="spot-image-area">
            <div class="spot-image-select">
              <div class="error-mark">*</div>
              写真投稿：
              <label :for="'file-select' + spot.spot_day + '_' + spotIndex">
                <span class="browse_btn">Select</span>
                <input type="file"
                class="file-select"
                :id="'file-select' + spot.spot_day + '_' + spotIndex"
                @input="SelectImage(spot.spot_count, $event.currentTarget)"
                multiple="multiple">
                {{ spot.spot_image.length }}枚選択
              </label>
            </div>
            <div class="spot-image-view">
              <canvas :id="'canvas' + spot.spot_count" style="display: none"></canvas>
              <img v-bind:id="'preview-image' + spot.spot_count">
            </div>
          </div>
        </div>
        <div class="spot-information-area">
          <div>
            <div class="error-mark">*</div>
            SPOT INFORMATION
          </div>
          <textarea class="spot-information-textarea user-input"
          v-model="spot.spot_information"
          placeholder="スポットの情報を投稿しよう！" >
          </textarea>
        </div>
        <div class="spot-information-area">
          <div>
            <div class="error-mark">*</div>
            SPOT INFORMATION
          </div>
          <textarea class="spot-information-textarea user-input"
          v-model="spot.spot_information"
          placeholder="スポットの情報を投稿しよう！" >
          </textarea>
        </div>
    </div>
</template>

<script>
  export default{
    props:[
      'spot',
      'spotIndex',
      'errors',
    ],
    created: function(){
      this.spot.spot_duration = "0.5時間"
    },
    beforeUpdate: function(){
      $('.hash-tag').tagsInput({width:'100%'});
    },
    updated: function(){
      console.log("updated");
      this.$emit('spotUpdate', this.spot, this.spot.spot_count);
    },
    methods: {
      SelectImage: function(Key, Target){

        this.spot.spot_image = [];
        $('#preview-image' + this.spot.spot_count).attr('src', '');

        for(let file of Target.files){

          if (!file.type.match(/^image\/(png|jpeg|gif)$/)){
            alert('認められていないファイル形式です')
            return;
          }

          let vueComponents = this;
          var reader = new FileReader();
          reader.readAsDataURL(file);

          reader.onload = (evt) => {

            var image = new Image();
            image.onload = () => {

              // 最大幅
              const maxWidth = 900;
              // 最大高さ
              const maxHeight = 700;
              // 変換後幅
              var convertedWidth;
              // 変換後高さ
              var convertedHeight;
              // canvas要素取得
              var canvas = $('#canvas' + Key);
              // 描画用オブジェクト
              var ctx = canvas[0].getContext('2d');

              // 縦長画像でサイズオーバー
              if(image.height > image.width && image.height > maxHeight){
                convertedWidth = Math.round(image.width * maxHeight / image.height);
                convertedHeight = maxHeight;
                // 横長画像でサイズオーバー
              }else if(image.width > image.height && image.width > maxWidth){
                convertedWidth = maxWidth;
                convertedHeight = Math.round(image.height * maxWidth / image.width);
                // サイズオーバーなし
              }else{
                convertedWidth = image.width;
                convertedHeight = image.height;
              }

              // 描画用に高さと幅をセット
              $('#canvas' + Key).attr('height', convertedHeight);
              $('#canvas' + Key).attr('width', convertedWidth);
              // 描画実行
              ctx.drawImage(image, 0, 0, image.width, image.height, 0, 0, convertedWidth, convertedHeight); //canvasに画像を転写

              // 描画対象をファイル変換
              ctx.canvas.toBlob((blob) => {
                const imageFile = new File([blob], file.name, {
                  type: file.type,
                });
                console.log(imageFile.size);
                // 圧縮後ファイルをVueComponentsにセット
                vueComponents.spot.spot_image.push(imageFile);
              }, file.type, 1);
            }
            // 画像プレビュー用にsrcに読み込み後画像をセット
            $('#preview-image' + vueComponents.spot.spot_count).attr('src', evt.target.result);
            image.src = evt.target.result;
          }
        }
      },
      registarSpotTag: function(){
        this.spot.spot_tag = document.getElementById(`id${this.spot.spot_count}`).value;
        console.log(this.spot.spot_tag);
        console.log('OK');
      },
    }
  }
</script>

<style media="screen">

  .file-select {
    display: none;
  }

  .browse_btn {
    background-color: #d3d3d3;
    padding: 3px 15px;
    border-radius: 8px;
    font-weight: bold;
  }

  .browse_btn:hover {
    cursor : pointer;
  }
</style>
