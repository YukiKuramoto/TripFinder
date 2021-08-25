<template id="">
  <form id="post-form" action="/post/create" method="post">
      <div>
        <div v-if="ErrorExist" id="error-msg">
          ＊は入力必須項目です
        </div>
        <div class="carousel">
          <div class="list" v-bind:style="_listStyle">
            <div class="list__item">
              <div class="item">
                <div class="content-title">PlanOutline</div>
                  <div class="title-area">
                    <div class="error-mark">{{ $store.state.planOutline.mark_title }}</div>
                    プランタイトル：
                    <input type="text"
                    name="plan[0][plan_title]"
                    class="user-input"
                    v-on:input="$store.state.planOutline.planTitle = $event.target.value"
                    v-bind:value="$store.state.planOutline.planTitle">
                  </div>
                  <div class="transportation-label">
                    <div class="title-area">
                      主要交通手段　：
                      <select name="plan[0][main_transportation]" class="user-input">
                        <option value="車">車</option>
                        <option value="電車">電車</option>
                        <option value="バス">バス</option>
                        <option value="徒歩">徒歩</option>
                        <option value="その他">その他</option>
                      </select>
                    </div>
                  </div>

                  <div class="plan-tag-area">
                    <div class="plan-textarea-title">
                      ハッシュタグ
                    </div>
                    <input
                    id="plantag-input"
                    class="hash-tag user-input"
                    name="plan[0][plan_tag]"
                    v-on:input="$store.state.planOutline.hashTag = $event.target.value"
                    v-bind:value="$store.state.planOutline.hashTag">
                    <div class="tagsinput">
                      <div class="tagsinput-child">
                        <input class="tagsinput-form" v-on:blur="registarPlanTag()">
                      </div>
                      <div class="tags_clear">
                      </div>
                    </div>
                  </div>
                  <div class="plan-information-area">
                    <div class="plan-textarea-title">
                      <div class="error-mark">{{ $store.state.planOutline.mark_info }}</div>
                      PLAN INFORMATION
                    </div>
                    <textarea class="plan-information-textarea user-input"
                    name="plan[0][plan_info]"
                    v-on:input="$store.state.planOutline.information = $event.target.value"
                    v-bind:value="$store.state.planOutline.information"
                    placeholder="プランの見所を投稿しよう！">
                    </textarea>
                  </div>
              </div>
            </div>
            <template class="spot-content" v-for="content in $store.state.contentsArray">
              <div class="list__item">
                <div class="item">
                  <div class="content-title">Spot{{ content.contentsKey + 1 }}</div>
                    <div class="title-area">
                      <div class="error-mark">{{ content.mark_title }}</div>
                      スポットタイトル：
                      <input type="text"
                      class="user-input"
                      v-on:input="content.contentsTitle = $event.target.value"
                      v-bind:name="'spot[' + content.contentsKey + '][spot_title]'"
                      v-bind:value="content.contentsTitle">
                    </div>
                    <div class="spot-detail-wrapper">
                      <div class="spot-detail-area">
                        <div class="spot-stay-area">
                          <div class="spot-area-title">
                            <div class="error-mark">{{ content.mark_duration }}</div>
                            滞在時間：
                          </div>
                          <select
                          v-bind:value="content.contentsDuration"
                          v-on:input="content.contentsDuration = $event.target.value"
                          v-bind:name="'spot[' + content.contentsKey + '][spot_duration]'"
                          class="user-input">
                            <option value="0.5時間">0.5時間</option>
                            <option value="1時間">1時間</option>
                            <option value="1.5時間">1.5時間</option>
                            <option value="2時間">2時間</option>
                            <option value="2時間以上">2時間以上</option>
                          </select>
                        </div>
                        <div class="spot-address-area">
                          <div class="spot-area-title">
                            <div class="error-mark">{{ content.mark_address }}</div>
                            所在地：
                          </div>
                          <input type="text"
                          class="user-input"
                          v-on:input="content.contentsAddress = $event.target.value"
                          v-bind:name="'spot[' + content.contentsKey + '][spot_address]'"
                          v-bind:value="content.contentsAddress">
                        </div>
                        <div class="spot-tag-area">
                          <div>
                            ハッシュタグ
                          </div>
                          <input
                          v-bind:id="'id' + content.contentsKey"
                          class="hash-tag user-input"
                          v-on:change="content.contentsTag = $event.target.value"
                          v-bind:name="'spot[' + content.contentsKey + '][spot_tag]'"
                          v-bind:value="content.contentsTag">
                          <div class="tagsinput">
                            <div class="tagsinput-child">
                              <input class="tagsinput-form" v-on:blur="registarSpotTag(content.contentsKey)">
                            </div>
                            <div class="tags_clear">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="spot-image-area">
                        <div class="spot-image-select">
                          写真を投稿：
                          <input type="file"
                          class="file-select"
                          v-on:input="SelectImage(content.contentsKey, $event.currentTarget)"
                          v-bind:name="'spot[' + content.contentsKey + '][spot_image]'"
                          multiple="multiple">
                        </div>
                        <div class="spot-image-view">
                          <img v-bind:src="content.spotImage[0]">
                        </div>
                      </div>
                    </div>
                    <div class="spot-information-area">
                      <div>
                        <div class="error-mark">{{ content.mark_info }}</div>
                        SPOT INFORMATION
                      </div>
                      <textarea class="spot-information-textarea user-input"
                      v-on:input="content.contentsInfo = $event.target.value"
                      v-bind:name="'spot[' + content.contentsKey + '][spot_info]'"
                      v-bind:value="content.contentsInfo"
                      placeholder="スポットの情報を投稿しよう！" >
                      </textarea>
                      <input v-bind:value="content.daykey + 1" v-bind:name="'spot[' + content.contentsKey + '][spot_day]'" style="display: none;">
                    </div>
                </div>
              </div>
            </template>
          </div>
          <div class="submit-button-area">
            <input type="hidden" name="_token" :value="csrf">
            <input type="submit" class="btn btn-success post-submit-button" value="投稿する">
          </div>
        </div>
      </div>
  </form>
</template>

<script>
export default{
  props:[
    'old',
    'errors',
  ],
  data() {
    return {
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      currentNum: 0,
    };
  },
  computed: {
    _listStyle() {
      return {
        transition: '',
        transform: `translatex(${-100 * this.$store.state.currentSlide}%)`,
      };
    },
    ErrorExist() {
      return this.$store.state.errorExist === 'Exist';
    }
  },
  beforeUpdate: function(){
    $('.hash-tag').tagsInput({width:'100%'});
  },
  methods: {
    SelectImage: function(Key, Target){
      this.contentsArray[Key].spotImage = [];

      if(Target.files.length > 0){
        const file = Target.files[0];
        const reader = new FileReader();

        reader.onload = (e) => {
          this.contentsArray[Key].spotImage.push(e.target.result);
        };
        reader.readAsDataURL(file);
      }
    },
    registarPlanTag: function(){
      const value = document.getElementById('plantag-input').value;
      this.$store.commit('registarPlanTag', value);
    },
    // registarSpotTag: function(key){
    //   const value = document.getElementById(`id${key}`).value;
    //   this.$store.commit('registarSpotTag', {key: key, value: value});
    // },
  },
}
</script>
