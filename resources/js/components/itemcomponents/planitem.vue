<template>
  <div class="item-component-wrapper">
    <v-app v-if="pagetype != 'home'" id="inspire">
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
    <div :class="'plan-item-wrapper plan-item-wrapper-' + pagetype">
      <a v-for="plan in plans" :href="'/index/' + plan.id" :class="'plan-item plan-item-' + pagetype">
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
        'response',
        'pagetype',
        'search_key',
        'prop_total_page',
    ],
    data() {
        return {
          current_page: '',
          total_page: '',
          plans: '',
        }
    },
    created: function(){
      // ページネーション初期値セット
      this.current_page = 1;
      // ページネーション合計ページ数セット
      this.total_page = this.prop_total_page;
      // サーバーからのレスポンスをセット
      this.plans = this.response;
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
        axios.get('/' + this.pagetype + '/nextplan', request)
        .then(function(response){
          // ページネーション合計ページ数を再セット
          that.total_page = response.data.total_page;
          // 取得結果を再セット
          that.plans = response.data.response;
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

.container {
  padding: 0px;
}

.item-component-wrapper {
  padding-bottom: 40px;
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
