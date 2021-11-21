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
                <input v-model="search_word" type="text" size="25" name="search_word" placeholder="キーワード検索">
                <button type="submit" @click.prevent="search_axios(search_type, 'mainSearch')"><i class="bi bi-search"></i></button>
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
                      <select name="transportation" v-model="transportation">
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
                      <select name="duration" v-model="duration">
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
                    <input type="text" name="address" placeholder="所在地検索"  v-model="plan_address">
                  </div>
                </div>
                <div class="search-button-wrapper search-button-plan">
                  <button type="submit" @click.prevent="search_axios('plan', 'planSearch')"><i class="bi bi-search"></i> PLAN</button>
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
                  <select name="stay" v-model="stay">
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
                  <input type="text" name="address" placeholder=" 所在地検索" v-model="spot_address">
                </div>
                <div class="search-button-wrapper">
                  <button type="submit" @click.prevent="search_axios('spot', 'spotSearch')"><i class="bi bi-search"></i> SPOT</button>
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
        :parameter="parameter"
      ></planitem-component>
      <spotitem-component
        v-else-if="result=='spot'"
        :response="response"
        :length="length"
        :search_key="search_key"
        pagetype="search"
        :parameter="parameter"
      ></spotitem-component>
      <useritem-component
        v-else-if="result=='user'"
        :login_user="login_user"
        :response="response"
        :length="length"
        :search_key="search_key"
        pagetype="search"
        :parameter="parameter"
      ></useritem-component>
    </div>
  </div>
</template>

<script>
  export default{
    props:[
      'prop_response',
      'prop_search_key',
      'prop_parameter',
      'prop_length',
      'prop_result',
    ],
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        result: '',
        search_key: '',
        response: [],
        login_user: '',
        length: '',
        search_word: '',
        search_type: 'plan',
        transportation: '',
        duration: '',
        stay: '',
        plan_address: '',
        spot_address: '',
      };
    },
    computed: {
    },
    created: function(){
      console.log(this.prop_result);
      if(this.prop_result != undefined){
        this.result = this.prop_result;
        this.search_word = this.prop_search_key.search_word;
        if(this.prop_result != 'no_result'){
          this.parameter = this.prop_parameter;
          this.search_key = this.prop_search_key;
          this.length = this.prop_length;
          this.response = this.prop_response;
        }
      }
    },
    beforeUpdate: function(){
    },
    methods: {

      search_axios: function(search_type, search_mode){
        let request = {};
        let that = this;
        request.page = 1;
        request.parameter = search_type;
        that.result = '';

        switch (search_mode) {
          case 'mainSearch':
            request.search_word = this.search_word;
            break;

          case 'planSearch':
            request.transportation = this.transportation;
            request.duration = this.duration;
            request.address = this.plan_address;
            break;

          case 'spotSearch':
            request.stay = this.stay;
            request.address = this.spot_address;
            break;
        }

        axios.post('/search/next' + search_type, request)
        .then(function(response){
          console.log(response.data.search_key);
          console.log(response.data.parameter);
          console.log(that);
          // that.result = 'plan';
          that.result = response.data.result;
          that.parameter = response.data.parameter;
          that.search_key = response.data.search_key;
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

</style>
