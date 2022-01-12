<template>
  <div class="searchpage-outer">
    <div class="searchpage-title">
      <h2>プランやユーザーを検索してみよう</h2>
    </div>
    <div class="search-box-area">
      <div class="search-box-area-wrapper accordion">
        <section class="form-search-fromword">
          <div class="form-title-area accordion-title">
            <a href="#">検索ワードから探す</a>
          </div>
          <div class="form-body-area accordion-content accordion-content-active">
            <form class="search_container" method="post">
              <input type="hidden" name="_token" :value="csrf">
              <div class="search-box-wrapper">
                <input v-model="search_key_form.keyword.search_word" type="text" size="25" placeholder="キーワード検索">
                <button type="submit" @click.prevent="search_axios(search_type, search_key_form.keyword)"><i class="bi bi-search"></i></button>
              </div>
              <div class="radio-button-area">
                <input type="radio" name="search_type" value="plan" checked="checked" v-model="search_type"><span>PLAN</span>
                <input type="radio" name="search_type" value="spot" v-model="search_type"><span>SPOT</span>
                <input type="radio" name="search_type" value="user" v-model="search_type"><span>USER</span>
              </div>
            </form>
          </div>
        </section>
        <section class="form-search-keytag">
          <div class="form-title-area accordion-title">
            <a href="#">キータグ項目からプランを探す</a>
          </div>
          <div class="form-body-area accordion-content">
            <form class="search_container" method="post">
              <input type="hidden" name="_token" :value="csrf">
              <div class="keytag-search">
                <div class="keytag-plan-search">
                  <div class="keytag-search">
                    <div class="search-item item-transportation">
                      <i class="fas fa-walking"></i>
                      <select name="transportation" v-model="search_key_form.plantag.transportation">
                        <option value="" name=""></option>
                        <option value="車">車</option>
                        <option value="電車">電車</option>
                        <option value="バス">バス</option>
                        <option value="徒歩">徒歩</option>
                        <option value="その他">その他</option>
                      </select>
                    </div>
                    <div class="search-item item-days">
                      <i class="bi bi-calendar-week-fill"></i>
                      <select name="duration" v-model="search_key_form.plantag.duration">
                        <option value=""></option>
                        <option value="1">1Day</option>
                        <option value="2">2Days</option>
                        <option value="3">3Days</option>
                        <option value="4">4Days</option>
                        <option value="Over 5Days">Over 5Days</option>
                      </select>
                    </div>
                  </div>
                  <div class="search-item item-address item-address-plan">
                    <i class="bi bi-geo-alt-fill icon-address"></i>
                    <input type="text" name="address" placeholder="所在地検索"  v-model="search_key_form.plantag.address">
                  </div>
                </div>
                <div class="search-button-wrapper search-button-plan">
                  <button type="submit" @click.prevent="search_axios('plan', search_key_form.plantag)"><i class="bi bi-search"></i> PLAN</button>
                </div>
              </div>
            </form>
          </div>
        </section>
        <section class="form-search-keytag">
          <div class="form-title-area accordion-title">
            <a href="#">キータグ項目からスポットを探す</a>
          </div>
          <div class="form-body-area accordion-content">
            <form class="search_container" method="post">
              <input type="hidden" name="_token" :value="csrf">
              <div class="keytag-search keytag-spot-search">
                <div class="search-item item-stay">
                  <i class="bi bi-stopwatch"></i>
                  <select name="stay" v-model="search_key_form.spottag.stay">
                    <option value=""></option>
                    <option value="0.5時間">0.5時間</option>
                    <option value="1時間">1時間</option>
                    <option value="1.5時間">1.5時間</option>
                    <option value="2時間">2時間</option>
                    <option value="2時間以上">2時間以上</option>
                  </select>
                </div>
                <div class="search-item item-address">
                  <i class="bi bi-geo-alt-fill icon-address"></i>
                  <input type="text" name="address" placeholder=" 所在地検索" v-model="search_key_form.spottag.address">
                </div>
                <div class="search-button-wrapper">
                  <button type="submit" @click.prevent="search_axios('spot', search_key_form.spottag)"><i class="bi bi-search"></i> SPOT</button>
                </div>
              </div>
            </form>
          </div>
        </section>
      </div>
    </div>
    <div class="search-result-area">
      <div v-if="result=='no_result'" class="search-error-result">
        <h4>検索結果はありません</h4>
      </div>
      <planitem-component
        v-else-if="result=='plan'"
        :response="response"
        :length="length"
        :search_key="search_key"
        pagetype="search"
      ></planitem-component>
      <spotitem-component
        v-else-if="result=='spot'"
        :response="response"
        :length="length"
        :search_key="search_key"
        pagetype="search"
      ></spotitem-component>
      <useritem-component
        v-else-if="result=='user'"
        :login_user="prop_login_uid"
        :response="response"
        :length="length"
        :search_key="search_key"
        pagetype="search"
      ></useritem-component>
    </div>
  </div>
</template>

<script>
  export default{
    props:[
      // 検索実行用キーワード
      'prop_search_key',
      'prop_login_uid',
    ],
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        result: '',
        response: [],
        login_user: '',
        length: '',
        // 検索画面表示時デフォルトでplanラジオボタンにするために設定
        search_type: 'plan',
        // 検索ボックス内データ保持用プロパティ
        search_key_form: {
          keyword: {
            search_word: '',
          },
          plantag: {
            transportation: '',
            duration: '',
            address: '',
          },
          spottag: {
            stay: '',
            address: '',
          }
        },
        // 子コンポーネント引き渡し用プロパティ
        search_key: {},
      };
    },
    computed: {
    },
    created: function(){
      // ホーム画面から検索が行われた場合、検索実行
      if(this.prop_search_key.length != 0){
        this.search_key_form.keyword = this.prop_search_key
        this.search_axios('plan', this.search_key_form.keyword);
      }
    },
    beforeUpdate: function(){
    },
    methods: {

      search_axios: function(search_type, search_key){
        console.log(search_key);
        let request = {};
        let that = this;

        // 子コンポーネントにてページネーションボタン押下時、検索キー送信できるようキーをセット
        this.search_key = search_key;

        request.page = 1;
        that.result = '';
        request.search_key = search_key;

        axios.post('/search/next' + search_type, request)
        .then(function(response){
          that.result = response.data.result;
          that.length = response.data.response_length;
          that.response = response.data.response;
        }).catch(function(error){
          console.log(error);
        })
      },

    },
  }
</script>

<style media="screen" scoped>
input, select, textarea{
  padding-left: 10px;
}

select {
  width: 100px;
}
</style>
