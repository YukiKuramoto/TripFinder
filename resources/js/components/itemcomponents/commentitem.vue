<template>
  <div class="comment-item-outer">
    <div class="comment-item" v-for="comment in comments">
      <div class="balloon2-right">
        <div class="comment-title">
          <p>{{ comment.comment_title }}</p>
        </div>
        <div class="comment-contents">
          <p>{{ comment.comment_content }}</p>
        </div>
      </div>
      <div class="comment-user-name">
        <div>
          <img :src="comment.user_image">
          <a v-if="comment.user_id == login_uid"
            class="comment-delete"
            :href="'/comment/delete/?comment_id=' + comment.id + '&plan_id=' + plan.id">
            削除
          </a>
          <p>by {{ comment.user_name }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default{
    props:[
      'plan',
      'comments',
      'login_uid',
    ],
    data() {
      return {
        mainImage: '',
        subImage: [],
      };
    },
    computed: {

    },
    created: function(){
      console.log(this.plan.id)
    },
    mounted: function(){

    },
    methods: {

    },
  }
</script>

<style scoped lang="scss">

.comment-item {
  display: flex;
  justify-content: center;

  .comment-title {
    margin-bottom: 8px;

    p{
      font-weight: 700;
    }
  }

  .comment-user-name {
    display: flex;
    flex-direction: row;
    align-items: flex-end;
    padding: 0 0 0px 5px;;
    width: 130px;

    img {
      width: 40px;
      height: 40px;
      border-radius: 10px;
    }

    .comment-delete{
      color: #555;
      margin-left: 5px;
    }
  }
}

.balloon2-right {
  position: relative;
  display: inline-block;
  margin: 0 15px 15px 0;
  padding: 7px 10px;
  width: 450px;
  min-height: 95px;
  color: #555;
  font-size: 16px;
  background: #FFF;
  border: solid 1px rgb(85,85,85, 0.3);
  border-radius: 5px;
  box-sizing: border-box;
}

.balloon2-right:before {
  content: "";
  position: absolute;
  top: 70%;
  right: -24px;
  margin-top: -12px;
  border: 12px solid transparent;
  border-left: 12px solid #FFF;
  z-index: 2;
}

.balloon2-right:after {
  content: "";
  position: absolute;
  top: 70%;
  right: -28px;
  margin-top: -14px;
  border: 14px solid transparent;
  border-left: 14px solid rgb(85,85,85, 0.2);
  z-index: 1;
}

.balloon2-right p {
  font-size: 14px;
  margin: 0;
  padding: 0;
}

</style>
