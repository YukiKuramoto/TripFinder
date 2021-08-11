/* jshint curly:true, debug:true */
/* globals $ */
/* globals Vue */

$(function(){
  $('.hash-tag').tagsInput({width:'100%'});
});


// プラン投稿画面
new Vue({
  el: '#post-page',
  data() {
    return {
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
        spotImage: [],
      }],
      currentNum: 0,
      planOutline: {
        
      }
    };
  },
  computed: {
    _listStyle() {
      return {
        transition: '',
        transform: `translatex(${-100 * this.currentNum}%)`,
      };
    },
  },
  beforeUpdate: function(){
      $('.hash-tag').tagsInput({width:'100%'});
  },
  methods: {
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
    addSpot: function(dayIndex, spotIndex) {
      
      this.dayInfo[dayIndex].spotInfo.splice(spotIndex + 1,0,{
        spotDisplay: 'display: block;',
      });
      
      this.contentsInfo.splice(spotIndex + 1,0,{
        daykey: dayIndex,
        spotImage: [],
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
      this.contentsInfo[Key].spotImage = [];
      
      if(Target.files.length > 0){
        const file = Target.files[0];
        const reader = new FileReader();
        
        reader.onload = (e) => {
            this.contentsInfo[Key].spotImage.push(e.target.result);
        };
        reader.readAsDataURL(file);
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
  template: `
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
                            <i class="bi bi-plus-square plus-button" v-on:click.prevent="addSpot(day.dayKey, spot.spotKey)"></i>
                            <i class="bi bi-dash-square minus-button" v-on:click.prevent="deleteSpot(day.dayKey, spot.spotKey)"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                </section>
            </div>
        </div>
        <form id="post-form" action="/post/create" method="post">
            <div>
              <div class="carousel">
                <div class="list" v-bind:style="_listStyle">
                  <div class="list__item">
                    <div class="item">
                      <div class="content-title">PlanOutline</div>
                        <div class="title-area">
                          プランタイトル：
                          <input type="text"
                          name="plan-title"
                          class="user-input"
                          v-on:input="planOutline.planTitle = $event.target.value"
                          v-bind:value="planOutline.planTitle">
                        </div>
                        
                        <div class="transportation-label">
                          <div class="title-area">
                          主要交通手段　：
                          <select name="main-transportation" class="user-input">
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
                          name="plan-tag"
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
                            PLAN INFORMATION
                          </div>
                          <textarea class="plan-information-textarea user-input"
                          name="plan-info"
                          v-on:input="planOutline.information = $event.target.value"
                          v-bind:value="planOutline.information"
                          placeholder="プランの見所を投稿しよう！">
                          </textarea>
                        </div>
                        <input name="spot_count" v-bind:value="contentsInfo.length" style="display: none;">
                    </div>
                  </div>
                  <template class="spot-content" v-for="content in contentsInfo">
                    <div class="list__item">
                      <div class="item">
                        <div class="content-title">Spot{{ content.contentsKey + 1 }}</div>
                          <div class="title-area">
                            スポットタイトル： 
                            <input type="text"
                            class="user-input"
                            v-on:input="content.contentsTitle = $event.target.value"
                            v-bind:name="'spot_title' + content.contentsKey"
                            v-bind:value="content.contentsTitle">
                          </div>
                          <div class="spot-detail-wrapper">
                            <div class="spot-detail-area">
                              <div class="spot-stay-area">
                                <div class="spot-area-title">
                                  滞在時間：
                                </div>
                                <select v-bind:name="'spot-stay' + content.contentsKey"
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
                                  所在地：
                                </div>
                                <input type="text"
                                class="user-input"
                                v-on:input="content.contentsAddress = $event.target.value"
                                v-bind:name="'spot-address' + content.contentsKey"
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
                                v-bind:name="'spot-tag' + content.contentsKey"
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
                                写真を投稿：
                                <input type="file"
                                class="file-select"
                                v-on:input="SelectImage(content.contentsKey, $event.currentTarget)"
                                v-bind:name="'spot-image' + content.contentsKey"
                                multiple="multiple">
                              </div>
                              <div class="spot-image-view">
                                <img v-bind:src="content.spotImage[0]">
                              </div>
                            </div>
                          </div>
                          <div class="spot-information-area">
                            <div>
                              SPOT INFORMATION
                            </div>
                            <textarea class="spot-information-textarea user-input"
                            v-on:input="content.contentsInfo = $event.target.value"
                            v-bind:name="'spot-info' + content.contentsKey"
                            v-bind:value="content.contentsInfo"
                            placeholder="スポットの情報を投稿しよう！" >
                            </textarea>
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
  `,
});
