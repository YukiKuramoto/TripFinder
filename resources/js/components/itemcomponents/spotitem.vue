<template>
  <div :class="'item-component-wrapper item-component-wrapper-' + pagetype">
    <div class="spot-item-outer">
      <v-app v-if="pagetype != 'home' && pagetype != 'view'" id="inspire">
        <div class="text-center">
          <v-pagination
          v-model="current_page"
          :length="total_page"
          :total-visible="7"
          prev-icon="mdi-menu-left"
          next-icon="mdi-menu-right"
          circle
          @input="getNextpage"
          ></v-pagination>
        </div>
      </v-app>
      <div id="wrapper" :class="'spot-item-wrapper spot-item-wrapper-' + pagetype">
        <a v-for="spot in spots" :href="'/index/spot/' + spot.id" :class="'spot-item spot-item-' + pagetype">
          <div class="spot-image-outer">
            <img class="spot-image" :src="spot.images[0].image_path">
          </div>
          <div class="spot-outline-wrapper">
            <p v-if="pagetype == 'view'">Spot{{ spot.spot_count+1 }}</p>
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
      'response',
      'pagetype',
      'search_key',
      'prop_total_page',
    ],
    data() {
        return {
          current_page: '',
          total_page: '',
          spots: '',
        }
    },
    created: function(){
      // ページネーション初期値セット
      this.current_page = 1;
      // ページネーション合計ページ数セット
      this.total_page = this.prop_total_page;
      // サーバーからのレスポンスをセット
      this.spots = this.response;

    },
    mounted: function(){
      if(this.pagetype == "mypage/favspot"){
        $("#wrapper").width(580);
      }
    },
    methods: {
      getNextpage: function(){

        // then句でVueインスタンスにアクセスできるようthatに仮代入
        let that = this;

        // Axios送信リクエスト用の空オブジェクト作成
        let request = {
          params: {
            data: {
              // サーバー側に送信する次のページ数をセット
              next_index: this.current_page - 1,
              // 検索用キーをセット（Search：検索ワード、Mypage：ユーザーobject）
              search_key: this.search_key,
            }
          }
        };

        // リクエストをサーバー側に送信
        axios.get('/' + this.pagetype + '/nextspot', request)
        .then(function(response){
          // ページネーション合計ページ数を再セット
          that.total_page = response.data.total_page;
          // 取得結果を再セット
          that.spots = response.data.response;
        }).catch(function(error){
          console.log(error);
        })
      },
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

.item-component-wrapper-view {
  display: flex;
  justify-content: center;
}

.container {
  padding: 0px;
}

.spot-item-outer {
  /* display: grid; */
  place-items: center;
  padding-bottom: 50px;
}

.spot-item-wrapper-mypage {
  width: 580px;
}

.spot-item-wrapper {
  padding: 10px 0px;
  margin-top: 80px;
  width: 900px;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
}


.spot-item-wrapper-home {
  margin-top: 20px;
}

.spot-item-wrapper-view {
  margin-top: 40px;
}

.spot-item-wrapper-search {
  padding-left: 50px;
}

.spot-item {
  height: 300px;
  width: 220px;
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

.spot-item-view {
  height: 340px;
  width: 240px;
  margin: 40px 5%;
  pointer-events: none;
}

.spot-item-view:hover {
  transform: scale(1);
}

.spot-image-outer {
  height: 180px;
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

.spot-outline-wrapper > p {
  margin: 7px 0 0 0;
  font-weight: 700;
  text-align: center;
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

@media (min-width: 1301px) {
  .spot-item-wrapper-view {
    width: 1100px;
  }
}

@media (max-width: 1300px) {
  .spot-item-wrapper-view {
    width: 630px;
  }
}

</style>

<style media="screen">
.v-application--wrap {
  min-height: 0px;
}
</style>
