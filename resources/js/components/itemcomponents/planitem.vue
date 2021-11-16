<template>
  <div class="item-component-wrapper">
    <v-app v-if="pagetype != 'home'" id="inspire">
      <div class="text-center">
        <v-pagination
        v-model="page_current"
        :length="page_length"
        :total-visible="7"
        prev-icon="mdi-menu-left"
        next-icon="mdi-menu-right"
        circle
        @input="getNextpage"
        ></v-pagination>
      </div>
    </v-app>
    <div :class="'plan-item-wrapper plan-item-wrapper-' + pagetype">
      <a v-for="plan in page_plans" :href="'/index/' + plan.id" :class="'plan-item plan-item-' + pagetype">
        <div class="plan-image-wrapper">
          <img class="plan-image" :src="plan.spots[0].images[0].image_path">
        </div>
        <div class="plan-outline-wrapper">
          <p class="plan-title">{{ plan.plan_title }}</p>
          <div class="icon-area">
            <i class="bi bi-star-fill"></i>{{ plan.favs.length }} favs
            <i class="bi bi-geo-fill"></i>{{ plan.spots.length }} spots
          </div>
          <p v-for="tag in plan.tags" class="plan-tag">
            #{{ tag.name.substr(0,10) }}
          </p>
          <p class="plan-introduction">{{ plan.plan_information }}</p>
        </div>
      </a>
    </div>
  </div>
</template>

<script>
export default {
    props: [
        'postuser',
        'response',
        'length',
        'pagetype',
        'parameter',
        'search_key',
    ],
    data() {
        return {
          page_current: '',
          page_length: '',
          page_plans: '',
        }
    },
    created: function(){
      this.page_current = 1;
      this.page_length = this.length;
      this.page_plans = this.response;
    },
    methods: {
      getNextpage: function(){
        let request = {};
        let that = this;
        this.createRequest(request);

        axios.post('/' + this.pagetype + '/nextplan', request)
        .then(function(response){
          console.log(response.data);
          that.page_length = response.data.response_length;
          that.page_plans = response.data.response;
        }).catch(function(error){
          console.log(error);
        })
      },
      createRequest: function(request){

        request.page = this.page_current;
        request.parameter = this.parameter;

        switch (this.pagetype) {
          case 'mypage':
            request.postuser = this.postuser;
            break;
          case 'search':
            request.search_word = this.search_key.search_word;
            request.duration = this.search_key.duration;
            request.transportation = this.search_key.transportation;
            request.address = this.search_key.address;
            break;
        }
      }
    }
}
</script>

<style media="screen" scoped>

.v-application {
  height: 0px;
}

.v-application--wrap {
  min-height: 0px;
}

.container {
  padding: 0px;
}

.item-component-wrapper {
  padding-bottom: 80px;
}

.plan-item-wrapper{
  margin-top: 50px;
  padding: 30px 0px 15px 0px;
  min-height: 615px;
  max-width: 800px;
}

.plan-item-wrapper-search{
  min-height: 0px;
}

.plan-item-wrapper-home{
  margin-top: 0px;
}

.plan-item {
  height: 180px;
  padding: 20px 15px;
  margin-bottom: 15px;
  width: 100%;
  border: solid 1px rgba(204, 204, 204, 0.8);
  border-radius: 10px;
  box-shadow: 0 8px 10px 0 rgba(0, 0, 0, .5);
  display: flex;
  text-decoration: none;
  color: black;
}

.plan-item-search {
  min-width: 800px;
}

.plan-item:hover{
  text-decoration: none;
  color: black;
}

.plan-image {
  height: 100%;
  width: 120px;
  border-radius: 10px;
  object-fit: cover;
}

.plan-tag {
  display: inline-block;
  font-size: 12px;
  border-radius: 100vh;
  padding: 2px 10px;
  color: #fff;
  background-color: #7dcafa;
}

.plan-outline-wrapper {
  padding-left: 30px;
  width: 70%;
}

.plan-title {
  display: inline-block;
  font-size: 20px;
  margin: 0 0 7px 0;
  padding: 0;
  text-decoration: underline 0.5px;

  overflow: hidden;
  white-space: nowrap;
  width: 100%;
  text-overflow: ellipsis;
}

.icon-area {
  margin: 5px 0 7px 0;
}

.bi {
  margin: 0 5px 0 10px;
}

.bi-star-fill {
  color: #fcc800;
}

.bi-geo-fill {
  color: #0068b7;
}

.bi-pencil-square {
  margin-right: 8px;
  color: #5f5f5f;
}

.plan-introduction {
  overflow: hidden;
  white-space: nowrap;
  width: 100%;
  text-overflow: ellipsis;
}

.plan-item:last-child {
  margin-bottom: 0;
}

.v-application--wrap {
  min-height: 0;
}

</style>

<style media="screen">
.v-application--wrap {
  min-height: 0px;
}
</style>
