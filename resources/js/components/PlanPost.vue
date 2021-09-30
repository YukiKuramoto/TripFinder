<template>
  <div id="post-body">
      <div id="post-menu">
          <div>
              <div id="button-area">
                  <button type="submit" id="dayadd-button" class="btn btn-secondary btn-submit" v-on:click="addDay">Add Day</button>
                  <button type="submit" id="daydelete-button" class="btn btn-secondary btn-submit" v-on:click="deleteDay">Delete Day</button>
              </div>
              <section>
                  <h2 class="accordion-title"><a href="#" v-on:click.prevent="showSpot">PlanOutline</a></h2>
                  <div class="accordion-content accordion-content-active" v-on:click="carouselMove(-1)">PlanOutline</div>
              </section>
              <section v-for="day in dayInfo">
                  <h2 class="accordion-title"><a href="#!" v-on:click.prevent="showSpot">Day{{ day.dayKey + 1 }}</a></h2>
                  <div v-for="spot in day.spotInfo">
                    <div class="accordion-content" v-bind:style="spot.spotDisplay" v-on:click.prevent="carouselMove(spot.spotKey)">
                      <div class="accordion-content-area">
                        <div class="spot-area">
                          Spot{{ spot.spotKey + 1 }}
                        </div>
                        <div class="icon-area">
                          <i class="bi bi-plus-square plus-button" v-on:click.prevent="addSpot(day.dayKey, spot.spotKey, 'block')"></i>
                          <i class="bi bi-dash-square minus-button" v-on:click.prevent="deleteSpot(day.dayKey, spot.spotKey)"></i>
                        </div>
                      </div>
                    </div>
                  </div>
              </section>
          </div>
      </div>
      <form id="post-form" v-bind:action="'/post/' + type" method="post" enctype="multipart/form-data">
          <div>
            <div v-if="ErrorExist" id="error-msg">
              ＊は入力必須項目です
            </div>
            <div class="carousel">
              <div class="list" v-bind:style="_listStyle">
                <div class="list__item">
                  <div class="item">
                    <div class="content-title">PlanOutline</div>
                      <div class="title-area">
                        <div class="error-mark">{{ planOutline.mark_title }}</div>
                        プランタイトル：
                        <input type="text"
                        name="plan[0][plan_title]"
                        class="user-input"
                        v-on:input="planOutline.planTitle = $event.target.value"
                        v-bind:value="planOutline.planTitle">
                      </div>
                      <div class="transportation-label">
                        <div class="title-area">
                          主要交通手段　：
                          <select name="plan[0][main_transportation]" class="user-input"
                          v-on:input="planOutline.mainTransportation = $event.target.value"
                          v-bind:value="planOutline.mainTransportation">
                            <option value="車">車</option>
                            <option value="電車">電車</option>
                            <option value="バス">バス</option>
                            <option value="徒歩">徒歩</option>
                            <option value="その他">その他</option>
                          </select>
                        </div>
                      </div>

                      <div class="plan-tag-area">
                        <div class="plan-textarea-title">
                          ハッシュタグ
                        </div>
                        <input
                        id="plantag-input"
                        class="hash-tag user-input"
                        name="plan[0][plan_tag]"
                        v-on:input="planOutline.hashTag = $event.target.value"
                        v-bind:value="planOutline.hashTag">
                        <div class="tagsinput">
                          <div class="tagsinput-child">
                            <input class="tagsinput-form" v-on:blur="registarPlanTag()">
                          </div>
                          <div class="tags_clear">
                          </div>
                        </div>
                      </div>
                      <div class="plan-information-area">
                        <div class="plan-textarea-title">
                          <div class="error-mark">{{ planOutline.mark_info }}</div>
                          PLAN INFORMATION
                        </div>
                        <textarea class="plan-information-textarea user-input"
                        name="plan[0][plan_information]"
                        v-on:input="planOutline.information = $event.target.value"
                        v-bind:value="planOutline.information"
                        placeholder="プランの見所を投稿しよう！">
                        </textarea>
                      </div>
                  </div>
                </div>
                <template class="spot-content" v-for="content in contentsInfo">
                  <div class="list__item">
                    <div class="item">
                      <div class="content-title">Spot{{ content.contentsKey + 1 }}</div>
                        <div class="title-area">
                          <div class="error-mark">{{ content.mark_title }}</div>
                          スポットタイトル：
                          <input type="text"
                          class="user-input"
                          v-on:input="content.contentsTitle = $event.target.value"
                          v-bind:name="'spot[' + content.contentsKey + '][spot_title]'"
                          v-bind:value="content.contentsTitle">
                        </div>
                        <div class="spot-detail-wrapper">
                          <div class="spot-detail-area">
                            <div class="spot-stay-area">
                              <div class="spot-area-title">
                                <div class="error-mark">{{ content.mark_duration }}</div>
                                滞在時間：
                              </div>
                              <select
                              v-bind:value="content.contentsDuration"
                              v-on:input="content.contentsDuration = $event.target.value"
                              v-bind:name="'spot[' + content.contentsKey + '][spot_duration]'"
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
                                <div class="error-mark">{{ content.mark_address }}</div>
                                所在地：
                              </div>
                              <input type="text"
                              class="user-input"
                              v-on:input="content.contentsAddress = $event.target.value"
                              v-bind:name="'spot[' + content.contentsKey + '][spot_address]'"
                              v-bind:value="content.contentsAddress">
                            </div>
                            <div class="spot-tag-area">
                              <div>
                                ハッシュタグ
                              </div>
                              <input
                              v-bind:id="'id' + content.contentsKey"
                              class="hash-tag user-input"
                              v-on:change="content.contentsTag = $event.target.value"
                              v-bind:name="'spot[' + content.contentsKey + '][spot_tag]'"
                              v-bind:value="content.contentsTag">
                              <div class="tagsinput">
                                <div class="tagsinput-child">
                                  <input class="tagsinput-form" v-on:blur="registarSpotTag(content.contentsKey)">
                                </div>
                                <div class="tags_clear">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="spot-image-area">
                            <div class="spot-image-select">
                              <div class="error-mark">{{ content.mark_image }}</div>
                              写真を投稿：
                              <input type="file"
                              class="file-select"
                              v-on:input="SelectImage(content.contentsKey, $event.currentTarget)"
                              v-bind:name="'spot[' + content.contentsKey + '][spot_image][]'"
                              multiple="multiple">
                            </div>
                            <div class="spot-image-view">
                              <!-- <canvas v-bind:id="'canvas' + content.contentsKey"></canvas> -->
                              <img v-bind:src="content.spotImage[0]">
                            </div>
                          </div>
                        </div>
                        <div class="spot-information-area">
                          <div>
                            <div class="error-mark">{{ content.mark_info }}</div>
                            SPOT INFORMATION
                          </div>
                          <textarea class="spot-information-textarea user-input"
                          v-on:input="content.contentsInfo = $event.target.value"
                          v-bind:name="'spot[' + content.contentsKey + '][spot_information]'"
                          v-bind:value="content.contentsInfo"
                          placeholder="スポットの情報を投稿しよう！" >
                          </textarea>
                          <input v-bind:value="content.daykey + 1" v-bind:name="'spot[' + content.contentsKey + '][spot_day]'" style="display: none;">
                        </div>
                    </div>
                  </div>
                </template>
              </div>
              <div class="submit-button-area">
                <input type="hidden" name="_token" :value="csrf">
                <input type="submit" class="btn btn-success post-submit-button" value="投稿する">
              </div>
            </div>
          </div>
      </form>
  </div>
</template>

<script>
  export default{
    props:[
      'old',
      'errors',
      'plan',
      'spot',
    ],
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        type: 'create',
        maxWidth: 300,
        dayInfo: [{
          dayKey: 0,
          spotInfo: [{
            spotKey: 0,
            spotDisplay: ''
          }],
        }],
        contentsInfo: [{
          contentsKey: 0,
          daykey: 0,
          spotkey: 0,
          contentsTitle: 'これはテストタイトルです。',
          contentsDuration: '0.5時間',
          contentsAddress: '東京',
          contentsTag: 'testspot,testspot1',
          contentsInfo: 'これはテストspotです',
          spotImage: [],
        }],
        currentNum: 0,
        planOutline: {
          planTitle: '',
          mainTransportation: '車',
          hashTag: 'testPlan1, testPlan2',
          information: 'これはテストプランです。'
        },
        errorExist: '',
      };
    },
    computed: {
      _listStyle() {
        return {
          transition: '',
          transform: `translatex(${-100 * this.currentNum}%)`,
        };
      },
      ErrorExist() {
        return this.errorExist === 'Exist';
      }
    },
    created: function(){
      console.log(this.plan);
      if(this.errors.length != 0){
        console.log(this.old.plan);
        this.errorExist = 'Exist';
        this.InputOldInfo_Plan(this.old.plan[0]);
        this.InputOldInfo_Spot(this.old.spot);
        this.InputErrInfo(this.errors);
      }else if(this.plan !== undefined){
        this.type = `update/${this.plan.id}`;
        this.InputOldInfo_Plan(this.plan);
        this.InputOldInfo_Spot(this.spot);
      }
    },
    beforeUpdate: function(){
      $('.hash-tag').tagsInput({width:'100%'});
    },
    methods: {
      InputOldInfo_Plan: function(plan){
        console.log(plan);
        this.planOutline.planTitle = plan.plan_title;
        this.planOutline.mainTransportation = plan.main_transportation;
        this.planOutline.hashTag = plan.plan_tag;
        this.planOutline.information = plan.plan_information;
      },
      InputOldInfo_Spot: function(spot){
        let day_max = 1;
        for(let i = 0; i < spot.length; i++){
          if(day_max < spot[i].spot_day){
            day_max = spot[i].spot_day;
            this.addDay();
          }else if(i > 0){
            this.addSpot(spot[i].spot_day - 1, i, 'none');
          }

          this.contentsInfo[i].contentsKey = i;
          this.contentsInfo[i].contentsTitle = spot[i].spot_title;
          this.contentsInfo[i].contentsAddress = spot[i].spot_address;
          this.contentsInfo[i].contentsTag = spot[i].spot_tag;
          this.contentsInfo[i].contentsInfo = spot[i].spot_information;
          this.contentsInfo[i].contentsDuration = spot[i].spot_duration;
          if(spot[i].images !== undefined){
            spot[i].images.forEach($image => {
              this.contentsInfo[i].spotImage.push($image.image_path);
            });
          };
        }
      },
      InputErrInfo: function(err){
        let key = Object.keys(err);
        for(let i=0; i<key.length; i++){
          let [category, index, property] = key[i].split('.')

          if(category == 'plan'){
            switch (property) {
              case 'plan_title':
                this.planOutline.mark_title = "＊";
                break;
              case 'plan_info':
                this.planOutline.mark_info = "＊";
                break;
              default:
                console.log(category);
                console.log(property);
                console.log('該当なし')
            }
          }else if(category == 'spot'){
            switch (property) {
              case 'spot_title':
                this.contentsInfo[index].mark_title = "＊"
                break;
              case 'spot_address':
                this.contentsInfo[index].mark_address = "＊"
                break;
              case 'spot_info':
                this.contentsInfo[index].mark_info = "＊"
                break;
              case 'spot_image':
                this.contentsInfo[index].mark_image = "*"
              default:
            }
          }
        }
      },
      carouselMove: function(spotKey){
        this.currentNum = spotKey + 1;
      },
      addDay: function() {
        let newDaykey = this.dayInfo.length;

        this.dayInfo.push({
          dayKey: newDaykey,
          spotInfo: [{}]
        });

        this.contentsInfo.push({
          spotImage: [],
          contentsDuration: '0.5時間',
        });

        this.assignKey();
      },
      deleteDay: function() {
        let targetIndex_day = this.dayInfo.length - 1;
        let targetIndex_content = this.dayInfo[targetIndex_day].spotInfo[0].spotKey;
        let targetCount = this.dayInfo[targetIndex_day].spotInfo.length;

        if(targetIndex_day == 0){
          return;
        }

        this.contentsInfo.splice(targetIndex_content, targetCount);
        this.dayInfo.splice(targetIndex_day, 1);

        let spotArray = this.dayInfo[this.dayInfo.length - 1].spotInfo;
        this.currentNum = spotArray[spotArray.length - 1].spotKey + 1;
      },
      addSpot: function(dayIndex, spotIndex, displayStyle) {

        this.dayInfo[dayIndex].spotInfo.splice(spotIndex + 1,0,{
          spotDisplay: `display: ${displayStyle}`,
        });

        this.contentsInfo.splice(spotIndex + 1,0,{
          daykey: dayIndex,
          spotImage: [],
          contentsDuration: '0.5時間',
        });

        this.assignKey();
      },
      deleteSpot: function(dayKey, spotKey){
        const spotArray = this.dayInfo[dayKey].spotInfo;
        const contentsArray = this.contentsInfo;
        let target;

        if(spotArray.length !== 1){
          for(let i=0; i<spotArray.length; i++){
            if(spotArray[i].spotKey == spotKey){
              target = i;
            }
          }
          spotArray.splice(target, 1);

          for(let i=0; i<contentsArray.length; i++){
            if(contentsArray[i].daykey == dayKey && contentsArray[i].spotkey == spotKey){
              contentsArray.splice(i, 1);
            }
          }
        }else{
          return;
        }

        this.assignKey();
      },
      assignKey: function(){
        const dayArray = this.dayInfo;
        let keyCount = 0;

        for(let i=0; i<dayArray.length; i++){
          for(let j=0; j<dayArray[i].spotInfo.length; j++){
            dayArray[i].spotInfo[j].spotKey = keyCount;
            this.contentsInfo[keyCount].contentsKey = keyCount;
            this.contentsInfo[keyCount].spotkey = keyCount;
            this.contentsInfo[keyCount].daykey = i;
            keyCount++;
          }
        }
      },
      SelectImage: function(Key, Target){

        // var file = Target.files[0];
        // if (!file.type.match(/^image\/(png|jpeg|gif)$/)){
        //   alert('認められていないファイル形式です')
        //   return;
        // }
        //
        // // var image = new Image();
        // // testComment
        // let vueComponents = this;
        // var reader = new FileReader();
        // reader.readAsDataURL(file);
        //
        // reader.onload = (evt) => {
        //
        //     var image = new Image();
        //     image.onload = () => {
        //
        //       // 最大幅
        //       const maxWidth = 900;
        //       // 最大高さ
        //       const maxHeight = 700;
        //       // 変換後幅
        //       var convertedWidth;
        //       // 変換後高さ
        //       var convertedHeight;
        //       // canvas要素取得
        //       var canvas = $('#canvas' + Key);
        //       // 描画用オブジェクト
        //       var ctx = canvas[0].getContext('2d');
        //
        //       // 縦長画像でサイズオーバー
        //       if(image.height > image.width && image.height > maxHeight){
        //         convertedWidth = Math.round(image.width * maxHeight / image.height);
        //         convertedHeight = maxHeight;
        //       // 横長画像でサイズオーバー
        //       }else if(image.width > image.height && image.width > maxWidth){
        //         convertedWidth = maxWidth;
        //         convertedHeight = Math.round(image.height * maxWidth / image.width);
        //       // サイズオーバーなし
        //       }else{
        //         convertedWidth = image.width;
        //         convertedHeight = image.height;
        //       }
        //
        //       // 描画用に高さと幅をセット
        //       $('#canvas' + Key).attr('height', convertedHeight);
        //       $('#canvas' + Key).attr('width', convertedWidth);
        //       // 描画実行
        //       ctx.drawImage(image, 0, 0, image.width, image.height, 0, 0, convertedWidth, convertedHeight); //canvasに画像を転写
        //
        //       // 描画対象をファイル変換
        //       ctx.canvas.toBlob((blob) => {
        //         const imageFile = new File([blob], file.name, {
        //           type: file.type,
        //         });
        //         console.log(imageFile.size);
        //
        //         vueComponents.contentsInfo[Key].spotImage.push(imageFile);
        //         // console.log(this.contentsInfo);
        //       }, file.type, 1);
        //     }
        //     image.src = evt.target.result;
        // }




        if(Target.files.length > 0) {
          this.contentsInfo[Key].spotImage = [];
          const file = Target.files[0];
          const reader = new FileReader();

          reader.onload = (e) => {
            this.contentsInfo[Key].spotImage.push(e.target.result);
          };
          reader.readAsDataURL(file);
          console.log(this.contentsInfo[Key].spotImage);
        }else{
          this.contentsInfo[Key].spotImage = [];
        }

      },
      showSpot: function(e) {
        e.preventDefault();
        const content = $(e.target)
        .closest('section')
        .find('.accordion-content');

        if (!content.is(':visible')) {
          $('.accordion-content:visible').slideUp();
          content.slideDown();
        }
      },
      registarPlanTag: function(){
        this.planOutline.hashTag = document.getElementById('plantag-input').value;
      },
      registarSpotTag: function(key){
        this.contentsInfo[key].contentsTag = document.getElementById(`id${key}`).value;
      },
    },
  }
</script>
