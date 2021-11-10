<template>
  <div id="post-body">
    <div id="post-menu">
      <div>
        <div v-show="type == 'post'" id="button-area">
          <button type="submit" id="dayadd-button" class="btn btn-secondary btn-submit" v-on:click="addDay">Add Day</button>
          <button type="submit" id="daydelete-button" class="btn btn-secondary btn-submit" v-on:click="deleteDay">Delete Day</button>
        </div>
        <section>
          <h2 class="accordion-title"><a href="#" v-on:click.prevent="showSpot">PlanOutline</a></h2>
          <div class="accordion-content accordion-content-active" v-on:click="carouselMove(-1)">PlanOutline</div>
        </section>
        <section v-for="day in dayInfo">
          <h2 class="accordion-title"><a href="#!" v-on:click.prevent="showSpot">Day{{ day.day_count + 1 }}</a></h2>
          <div v-for="(spot, index) in day.spotInfo">
            <div class="accordion-content" :style=spot.spot_accordion_display v-on:click.prevent="carouselMove(spot.spot_count)">
              <div class="accordion-content-area">
                <div class="spot-area">
                  Spot{{ index + 1 }}
                </div>
                <div v-if="type=='post' && index == day.spotInfo.length-1" class="icon-area">
                  <i class="bi bi-plus-square plus-button" v-on:click.prevent="addSpot(day.day_count, 'block')"></i>
                  <i class="bi bi-dash-square minus-button" v-on:click.prevent="deleteSpot(day.day_count)"></i>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
    <form id="post-form" action="/post/create" method="post" enctype="multipart/form-data">
      <div>
        <div v-if="ErrorExist" id="error-msg">
          ＊は入力必須項目です
        </div>
        <div class="carousel">
          <div v-if="type=='post'" class="list" v-bind:style="_listStyle">
            <div class="list__item">
              <planoutline-post-component
              :planOutline= planOutline
              ref="child_outline"
              @outlineUpdate='outlineDataUpdate'
              ></planoutline-post-component>
            </div>
            <template v-for="day in dayInfo">
              <div v-for="(spot, index) in day.spotInfo" class="spot-content list__item">
                <spot-post-component
                :spot= spot
                :spotIndex= index
                ref="child_spot"
                @spotUpdate='spotDataUpdate'
                ></spot-post-component>
              </div>
            </template>
          </div>
          <div v-if="type=='view'" class="list" v-bind:style="_listStyle">
            <div class="list__item">
              <planoutline-view-component
              :planOutline= planOutline
              :dayInfo= dayInfo
              :postuser = postuser_view
              :login_uid = loginuid_view
              :csrf = csrf
              ></planoutline-view-component>
            </div>
            <template v-for="day in dayInfo">
              <div v-for="spot in day.spotInfo" class="spot-content list__item">
                <spot-view-component
                :showstyle= "spot.spot_display"
                :planOutline= planOutline
                :spot= spot
                :postuser = postuser_view
                :login_uid = loginuid_view
                :csrf = csrf
                ></spot-view-component>
              </div>
            </template>
          </div>
          <div v-show="type == 'post'" class="submit-button-area">
            <input type="hidden" name="_token" :value="csrf">
            <input type="button" class="btn btn-success post-submit-button" id="post-button" @click="SendData" value="投稿する">
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  export default{
    props:[
      'plan_view',
      'spot_view',
      'postuser_view',
      'loginuid_view',
      'type',
    ],
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        maxWidth: 300,
        dayInfo: [{
          day_count: 0,
          spotInfo: [{
            spot_day: 0,
            spot_count: 0,
            spot_display: '',
            spot_image_preview: [],
            spot_image: [],
            spot_accordion_display: 'display:none'
          }],
        }],
        currentNum: 0,
        planOutline: {
          plan_title: '',
          main_transportation: '車',
          plan_tag: 'testPlan1, testPlan2',
          plan_information: 'これはテストプランです。'
        },
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
      if(this.type == 'view'){
        // console.log(this.plan_view);
        this.planOutline = this.plan_view;
        this.dayInfo = this.spot_view;
        this.setStyle(this.dayInfo);
      }
    },
    beforeUpdate: function(){
      $('.hash-tag').tagsInput({width:'100%'});
    },
    methods: {
      setStyle: function(dayInfo){
        this.dayInfo.forEach(day => {
          day.spotInfo.forEach(spot => {
            this.$set(spot,'spot_display','display: none');
          });
        });
      },
      carouselMove: function(spotKey){
        this.currentNum = spotKey + 1

        if(spotKey == -1){
          this.planOutline.displayStyle = 'display: block';
          this.dayInfo.forEach(day => {
            day.spotInfo.forEach(spot => {
              this.$set(spot,'spot_display', 'height: 0');
            });
          });
        }else{
          this.planOutline.displayStyle = 'height: 0';
          this.dayInfo.forEach((day, d_index) => {
            day.spotInfo.forEach((spot, s_index) => {
              if(spot.spot_count == spotKey){
                this.$set(spot,'spot_display', 'display: block');
              }else{
                this.$set(spot,'spot_display', 'height: 0');
              }
            });
          });
        }
      },
      addDay: function() {
        console.log(100);
        let newdayIndex = this.dayInfo.length;

        this.dayInfo.push({
          day_count: newdayIndex,
          spotInfo: [{
            spot_image_preview: [],
            spot_image: [],
            spot_duration: '0.5時間'
          }]
        });

        this.assignKey();
      },
      deleteDay: function() {
        let targetIndex = this.dayInfo.length - 1;
        if(targetIndex == 0){
          return;
        }

        this.dayInfo.splice(targetIndex, 1);
        let spotArray = this.dayInfo[this.dayInfo.length - 1].spotInfo;
        this.currentNum = spotArray[spotArray.length - 1].spot_count + 1;
      },
      addSpot: function(dayIndex, displayStyle) {

        console.log(displayStyle);
        this.dayInfo[dayIndex].spotInfo.push({
          spot_accordion_display: `display: ${displayStyle}`,
          spot_image: [],
          spot_image_preview: [],
          spot_duration: '0.5時間',
        });

        this.assignKey();
      },
      deleteSpot: function(dayIndex){
        let targetIndex = this.dayInfo[dayIndex].spotInfo.length - 1;

        if(targetIndex == 0){
          return;
        }else{
          this.dayInfo[dayIndex].spotInfo.splice(targetIndex, 1);
        }

        this.assignKey();
      },
      assignKey: function(){
        const dayArray = this.dayInfo;
        let keyCount = 0;

        for(let i=0; i<dayArray.length; i++){
          for(let j=0; j<dayArray[i].spotInfo.length; j++){
            dayArray[i].spotInfo[j].spot_count = keyCount;
            dayArray[i].spotInfo[j].spot_count = keyCount;
            dayArray[i].spotInfo[j].spot_day = i;
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
      outlineDataUpdate: function(childData){
        this.planOutline = childData;
      },
      spotDataUpdate: function(childData, spot_count_component){
        for(let i=0;i<this.dayInfo.length;i++){
          for(let j=0; j<this.dayInfo[i].spotInfo.length; j++){
            if(this.dayInfo[i].spotInfo[j].spot_count == spot_count_component){
              this.dayInfo[i].spotInfo[j] = childData;
            }
          }
        }
      },
      SendData: function(){

        let params = {};
        let that = this;
        var formData = new FormData();
        var config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        };

        this.dayInfo.forEach(day => {
          let dayIndex = this.dayInfo.indexOf(day);
          day.spotInfo.forEach(spot => {
            let spotIndex = day.spotInfo.indexOf(spot);
            spot.spot_image.forEach(image => {
              let imageIndex = spot.spot_image.indexOf(image);
              formData.append(`${dayIndex}_${spotIndex}_${imageIndex}`, image);
            });
          })
        })

        params.planOutline = this.planOutline;
        params.dayInfo = this.dayInfo;
        params = JSON.stringify(params);

        formData.append('request', params);

        // $('#post-button').val('送信中...');
        // $("#post-button").attr('disabled', true);

        axios.post('/post/create', formData, config)
        .then(function (response) {
          console.log(response);
          console.log('OK');
          // $('#post-button').val('送信完了！！');
        })
        .catch(function (error) {
          console.log(error);
          console.log(error.response.data.errors);
          // errors[key] = error.response.data.errors[key].join()
        });
      },
    },
  }
</script>

<style media="screen" scoped>
.btn-secondary{
  color: white;
}

.btn-success{
  color: white;
}
</style>
