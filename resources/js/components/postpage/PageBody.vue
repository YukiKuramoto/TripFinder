<template>
  <div id="post-body">
    <div id="post-menu" v-show="type == 'post' || type == 'view'">
      <div>
        <div id="submit-button-area">
          <input v-if="type == 'post'" type="hidden" name="_token" :value="csrf">
          <input v-if="type == 'post'" type="button" class="btn btn-success post-submit-button" id="post-button" @click="SendData" value="post your plan">
          <input v-else-if="type == 'planedit' || type == 'spotedit'" type="hidden" name="_token" :value="csrf">
          <input v-else-if="type == 'planedit' || type == 'spotedit'" type="button" class="btn btn-success post-submit-button" id="post-button" @click="EditData" value="編集する">
        </div>
        <div v-show="type == 'post'" id="button-area">
          <button id="dayadd-button" class="btn btn-secondary btn-submit" v-on:click.prevent="addDay">Add Day</button>
          <button id="daydelete-button" class="btn btn-secondary btn-submit" v-on:click.prevent="deleteDay">Delete Day</button>
        </div>
        <section>
          <h2 class="accordion-title"><a href="#" v-on:click.prevent="showSpot">PlanOutline</a></h2>
          <div class="accordion-content accordion-content-active" v-on:click="carouselMove(-1)">PlanOutline</div>
        </section>
        <section v-for="day in dayInfo">
          <h2 class="accordion-title"><a href="#!" v-on:click.prevent="showSpot">Day{{ day.day_count + 1 }}</a></h2>
          <div v-for="(spot, index) in day.spotInfo">
            <div class="accordion-content" :style=spot.spot_accordion_display v-on:click.prevent="carouselMove(spot.spot_count)">
              <div class="accordion-content-area">
                <div class="spot-area">
                  Spot{{ index + 1 }}
                </div>
                <div v-if="type=='post' && index == day.spotInfo.length-1" class="icon-area">
                  <i class="bi bi-plus-square plus-button" v-on:click.prevent="addSpot(day.day_count)"></i>
                  <i class="bi bi-dash-square minus-button" v-on:click.prevent="deleteSpot(day.day_count)"></i>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
    <form :id="'post-form-' + type" action="/post/create" method="post" enctype="multipart/form-data">
      <div>
        <div v-if="errorExist" class="error-msg" :id="'error-msg-' + type">
          ＊は入力必須項目です
        </div>
        <div class="carousel">
          <div :class="'list_' + type " v-bind:style="_listStyle">
            <div v-if="type=='post' || type=='planedit'" class="list__item item_post">
              <planoutline-post-component
              :planOutline=planOutline
              :type=type
              ref="child_outline"
              @outlineUpdate='outlineDataUpdate'
              ></planoutline-post-component>
            </div>
            <template v-if="type=='post' || type=='spotedit'" v-for="day in dayInfo">
              <div v-for="(spot, index) in day.spotInfo" class="spot-content list__item item_post">
                <spot-post-component
                :spot=spot
                :spotIndex=index
                :type=type
                ref="child_spot"
                @spotUpdate='spotDataUpdate'
                ></spot-post-component>
              </div>
            </template>
            <div v-if="type=='view'" class="item_view">
              <planoutline-view-component
              :planOutline= planOutline
              :dayInfo= dayInfo
              :postuser = postuser_view
              :login_uid = loginuid_view
              :csrf = csrf
              ></planoutline-view-component>
            </div>
            <template v-if="type=='view'" v-for="day in dayInfo">
              <div v-for="(spot, key) in day.spotInfo" class="spot-content item_view">
                <spot-view-component
                :showstyle="spot.spot_display"
                :planOutline=planOutline
                :spot=spot
                :spotkey=key
                :postuser = postuser_view
                :login_uid = loginuid_view
                :csrf = csrf
                ></spot-view-component>
              </div>
            </template>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>

  window.onkeydown = function(e) {
    if (e.keyCode == 9)
      return false; // Disable Tab!
  }


  export default{
    props:[
      'plandata',
      'spotdata',
      'postuser_view',
      'loginuid_view',
      'type',
    ],
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        maxWidth: 300,
        dayInfo: [{
          day_count: 0,
          spotInfo: [{
            spot_day: 0,
            spot_count: 0,
            spot_display: '',
            spot_image_preview: [],
            spot_image: [],
            spot_link: [{}, {}, {}],
            spot_accordion_display: 'display:none'
          }],
        }],
        currentNum: 0,
        planOutline: {
          main_transportation: '車',
        },
        errorExist: false,
      };
    },
    computed: {
      // this.currentNumを用いて右方向にどれだけスライドさせるかを算出する算出プロパティ
      _listStyle() {
        return {
          transform: `translatex(${-100 * this.currentNum}%)`,
        };
      },
    },
    /**
     * ページ構成時初期値セット用関数
     * コンポーネント呼び出しtypeによって初期値セットを切り分け
     * view     : （投稿閲覧用）サーバーからリターンされた投稿情報と初期cssとして{display: none}をセット
     * planedit : （プラン編集用）サーバーからリターンされたプラン情報をセット
     * spotedit : （スポット編集用）サーバーからリターンされたスポット情報をセットし、空の画像用配列セット
     */
    created: function(){

      switch (this.type) {
        case 'view':
          this.planOutline = this.plandata;
          this.dayInfo = this.spotdata;
          this.setStyle(this.dayInfo);
          break;

        case 'planedit':
          this.planOutline = this.plandata;
          break;

        case 'spotedit':
          this.dayInfo[0].spotInfo[0] = this.spotdata;
          this.dayInfo[0].spotInfo[0].spot_image = [];
          break;

        default:
      }
    },
    // /**
    //  * vueインスタンスアップデート前実行関数
    //  * Day、Spot追加時にタグ投稿用のインプット要素幅設定
    //  */
    // beforeUpdate: function(){
    //   $('.hash-tag').tagsInput({
    //     width:'100%'
    //   });
    // },
    methods: {

      /**
       * 投稿閲覧画面用に各スポット情報カルーセルのCss初期値をセットする関数
       * ページ読み込み時はプランアウトラインがメインで表示されるためスポットDisplayはnone
       */
      setStyle: function(dayInfo){
        this.dayInfo.forEach(day => {
          day.spotInfo.forEach(spot => {
            this.$set(spot,'spot_display','display: none');
          });
        });
      },

      /**
       * ユーザークリック対象に応じ、カルーセルをスライドさせ、CSSを適用する関数
       * クリックされたスポットのスポットキーが -1 の場合プランアウトライン表示用CSS
       * クリックされたスポットのスポットキーが 1 以上の場合スポット表示用CSS
       */
      carouselMove: function(spotKey){
        //currentNumを更新することで算出プロパティ実行させ、スライドさせる
        this.currentNum = spotKey + 1

        //クリックされた内容がプランアウトラインだった場合
        if(spotKey == -1){
          //プランアウトラインを表示
          this.planOutline.displayStyle = 'display: block';
          //各スポットのspot_displayプロパティに高さ0をセット
          this.dayInfo.forEach(day => {
            day.spotInfo.forEach(spot => {
              this.$set(spot,'spot_display', 'height: 0');
            });
          });
        //クリックされた内容がスポットだった場合
        }else{
          //プランアウトラインの高さを0にセット
          this.planOutline.displayStyle = 'height: 0';
          this.dayInfo.forEach((day, d_index) => {
            day.spotInfo.forEach((spot, s_index) => {
              //クリックされたスポットにはdisplay:blockをセット
              if(spot.spot_count == spotKey){
                this.$set(spot,'spot_display', 'display: block');
              //クリックされたスポット以外にはheight:0をセット
              }else{
                this.$set(spot,'spot_display', 'height: 0');
              }
            });
          });
        }
      },

      /**
       * 「addDay」ボタン押下時の新しい空の「Day」と「Spot」追加処理
       */
      addDay: function() {
        let newdayIndex = this.dayInfo.length;

        if (newdayIndex === 6){
          alert('追加できる日数は6日までです');
          return;
        }

        //dayInfoの配列要素数が日数になるため、最終要素番号をday_countにセット
        //空の情報・デフォルトの情報をspotInfoにセット
        this.dayInfo.push({
          day_count: newdayIndex,
          spotInfo: [{
            spot_image_preview: [],
            spot_image: [],
            spot_duration: '0.5時間',
            spot_link: [{}, {}, {}],
          }]
        });

        //新スポット追加により各スポットを採番し直し
        this.assignKey();
      },
      /**
       * 「Delete Day」ボタン押下時のDay,Spot削除処理
       */
      deleteDay: function() {
        let targetIndex = this.dayInfo.length - 1;

        //要素がひとつしかない場合は実行しない
        if(targetIndex == 0){
          return;
        }

        //削除処理
        this.dayInfo.splice(targetIndex, 1);
        let spotArray = this.dayInfo[this.dayInfo.length - 1].spotInfo;
        //削除後スライド表示をひとつ前に戻す
        this.currentNum = spotArray[spotArray.length - 1].spot_count + 1;
      },

      /**
       * プラスボタンクリック時のスポット追加処理
       */
      addSpot: function(dayIndex) {

        if(this.dayInfo[dayIndex].spotInfo.length === 4){
          alert('追加できるスポットは1日あたり4つまでです');
          return;
        }

        //デフォルトCSS、デフォルト滞在時間、空の情報をdayInfo内対象インデックスのspotInfoに追加
        this.dayInfo[dayIndex].spotInfo.push({
          spot_accordion_display: `display: block`,
          spot_image: [],
          spot_image_preview: [],
          spot_duration: '0.5時間',
          spot_link: [{}, {}, {}],
        });

        //新スポット追加により採番し直し
        this.assignKey();
      },

      /**
       * ×ボタン押されたスポットの削除処理
       */
      deleteSpot: function(dayIndex){
        // 削除対象のスポット対象インデックスを取得
        let targetIndex = this.dayInfo[dayIndex].spotInfo.length - 1;
        // 最初のスポットは削除不可のためリターン
        if(targetIndex == 0){
          return;
        }else{
          this.dayInfo[dayIndex].spotInfo.splice(targetIndex, 1);
        }
        // 削除後のキーの採番処理
        this.assignKey();
      },
      /**
       * インデックス追加、削除後の採番処理関数
       */
      assignKey: function(){

        const dayArray = this.dayInfo;
        let keyCount = 0;

        // 繰り返し処理で要素一個ずつ採番
        for(let i=0; i<dayArray.length; i++){
          for(let j=0; j<dayArray[i].spotInfo.length; j++){
            dayArray[i].spotInfo[j].spot_count = keyCount;
            dayArray[i].spotInfo[j].spot_count = keyCount;
            dayArray[i].spotInfo[j].spot_day = i;
            keyCount++;
          }
        }
      },
      /**
       * メニューバーのアコーディオンメニュー表示処理
       */
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
      /**
       * 子コンポーネントからの$emitを受けプランアウトラインデータ更新
       */
      outlineDataUpdate: function(childData){
        this.planOutline = childData;
      },
      /**
       * 子コンポーネントからの$emitを受けスポットデータ更新
       * childData: 更新データ
       * spot_count_component: 更新対象は何個目のスポット情報かを表すカウント
       */
      spotDataUpdate: function(childData, spot_count_component){
        if(this.type=='post'){
          for(let i=0;i<this.dayInfo.length;i++){
            for(let j=0; j<this.dayInfo[i].spotInfo.length; j++){
              if(this.dayInfo[i].spotInfo[j].spot_count == spot_count_component){
                this.dayInfo[i].spotInfo[j] = childData;
              }
            }
          }
        }else if(this.type=='spotedit'){
          this.dayInfo[0].spotInfo[0] = childData;
        }
      },
      /**
       * 新規投稿時のAxios通信処理
       */
      SendData: function(){

        // リクエストパラメータ
        let params = {};
        // then句の中でVueインスタンスにアクセスできるよう代入
        let that = this;
        // リクエスト内容送信用フォームデータ
        var formData = new FormData();
        // 送信用config
        var config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        };

        // Formdataに画像オブジェクトappend時多次元配列かできず、各画像がどの日どのスポットか解らなくなるため
        // プロパティの名称で判別できるよう、インデックス番号をプロパティ名称にセット
        this.dayInfo.forEach(day => {
          let dayIndex = this.dayInfo.indexOf(day);
          day.spotInfo.forEach(spot => {
            let spotIndex = day.spotInfo.indexOf(spot);
            spot.spot_image.forEach(image => {
              let imageIndex = spot.spot_image.indexOf(image);
              formData.append(`${dayIndex}_${spotIndex}_${imageIndex}`, image);
            });
          })
        })

        // プランアウトライン情報をパラメータにセット
        params.planOutline = this.planOutline;
        // スポット情報をパラメータにセット
        params.dayInfo = this.dayInfo;
        // パラメータオブジェクトをJSON形式に変換
        params = JSON.stringify(params);
        // リクエストプロパティとしてパラメータをセット
        formData.append('request', params);

        // ボタンの非活性化、送信中メッセージ表示
        $('#post-button').val('now posting...');
        $("#post-button").attr('disabled', true);

        // axiosにて投稿データFormDataを送信
        axios.post('/post/create', formData, config)
        .then(function (response) {
          // ステータスコード200が返ってきたら成功としてページ遷移
          if(response.request.status == 200){
            window.location.href = '/index/' + response.data.plan_id;
          };
        })
        .catch(function (error) {
          // ステータスコードが422なら失敗としてエラーメッセージ表示し、ボタン活性化
          if(error.response.status == 422){
            that.errorExist = true;
            $('#post-button').val('retry post');
            $("#post-button").attr('disabled', false);
          }
        });
      },
      /**
       * 既存投稿編集時のaxios通信
       */
      EditData: function(){

        // リクエストパラメータ
        let params = {};
        // then句の中でVueインスタンスにアクセスできるよう代入
        let that = this;
        // 編集対象特定用にidをセット
        let id;
        // ボタンの非活性化、送信中メッセージ表示
        var config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        };

        switch (this.type) {

          case 'spotedit':
            // スポット編集用パラメータ作成関数呼び出し
            params = this.CreateSpotParams();
            // 編集対象スポットidをセット
            id = this.dayInfo[0].spotInfo[0].id;
            break;

          case 'planedit':
            // プラン編集用パラメータ作成関数呼び出し
            params = this.CreatePlanParams();
            // 編集対象プランidをセット
            id = this.planOutline.id;
            break;
        }

        // ボタンの非活性化、送信中メッセージ表示
        $('#post-button').val('now posting...');
        $("#post-button").attr('disabled', true);

        // axiosにて投稿データFormDataを送信
        axios.post('/post/' + this.type + '/' + id, params, config)
        .then(function (response) {
          // ステータスコード200が返ってきたら成功としてページ遷移
          if(response.request.status == 200){
            window.location.href = '/index/' + response.data.plan_id;
          };
        })
        .catch(function (error) {
          // ステータスコードが422なら失敗としてエラーメッセージ表示し、ボタン活性化
          if(error.response.status == 422){
            that.errorExist = true;
            $('#post-button').val('retry post');
            $("#post-button").attr('disabled', false);
          }
        });
      },
      /**
       * プランパラメータ作成
       */
      CreatePlanParams: function(){
        let params = {};
        let formData = new FormData();

        params.planOutline = this.planOutline;
        params = JSON.stringify(params);
        formData.append('request', params);

        return formData;
      },
      /**
       * スポットパラメータ作成
       */
      CreateSpotParams: function(){

        let params = {};
        let count = 0;
        var formData = new FormData();

        this.dayInfo[0].spotInfo[0].spot_image.forEach(image => {
          formData.append(`spot_image_${count}`, image)
          count++;
        });

        params.dayInfo = this.dayInfo[0].spotInfo[0];
        params = JSON.stringify(params);
        formData.append('request', params);

        return formData;
      },
    },
  }
</script>

<style media="screen" lang="scss" scoped>

#post-body{
    padding-top: 60px;
    width: 100vw;
    display: flex;
    min-height: 800px;
}

#post-menu {
    height: calc(100vh - 60px);
    width: 250px;
    background-color: #fff;
    box-shadow: 0 0 8px 0 rgb(0 0 0 / 15%);
    position: fixed;
    overflow-y: auto;
    z-index: 80;

    #submit-button-area {
      background-color: #f0f0f0;
      text-align: center;

      input {
        margin-top: 15px;
        width: 200px;
      }
    }

    #button-area {
        padding: 0 15px;
        background-color: #f0f0f0;
        border-bottom: 1px solid #ccc;
        display: flex;
        justify-content: space-around;

        button {
          padding: 7px 2px;
          margin: 20px 0;
          width: 90px;
          font-size: 13px;
        }
    }
}

.btn-secondary{
  color: white;
}

.btn-success{
  color: white;
}

.error-msg {
    z-index: 2000;
    position: fixed;
    text-align: center;
    height: 30px;
    background-color: rgba(255,182, 193);
    padding: 5px;
    color: #fff;
}

.carousel {
  overflow: hidden;

  .list_post {
    width: calc(100vw - 250px);
    margin: 0 auto;
    white-space: nowrap;
    font-size: 0;
    transition: transform 0.5s;
    min-height: 800px;

    .item_post {
      display: inline-block;
      vertical-align: top;
      width: 100%;
      height: 100%;
      font-size: 16px;
      padding: 40px 5em 25px 5em;
    }
  }

  .list_spotedit {
    padding: 50px 15em 30px 15em;
  }

  .list_planedit {
    padding: 50px 15em 30px 15em;
  }

  .list_view {
    min-height: calc(100vh - 60px);
    margin: 0 auto;
    white-space: nowrap;
    font-size: 0;
    transition: transform 0.5s;

    //コンテンツが見切れないために必要
    .item_view {
      white-space: normal;
      overflow: scroll;
    }

    .item_view {
        display: inline-block;
        vertical-align: top;
        width: 100%;
        height: 100%;
        font-size: 16px;
      }
  }
}

.submit-button-area {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;

  .post-submit-button {
    width: 300px;
  }
}


//呼び出しモードによってCSSを切り替え

//投稿モード「postpage」時のCSS
#error-msg-post{
  width: calc(100vw - 250px);
}

#post-form-post {
  width: calc(100vw - 250px);
  margin-left: 250px;
}

//編集モード「planedit」時のCSS
#error-msg-planedit{
  width: 100%;
}

#post-form-planedit {
  width: 100%;
}

//編集モード「spotedit」時のCSS
#error-msg-spotedit{
  width: 100%;
}

#post-form-spotedit {
  width: 100%;
}

//閲覧モード「view」時のCSS
#post-form-view {
  width: calc(100vw - 250px);
  margin-left: 250px;
}


</style>
