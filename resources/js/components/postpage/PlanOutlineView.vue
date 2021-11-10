<template>
  <div class="item">
    <div class="plan-outline-wrapper" v-bind:style="planOutline.displayStyle">
      <div class="plan-title">
        <h2>{{ planOutline.plan_title }}</h2>
      </div>
      <div class="plan-edit-button" v-if="postuser.id == login_uid">
        <!-- <a v-bind:href="'/post/edit/' + planOutline.id_DB" class="btn btn-secondary">編集</a> -->
        <form v-bind:action="'/post/delete/' + planOutline.id" method="post" enctype="multipart/form-data">
          <input type="hidden" name="_token" :value="csrf">
          <input type="submit" class="btn btn-secondary" value="削除">
        </form>
      </div>
      <div class="post-user-wrapper" v-else>
        <a class="plan-post-user" v-bind:href="'/mypage/' + postuser.id">POSTED BY {{ postuser.name }}</a>
      </div>
      <div class="plan-images-wrapper">
        <div class="main-image-wrapper">
          <img class="main-image outline-images" v-bind:src="mainImage">
        </div>
        <div class="sub-image-wrapper">
          <img class="sub-image outline-images" v-for="image in subImage" v-bind:src="image">
        </div>
      </div>
      <div class="plan-taginfo-area">
        <div class="plan-tag-area">
          <div class="plan-tag-header contents-header">
            <p>プランタグ</p>
          </div>
          <div class="plan-tag-wrapper">
            <i class="fas fa-suitcase-rolling"></i>
            <p class="tag-name">#{{ planOutline.plan_duration }}Day Plan</p>
          </div>
          <div class="plan-tag-wrapper">
            <i class="fas fa-walking"></i>
            <p class="tag-name">#{{ planOutline.main_transportation }}</p>
          </div>
          <div class="plan-tag-wrapper">
            <i class="fas fa-tags"></i>
            <p class="tag-name" v-for="tag in planOutline.tags">#{{ tag.name }}</p>
          </div>
          <div class="fav-button-area">
            <a v-if="planOutline.fav_status" class="btn btn-warning plan-unfav-btn" v-bind:href="'/index/unfavplan?planId=' + planOutline.id">お気に入り登録済み</a>
            <a v-else class="btn btn-warning plan-fav-btn" v-bind:href="'/index/favplan?planId=' + planOutline.id">お気に入り登録</a>
          </div>
        </div>
        <div class="plan-info-area">
          <div class="plan-info-header contents-header">
            <p>プラン情報</p>
          </div>
          <div class="plan-info">
            <p>{{ planOutline.plan_information }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default{
    props:[
      'planOutline',
      'dayInfo',
      'postuser',
      'login_uid',
      'csrf'
    ],
    data() {
      return {
        mainImage: '',
        subImage: [],
      };
    },
    created: function(){
      this.setImage(this.dayInfo);
    },
    methods: {
      setImage: function(dayInfo){

        dayInfo.forEach((day, d_index) => {
          day.spotInfo.forEach((spot, s_index) => {
            spot.images.forEach((image, i_index) => {
              if(d_index == 0 && s_index == 0 && i_index == 0){
                this.mainImage = image.image_path;
              }else if(this.subImage.length < 4){
                this.subImage.push(image.image_path);
              }
            });
          });
        });

        if(this.subImage.length < 4){
          do {
            this.subImage.push("../image/no_image.png")
          } while (this.subImage.length < 4);
        }

      },
    },
  }
</script>
