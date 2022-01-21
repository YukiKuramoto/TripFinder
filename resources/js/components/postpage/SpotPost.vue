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
                @input="SelectImage(spot.spot_count, $event.currentTarget)">
                {{ image_count }}枚
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
import heic2any from "heic2any";

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
      if(this.type=='post'){
        this.spot.spot_duration = "0.5時間"
      }else if(this.type == 'spotedit'){
        this.setLinks();
      }
    },
    beforeUpdate: function(){
      // プラスボタンを押下し、スポット要素足した際にテキストボックス非表示
      $('.hash-tag').tagsInput({width:'100%'});
    },
    updated: function(){
      this.$emit('spotUpdate', this.spot, this.spot.spot_count);
    },
    methods: {
      ClearImage: function(){
        this.spot.spot_image = [];
        this.image_count = this.spot.spot_image.length;
        $('#preview-image' + this.spot.spot_count).attr('src', "");
      },
      SelectImage: function(Key, Target){

        if (this.spot.spot_image.length === 3){
          alert('登録ファイルの上限数は3つまでです');
          return;
        }

        for(let file of Target.files){

          if(file.type.match(/^image\/(heic)$/)){

            const blob = file;

            heic2any({
              blob,
              toType: "image/jpeg",
              quality: 0.5
            }).then(resultBlob => {

              const reader = new FileReader();

              reader.readAsDataURL(resultBlob);
              let vueComponents = this;

              reader.onload = (evt) => {

                this.remakeImage(evt, vueComponents, file);

              }
            });
          }else {
            var reader = new FileReader();
            reader.readAsDataURL(file);

            let vueComponents = this;
            reader.onload = (evt) => {

              this.remakeImage(evt, vueComponents, file);

            }
          }
        }
      },
      remakeImage: function(evt, vueComponents, file){

          let Key = vueComponents.spot.spot_count;

          var image = new Image();
          image.onload = () => {

            // 最大幅
            const maxWidth = 700;
            // 最大高さ
            const maxHeight = 500;
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
        // }
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

<style media="screen" lang="scss" scoped>

input, select, textarea{
  padding-left: 10px;
}

select {
  width: 100px;
}

.item {
  min-width: 770px;
  width: 100%;
  height: 100%;
  padding: 0 5%;
  background-color: #fff;
  box-shadow: 0 0 8px 0 rgb(0 0 0 / 15%);

  .content-title {
    text-align: center;
    padding: 20px 0;
    font-size: 30px;
  }

  .title-area {
    margin-top: 30px;

    input {
      width: calc(100% - 130px);
    }
  }

  .spot-detail-wrapper {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;

    .spot-detail-area {
      width: 65%;

      .spot-address-area {
        margin-top: 30px;

        input {
          width: calc(100% - 90px);
        }
      }

      .spot-tag-area {
        margin-top: 30px;

        textarea {
          width: 100%;
          height: 80px;
        }
      }

      .spot-area-title {
        display: inline-block;
        width: 90px;
      }

    }

    .spot-image-area {
      width: 30%;
      height: 200px;

      .spot-image-view {
        height: 100%;

        img {
          border-radius: 20px;
          margin-top: 10px;
          object-fit: cover;
          max-width: 100%;
          max-height: 100%;
        }
      }
    }

  }

  .spot-information-area {
    margin-top: 30px;

    .spot-information-textarea {
      height: 100px;
      margin-bottom: 30px;
      width: 100%;
    }
  }

  .user-input {
    border:1px solid #999;
  }

  .error-mark {
    text-decoration: none;
    display: inline;
    color: #FF3333;
  }

  .link-form-area {
    padding-bottom: 50px;

    p {
      margin: 0;
      padding: 0;
      font-size: 15px;
    }

    input {
      width: 90%;
    }

    textarea {
      width: 100%;
    }

    .form-row {
      margin: 0 0 -1px 0;
      padding: 8px;
      border: 1px solid rgb(85,85,85, 0.5);
    }

    .form-header {
      background-color: #EEEEEE;
    }
  }

}


// ここから手直し必要
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
