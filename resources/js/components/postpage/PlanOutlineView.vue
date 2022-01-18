<template>
  <div class="item">
    <div class="plan-outline-wrapper" v-bind:style="planOutline.displayStyle">
      <div class="plan-title">
        <h2>{{ planOutline.plan_title }}</h2>
      </div>
      <div class="plan-editdelete-button" v-if="postuser.id == login_uid">
        <form v-bind:action="'/post/planedit/' + planOutline.id" method="get" enctype="multipart/form-data">
          <input type="hidden" name="_token" :value="csrf">
          <input type="submit" class="btn btn-secondary" value="編集">
        </form>
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

<style media="screen" lang="scss" scoped>

.btn-secondary{
  color: white;
}

.btn-success{
  color: white;
}

.item {
  min-width: 900px;
  width: 100%;
  height: 100%;
  min-height: calc(100vh - 60px);
  padding: 0 5%;
  background-color: #fff;
  box-shadow: 0 0 8px 0 rgb(0 0 0 / 15%);
  display: flex;
  justify-content: center;

  .plan-outline-wrapper {
    width: 100%;
    padding: 0 70px;

    .plan-title {
      padding-top: 50px;
      display: inline-block;

      h2 {
        border-bottom: solid 1px;
      }
    }

    .plan-editdelete-button {
      display: flex;
      justify-content: right;
      padding-right: 20px;

      input {
        margin-right: 5px;
        width: 100px;
      }
    }

    .post-user-wrapper {
      text-align: right;

      .plan-post-user {
        text-decoration: none;
        color: #3e3e3e;
      }

      .plan-post-user:hover {
        background-color: rgba(213, 218, 229, 0.9);
        transition: 0.4s;
      }
    }

    .plan-images-wrapper {
      margin: 20px 0 auto 0;
      width: 100%;
      height: 400px;
      display: flex;
      justify-content: center;

      .outline-images {
        padding: 2px 2px 0 0;
      }

      .main-image-wrapper {
        width: 450px;

        .main-image {
          height: 100%;
          width: 100%;
          border-radius: 10px;
          object-fit: cover;
        }
      }

      .sub-image-wrapper {
        width: 650px;
        display: flex;
        flex-wrap: wrap;

        .sub-image {
          height: 50%;
          width: 50%;
          border-radius: 10px;
          object-fit: cover;
        }
      }
    }

    .plan-taginfo-area {
      padding: 60px 0;
      width: 100%;
      display: flex;
      justify-content: space-around;

      .contents-header {
        margin-bottom: 35px;
        border-bottom: 1px solid rgb(85,85,85, 0.3);
      }

      .plan-tag-area {
        width: 37%;

        i {
          font-size: 25px;
          width: 50px;
        }

        .tag-name {
            display: inline-block;
            font-size: 15px;
            border-radius: 100vh;
            padding: 2px 10px;
            margin: 8px 10px 15px 0;
            color: #fff;
            background-color: #7dcafa;
        }
      }

      .plan-info-area {
        width: 50%;

        .plan-info {
          white-space: pre-wrap;
        }
      }

      .fav-button-area {
        margin-top: 30px;

        a {
          width: 100%;
        }

        .plan-unfav-btn {
          background-color: #FFF;
          color: #fcc800;
          font-weight: 600;
          border: solid 2px #fcc800;
        }
      }
    }

    .plan-map-area {
      margin: 30px 0 0 0;
      padding-bottom: 50px;

      .plan-map{
        border: solid 1px;
        height: 400px;
        width: 70%;
      }
    }
  }
}


</style>
