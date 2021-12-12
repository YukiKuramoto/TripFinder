<template>
    <div class="item">
      <div v-if="type == 'post'" class="content-title">
        Day{{ spot.spot_day+1 }} - Spot{{ spotIndex + 1 }}
      </div>
      <div v-if="type == 'spotedit'" class="content-title">
        Spot編集
      </div>
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
              写真：
              <label :for="'file-select' + spot.spot_day + '_' + spotIndex">
                <input type="button"
                class="browse_btn"
                :id="'file-clear' + spot.spot_day + '_' + spotIndex"
                @click="ClearImage"
                value="Clear">
                <span class="browse_btn">Select</span>
                <input type="file"
                class="file-select"
                :id="'file-select' + spot.spot_day + '_' + spotIndex"
                @input="SelectImage(spot.spot_count, $event.currentTarget)"
                multiple="multiple">
                {{ image_count }}枚選択
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
        <div class="spot-link-area">
          <div>
            関連サイトリンク
          </div>
          <div class="link-form-area">
            <section v-for="(link, link_index) in spot.spot_link">
              <div class="link-title-header form-row form-header spot-accordion-title">
                <a href="#!" v-on:click.prevent="showLinkInput">リンク{{ link_index + 1 }}</a>
              </div>
              <div class="spot-accordion-content">
                <div class="link-title form-row form-content">
                  <span>Title：</span><input v-model="link.link_title" type="text">
                </div>
                <div class="link-title form-row form-content">
                  <span>URL：</span><input v-model="link.link_url" type="text">
                </div>
              </div>
            </section>
          </div>
        </div>
    </div>
</template>

<script>
  export default{
    props:[
      'spot',
      'spotIndex',
      'errors',
      'type',
    ],
    data() {
      return {
        image_count: 0,
      }
    },
    created: function(){
      console.log(this.type);
      if(this.type=='post'){
        this.spot.spot_duration = "0.5時間"
      }else if(this.type == 'spotedit'){
        this.setLinks();
      }
    },
    beforeUpdate: function(){
      $('.hash-tag').tagsInput({width:'100%'});
    },
    updated: function(){
      console.log("updated");
      this.$emit('spotUpdate', this.spot, this.spot.spot_count);
    },
    methods: {
      ClearImage: function(){
        console.log("clear");
        console.log(this.spot);
        this.spot.spot_image = [];
        this.image_count = this.spot.spot_image.length;
        $('#preview-image' + this.spot.spot_count).attr('src', "");
      },
      SelectImage: function(Key, Target){

        console.log(this.spot);
        // this.spot.spot_image = [];
        if (this.spot.spot_image.length === 3){
          alert('登録ファイルの上限数は3つまでです');
          return;
        }
        // $('#preview-image' + this.spot.spot_count).attr('src', '');

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
                vueComponents.image_count = vueComponents.spot.spot_image.length;
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
      },
      setLinks: function(){
        let count = this.spot.spot_link.length;

        if(count != 3){
          for(let i = 0; i <= 2; i++){
            if(i>count-1){
              this.spot.spot_link.push({});
            }
          }
        }

        console.log(count);
      },
      showLinkInput: function(e) {
        e.preventDefault();
        const content = $(e.target)
        .closest('section')
        .find('.spot-accordion-content');

        if (!content.is(':visible')) {
          $('.spot-accordion-content:visible').slideUp();
          content.slideDown();
        }
      },
    }
  }
</script>

<style media="screen" lang="scss">

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


  /* アコーディオンのタイトル部分 */
  .spot-accordion-title {
    margin: 0;
    border: 1px solid #ccc;
    background-color: #f0f0f0;
    font-size: 1rem;
  }
  .spot-accordion-title a {
    display: block;
    color: #3F4548;
    text-decoration: none;
  }

  .spot-accordion-title:hover {
    background-color: rgba(213, 218, 229, 0.9);
    // transition: 0.4s;
  }

  /* アコーディオンのコンテンツ部分 */
  .spot-accordion-content {
    display: none;
    border: 1px solid #ccc;
    cursor: pointer;

    .spot-accordion-content-area {
      display: flex;
      justify-content: space-between;
    }
  }

  /* accordion-content-activeクラスが付いているものは初期状態で表示しておく */
  .spot-accordion-content-active {
    display: block;
  }

</style>
