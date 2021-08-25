<template id="">
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
          <section v-for="day in dayArray">
              <h2 class="accordion-title"><a href="#!" v-on:click.prevent="showSpot">Day{{ day.dayKey + 1 }}</a></h2>
              <div v-for="spot in day.spotArray">
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
</template>

<script>
  export default{
    props: [
      'old',
      'errors',
    ],
    data() {
      return {
        dayArray: [{
          dayKey: 0,
          spotArray: [{
            spotKey: 0,
            spotDisplay: ''
          }],
        }],
        currentNum: 0,
      }
    },
    created: function(){
      if(this.errors.length != 0){
        this.$store.commit('ChangeErrStatus');
        this.InputOldInfo_Plan(this.old.plan[0]);
        this.InputOldInfo_Spot(this.old.spot);
        this.InputErrInfo(this.errors);
      }
    },
    methods: {
      InputOldInfo_Plan: function(plan){
        this.$store.commit('InputOldInfo_Plan', plan);
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
        }
      },
      InputErrInfo: function(err){
        let key = Object.keys(err);
        for(let i=0; i<key.length; i++){
          this.$store.commit('InputErrInfo', key[i]);
        }
      },
      carouselMove: function(spotKey){
        this.$store.commit('changeCurrentSlide', spotKey + 1);
      },
      addDay: function() {
        let newDaykey = this.dayArray.length;

        this.dayArray.push({
          dayKey: newDaykey,
          spotArray: [{}]
        });
        this.$store.commit('addDay', newDaykey);
        this.assignKey();
      },
      deleteDay: function() {
        let targetIndex_Day = this.dayArray.length - 1;
        let targetIndex_Spot = this.dayArray[targetIndex_Day].spotArray[0].spotKey;
        let targetCount = this.dayArray[targetIndex_Day].spotArray.length;

        if(targetIndex_Day == 0){
          return;
        }
        this.$store.commit('deleteDay', {index: targetIndex_Spot, count: targetCount});
        this.dayArray.splice(targetIndex_Day, 1);
        this.$emit('method-emit', 'deleteSpot', targetIndex_Spot, targetCount, '');

        let lastIndex = this.dayArray.length - 1
        let spotArray = this.dayArray[lastIndex].spotArray;
        this.$store.commit('changeCurrentSlide', spotArray[spotArray.length - 1].spotKey + 1);
      },
      addSpot: function(dayIndex, spotIndex, displayStyle) {

        this.dayArray[dayIndex].spotArray.splice(spotIndex + 1,0,{
          spotDisplay: `display: ${displayStyle}`,
        });
        this.$store.commit('addSpot', { targetIndex: spotIndex + 1, dayKey: dayIndex});
        this.assignKey();
      },
      deleteSpot: function(dayKey, spotKey){
        const spotArray = this.dayArray[dayKey].spotArray;
        let target;

        if(spotArray.length !== 1){
          for(let i=0; i<spotArray.length; i++){
            if(spotArray[i].spotKey == spotKey){
              target = i;
            }
          }
          spotArray.splice(target, 1);

          this.$emit('method-emit', 'deleteSpot', '', '', '');
          console.log(spotKey);
          this.$store.commit('deleteSpot', {dayKey: dayKey, spotKey: spotKey});
          this.$store.commit('changeCurrentSlide', spotKey - 1);
        }else{
          return;
        }

        this.assignKey();
      },
      assignKey: function(){
        const dayArray = this.dayArray;
        let keyCount = 0;

        for(let i=0; i<dayArray.length; i++){
          for(let j=0; j<dayArray[i].spotArray.length; j++){
            dayArray[i].spotArray[j].spotKey = keyCount;
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
    }
  }
</script>
