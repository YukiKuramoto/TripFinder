/* jshint curly:true, debug:true */
/* globals $ */

// ログイン、新規登録関連操作
$('#sign-in').on('click',(e) => {
    e.preventDefault();
    $('.modal-title').text('新規登録');
    $('#button-signin').show();
    $('#button-login').hide();
});

$('#log-in').on('click',(e) => {
    e.preventDefault();
    $('.modal-title').text('ログイン');
    $('#button-login').show();
    $('#button-signin').hide();
});

$('.btn-submit').click(function() {
  $(this).parents('form').attr('action', $(this).data('action'));
  $(this).parents('form').submit();
});


// プラン投稿画面
new Vue({
  el: '#post-page',
  data() {
    return {
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
        contentsTitle: `AddedPage${spotIndex + 1}`,
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
                    <h2 class="accordion-title"><a href="#" v-on:click="showSpot">PlanOutline</a></h2>
                    <div class="accordion-content accordion-content-active" v-on:click="carouselMove(-1)">PlanOutline</div>
                </section>
                
                <section v-for="day in dayInfo">
                    <h2 class="accordion-title"><a href="#!" v-on:click="showSpot">Day{{ day.dayKey + 1 }}</a></h2>
                    <div v-for="spot in day.spotInfo">
                      <div class="accordion-content" v-bind:style="spot.spotDisplay" v-on:click="carouselMove(spot.spotKey)">
                        <div class="accordion-content-area">
                          <div class="spot-area">
                            Spot{{ spot.spotKey + 1 }}
                          </div>
                          <div class="icon-area">
                            <i class="bi bi-plus-square plus-button" v-on:click="addSpot(day.dayKey, spot.spotKey)"></i>
                            <i class="bi bi-dash-square minus-button" v-on:click="deleteSpot(day.dayKey, spot.spotKey)"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                </section>
            </div>
        </div>
        <form id="post-form">
            <div>
              <div class="carousel">
                <div class="list" v-bind:style="_listStyle">
                  <div class="list__item">
                    <div class="item">
                      <div class="content-spot-number">PlanOutline</div>
                      <input type="text">
                    </div>
                  </div>
                  <template class="spot-content" v-for="content in contentsInfo">
                    <div class="list__item">
                      <div class="item">
                        <div class="content-spot-number">Spot{{ content.contentsKey + 1 }}</div>
                          <div class="spot-title-area">
                            スポットタイトル： 
                            <input type="text"
                            v-on:input="content.contentsTitle = $event.target.value" 
                            v-bind:value="content.contentsTitle">
                          </div>
                          <div class="spot-detail-area">
                            <div class="spot-address-area">
                              所在地：
                              <input type="text"
                              v-on:input="content.contentsAddress = $event.target.value" 
                              v-bind:value="content.contentsAddress">
                            </div>
                            <div class="spot-time-area">
                              滞在時間：
                              <input type="text"
                              v-on:input="content.contentsAddress = $event.target.value" 
                              v-bind:value="content.contentsAddress">
                            </div>
                          </div>
                          <div class="spot-image-area">
                            写真を投稿：
                            <input type="file"
                            v-on:input="content.contentsAddress = $event.target.value" 
                            v-bind:value="content.contentsAddress">
                          </div>
                          <div class="spot-info-area">
                            SpotInfo：
                            <textarea
                            v-on:input="content.contentsInfo = $event.target.value"
                            v-bind:value="content.contentsInfo"
                            placeholder="スポットの情報を投稿しよう！" >
                            </textarea>
                          </div>
                      </div>
                    </div>
                  </template>
                </div>
                <div class="submit-button-area">
                  <a href="#" class="btn btn-success post-submit-button">投稿する</a>
                </div>
              </div>
            </div>
        </form>
    </div>
  `,
});
