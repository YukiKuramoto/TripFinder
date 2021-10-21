<template>
  <div class="item-component-wrapper">
    <v-app v-if="pagetype != 'home'" id="inspire">
      <div class="text-center">
        <v-container>
          <v-row justify="center">
            <v-col cols="8">
              <v-container class="max-width">
                <v-pagination
                v-model="page_current"
                class="my-3"
                :length="page_length"
                circle
                @input="getNextpage"
                ></v-pagination>
              </v-container>
            </v-col>
          </v-row>
        </v-container>
      </div>
    </v-app>
    <div :class="'plan-item-wrapper plan-item-wrapper-' + pagetype">
      <a v-for="plan in page_plans" :href="'/index/' + plan.id" class="plan-item">
        <div class="plan-image-wrapper">
          <img class="plan-image" :src="plan.images[0].image_path">
        </div>
        <div class="plan-outline-wrapper">
          <p class="plan-title">{{ plan.plan_title }}</p>
          <div class="icon-area">
            <i class="bi bi-star-fill"></i>{{ plan.favs.length }} favs
            <i class="bi bi-geo-fill"></i>{{ plan.spots.length }} spots
          </div>
          <p v-for="tag in plan.tags" class="plan-tag">
            #{{ tag.name }}
          </p>
          <p class="plan-introduction">{{ plan.plan_information.substr(0, 30) }}...</p>
        </div>
      </a>
    </div>
  </div>
</template>

<script>
export default {
    props: [
        'user',
        'plans',
        'length',
        'pagetype',
        'parameter',
        'searchword',
    ],
    data() {
        return {
          page_current: '',
          page_length: '',
          page_user: '',
          page_plans: '',
        }
    },
    created: function(){
      console.log(this.plans);
      this.page_current = 1;
      this.page_length = this.length;
      this.page_user = this.user;
      this.page_plans = this.plans;
    },
    methods: {
      getNextpage: function(){
        console.log(this.page_current);
        let params = {};
        let that = this;
        params.page = this.page_current;
        params.user = this.page_user.id;
        params.parameter = this.parameter;
        params.search_word = this.searchword;

        axios.post('/' + this.pagetype + '/nextplan', params)
        .then(function(response){
          console.log(response.data);
          that.page_user = response.data.user;
          that.page_length = response.data.plan_length;
          that.page_plans = response.data.plans;
        }).catch(function(error){
          console.log(error);
        })
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
  min-width: 665px;
  border: solid 1px rgba(204, 204, 204, 0.8);
  border-radius: 10px;
  box-shadow: 0 8px 10px 0 rgba(0, 0, 0, .5);
  display: flex;
  text-decoration: none;
  color: black;
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
}

.plan-title {
  display: inline-block;
  font-size: 20px;
  margin: 0 0 7px 0;
  padding: 0;
  text-decoration: underline 0.5px;
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
