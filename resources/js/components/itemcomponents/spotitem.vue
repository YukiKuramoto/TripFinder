<template>
  <div class="item-component-wrapper">
    <div class="spot-item-outer">
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
      <div :class="'spot-item-wrapper spot-item-wrapper-' + pagetype">
        <a v-for="spot in page_spots" :href="'/index/spot/' + spot.id" class="spot-item">
          <div class="spot-image-outer">
            <img class="spot-image" :src="spot.images[0].image_path">
          </div>
          <div class="spot-outline-wrapper">
            <div class="spot-title-area">
              <p class="spot-title">{{ spot.spot_title.substr(0, 25) }}</p>
            </div>
            <div class="icon-area">
              <i class="bi bi-star-fill"></i>{{ spot.favs.length }} favs
              <i class="bi bi-pencil-square"></i> by {{ spot.user.name }}
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    props: [
        'user',
        'spots',
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
          page_spots: '',
        }
    },
    created: function(){
      console.log(this.parameter);
      this.page_current = 1;
      this.page_length = this.length;
      this.page_user = this.user;
      this.page_spots = this.spots;
    },
    methods: {
      getNextpage: function(){
        console.log(this.parameter);
        let params = {};
        let that = this;
        params.page = this.page_current;
        params.user = this.page_user.id;
        params.parameter = this.parameter;
        params.search_word = this.searchword;

        axios.post('/' + this.pagetype + '/nextspot', params)
        .then(function(response){
          console.log(response.data.spots);
          that.page_user = response.data.user;
          that.page_length = response.data.spot_length;
          that.page_spots = response.data.spots;
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

.spot-item-outer {
  display: grid;
  place-items: center;
  padding-bottom: 50px;
}

.spot-item-wrapper {
  padding: 10px 0px;
  margin-top: 80px;
  width: 900px;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
}

.spot-item-wrapper-mypage {
  width: 580px;
}

.spot-item-wrapper-home {
  margin-top: 20px;
}


.spot-item {
  height: 320px;
  width: 250px;
  margin: 0 20px 30px 20px;
  padding: 0;
  border: solid 0.5px rgba(77, 77, 77, 0.5);
  box-shadow: 0 8px 10px 0 rgba(0, 0, 0, 0.3);
  text-decoration: none;
  color: black;
}

.spot-item:hover {
  transform: scale(1.03);
}

.spot-image-outer {
  height: 200px;
  background-color: #f5f5f5;
  border-bottom: solid 0.5px rgba(77, 77, 77, 0.5);
  display: flex;
  justify-content: center;
}

.spot-image {
  margin: 4%;
  max-height: 90%;
  max-width: 85%;
  flex-wrap: wrap;
  border-radius: 5px;
}


.spot-title-area {
  padding: 5px 10px;
}

.spot-title-area p {
  margin: 8px 0 0 0;
  text-align: center;
  text-decoration: underline;
}

.icon-area {
  margin: 8px 0 7px 0;
  text-align: center;
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

</style>

<style media="screen">
.v-application--wrap {
  min-height: 0px;
}
</style>
