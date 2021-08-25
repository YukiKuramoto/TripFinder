<template>
  <div id="post-body">
    <SlideMenu
      :old="old"
      :errors= "errors">
    </SlideMenu>
    <CarouselForm
      :old="old"
      :errors= "errors">
    </CarouselForm>
  </div>
</template>

<script>
  import Vue from 'vue';
  import Vuex from 'vuex';
  import SlideMenu from './SlideMenuComponent.vue';
  import CarouselForm from './CarouselFormComponent.vue';

  Vue.use(Vuex);

  export default{
    props:[
      'old',
      'errors',
    ],
    components: {
      SlideMenu,
      CarouselForm,
    },
    data() {
      return {
        sayConsole: function(method, index, length, dayKey){
          switch (method) {
            case 'addDay':
              this.$refs.carousel.addDay();
              console.log('add!!!!!');
              break;
            case 'addSpot':
              console.log('SPOTADD');
              break;
            case 'deleteSpot':
              console.log('DELETE');
              break;
          }
        },
      }
    }
    // data() {
    //   return {
    //     csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //     dayInfo: [{
    //       dayKey: 0,
    //       spotInfo: [{
    //         spotKey: 0,
    //         spotDisplay: ''
    //       }],
    //     }],
    //     contentsInfo: [{
    //       contentsKey: 0,
    //       daykey: 0,
    //       spotkey: 0,
    //       contentsTitle: 'これはテストタイトルです。',
    //       contentsDuration: '0.5時間',
    //       contentsAddress: '東京',
    //       contentsTag: 'testspot,testspot1',
    //       contentsInfo: 'これはテストspotです',
    //       spotImage: [],
    //     }],
    //     currentNum: 0,
    //     planOutline: {
    //       planTitle: '',
    //       hashTag: 'testPlan1, testPlan2',
    //       information: 'これはテストプランです。'
    //     },
    //     errorExist: '',
    //   };
    // },
    // computed: {
    //   _listStyle() {
    //     return {
    //       transition: '',
    //       transform: `translatex(${-100 * this.currentNum}%)`,
    //     };
    //   },
    //   ErrorExist() {
    //     return this.errorExist === 'Exist';
    //   }
    // },
    // created: function(){
    //   if(this.errors.length != 0){
    //     this.errorExist = 'Exist';
    //     this.InputOldInfo_Plan(this.old.plan[0]);
    //     this.InputOldInfo_Spot(this.old.spot);
    //     this.InputErrInfo(this.errors);
    //   }
    // },
    // beforeUpdate: function(){
    //   $('.hash-tag').tagsInput({width:'100%'});
    // },
    // methods: {
    //   InputOldInfo_Plan: function(plan){
    //     console.log(plan);
    //     this.planOutline.planTitle = plan.plan_title;
    //     this.planOutline.hashTag = plan.plan_tag;
    //     this.planOutline.information = plan.plan_info;
    //   },
    //   InputOldInfo_Spot: function(spot){
    //     let day_max = 1;
    //     for(let i = 0; i < spot.length; i++){
    //       if(day_max < spot[i].spot_day){
    //         day_max = spot[i].spot_day;
    //         this.addDay();
    //       }else if(i > 0){
    //         this.addSpot(spot[i].spot_day - 1, i, 'none');
    //       }
    //
    //       this.contentsInfo[i].contentsKey = i;
    //       this.contentsInfo[i].contentsTitle = spot[i].spot_title;
    //       this.contentsInfo[i].contentsAddress = spot[i].spot_address;
    //       this.contentsInfo[i].contentsTag = spot[i].spot_tag;
    //       this.contentsInfo[i].contentsInfo = spot[i].spot_info;
    //       this.contentsInfo[i].contentsDuration = spot[i].spot_duration;
    //     }
    //   },
    //   InputErrInfo: function(err){
    //     let key = Object.keys(err);
    //     for(let i=0; i<key.length; i++){
    //       let [category, index, property] = key[i].split('.')
    //
    //       if(category == 'plan'){
    //         switch (property) {
    //           case 'plan_title':
    //             this.planOutline.mark_title = "＊";
    //             break;
    //           case 'plan_info':
    //             this.planOutline.mark_info = "＊";
    //             break;
    //           default:
    //             console.log('該当なし')
    //         }
    //       }else if(category == 'spot'){
    //         switch (property) {
    //           case 'spot_title':
    //             this.contentsInfo[index].mark_title = "＊"
    //             break;
    //           case 'spot_address':
    //             this.contentsInfo[index].mark_address = "＊"
    //             break;
    //           case 'spot_info':
    //             this.contentsInfo[index].mark_info = "＊"
    //             break;
    //           default:
    //         }
    //       }
    //     }
    //   },
    //   carouselMove: function(spotKey){
    //     this.currentNum = spotKey + 1;
    //   },
    //   addDay: function() {
    //     let newDaykey = this.dayInfo.length;
    //
    //     this.dayInfo.push({
    //       dayKey: newDaykey,
    //       spotInfo: [{}]
    //     });
    //
    //     this.contentsInfo.push({
    //       spotImage: [],
    //       contentsDuration: '0.5時間',
    //     });
    //
    //     this.assignKey();
    //   },
    //   deleteDay: function() {
    //     let targetIndex_day = this.dayInfo.length - 1;
    //     let targetIndex_content = this.dayInfo[targetIndex_day].spotInfo[0].spotKey;
    //     let targetCount = this.dayInfo[targetIndex_day].spotInfo.length;
    //
    //     if(targetIndex_day == 0){
    //       return;
    //     }
    //
    //     this.contentsInfo.splice(targetIndex_content, targetCount);
    //     this.dayInfo.splice(targetIndex_day, 1);
    //
    //     let spotArray = this.dayInfo[this.dayInfo.length - 1].spotInfo;
    //     this.currentNum = spotArray[spotArray.length - 1].spotKey + 1;
    //   },
    //   addSpot: function(dayIndex, spotIndex, displayStyle) {
    //
    //     this.dayInfo[dayIndex].spotInfo.splice(spotIndex + 1,0,{
    //       spotDisplay: `display: ${displayStyle}`,
    //     });
    //
    //     this.contentsInfo.splice(spotIndex + 1,0,{
    //       daykey: dayIndex,
    //       spotImage: [],
    //       contentsDuration: '0.5時間',
    //     });
    //
    //     this.assignKey();
    //   },
    //   deleteSpot: function(dayKey, spotKey){
    //     const spotArray = this.dayInfo[dayKey].spotInfo;
    //     const contentsArray = this.contentsInfo;
    //     let target;
    //
    //     if(spotArray.length !== 1){
    //       for(let i=0; i<spotArray.length; i++){
    //         if(spotArray[i].spotKey == spotKey){
    //           target = i;
    //         }
    //       }
    //       spotArray.splice(target, 1);
    //
    //       for(let i=0; i<contentsArray.length; i++){
    //         if(contentsArray[i].daykey == dayKey && contentsArray[i].spotkey == spotKey){
    //           contentsArray.splice(i, 1);
    //         }
    //       }
    //     }else{
    //       return;
    //     }
    //
    //     this.assignKey();
    //   },
    //   assignKey: function(){
    //     const dayArray = this.dayInfo;
    //     let keyCount = 0;
    //
    //     for(let i=0; i<dayArray.length; i++){
    //       for(let j=0; j<dayArray[i].spotInfo.length; j++){
    //         dayArray[i].spotInfo[j].spotKey = keyCount;
    //         this.contentsInfo[keyCount].contentsKey = keyCount;
    //         this.contentsInfo[keyCount].spotkey = keyCount;
    //         this.contentsInfo[keyCount].daykey = i;
    //         keyCount++;
    //       }
    //     }
    //   },
    //   SelectImage: function(Key, Target){
    //     this.contentsInfo[Key].spotImage = [];
    //
    //     if(Target.files.length > 0){
    //       const file = Target.files[0];
    //       const reader = new FileReader();
    //
    //       reader.onload = (e) => {
    //         this.contentsInfo[Key].spotImage.push(e.target.result);
    //       };
    //       reader.readAsDataURL(file);
    //     }
    //   },
    //   showSpot: function(e) {
    //     e.preventDefault();
    //     const content = $(e.target)
    //     .closest('section')
    //     .find('.accordion-content');
    //
    //     if (!content.is(':visible')) {
    //       $('.accordion-content:visible').slideUp();
    //       content.slideDown();
    //     }
    //   },
    //   registarPlanTag: function(){
    //     this.planOutline.hashTag = document.getElementById('plantag-input').value;
    //   },
    //   registarSpotTag: function(key){
    //     this.contentsInfo[key].contentsTag = document.getElementById(`id${key}`).value;
    //   },
    // },
  }
</script>
