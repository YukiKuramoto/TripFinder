
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({

  // 共有するデータ
  state: {
    currentSlide: 0,
    planOutline: {
      planTitle: '',
      hashTag: 'testPlan1, testPlan2',
      information: 'これはテストプランです。'
    },
    contentsArray: [{
      contentsKey: 0,
      dayKey: 0,
      spotKey: 0,
      contentsTitle: 'これはテストタイトルです。',
      contentsDuration: '0.5時間',
      contentsAddress: '東京',
      contentsTag: 'testspot,testspot1',
      contentsInfo: 'これはテストspotです',
      spotImage: [],
    }],
    errorExist: '',
  },

  // ミューテーション
  mutations: {
    InputOldInfo_Plan(state, plan){
      console.log(state.planOutline);
      state.planOutline.planTitle = plan.plan_title;
      state.planOutline.hashTag = plan.plan_tag;
      state.planOutline.information = plan.plan_info;
    },
    ChangeErrStatus(state){
      state.errorExist = 'Exist';
    },
    InputErrInfo(state, params){
      let [category, index, property] = params.split('.')
      if(category == 'plan'){
        switch (property) {
          case 'plan_title':
            state.planOutline.mark_title = "＊";
            break;
          case 'plan_info':
            state.planOutline.mark_info = "＊";
            break;
          default:
            console.log('該当なし')
        }
      }else if(category == 'spot'){
        switch (property) {
          case 'spot_title':
            state.contentsArray[index].mark_title = "＊"
            break;
          case 'spot_address':
            state.contentsArray[index].mark_address = "＊"
            break;
          case 'spot_info':
            state.contentsArray[index].mark_info = "＊"
            break;
          default:
        }
      }
    },
    changeCurrentSlide(state, newNumber) {
      state.currentSlide = newNumber;
    },
    addDay(state, dayKey){
      state.contentsArray.push({
        dayKey: dayKey,
        spotImage: [],
      });
      for(let i=0;i<state.contentsArray.length; i++){
        state.contentsArray[i].contentsKey = i;
        state.contentsArray[i].spotKey = i;
      }
    },
    addSpot(state, {targetIndex, dayKey}){
      state.contentsArray.splice(targetIndex, 0, {
        dayKey: dayKey,
        spotImage: [],
        contentsDuration: '0.5時間',
      })
      for(let i=0;i<state.contentsArray.length; i++){
        state.contentsArray[i].contentsKey = i;
        state.contentsArray[i].spotKey = i;
      }
    },
    deleteDay(state, {index, count}){
      state.contentsArray.splice(index, count)
    },
    deleteSpot(state, {dayKey, spotKey}){
      for(let i=0; i<state.contentsArray.length; i++){
        if(state.contentsArray[i].dayKey == dayKey && state.contentsArray[i].spotKey == spotKey){
          state.contentsArray.splice(i, 1);
        }else{
          state.contentsArray[i].contentsKey = i;
          state.contentsArray[i].spotKey = i;
        }
      }
    },
    registarPlanTag(state, value){
      state.planOutline.hashTag = value;
    },
    registarSpotTag(state, {key, value}){
      state.contentsArray[key].contentsTag = value;
    },
  },
})
