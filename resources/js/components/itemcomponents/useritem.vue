<template>
  <div class="users-contents-outer">
    <v-app id="inspire">
      <div class="text-center">
        <v-pagination
        v-model="page_current"
        :length="page_length"
        :total-visible="7"
        prev-icon="mdi-menu-left"
        next-icon="mdi-menu-right"
        circle
        @input="getNextpage"
        ></v-pagination>
      </div>
    </v-app>
    <div class="users-contents-wrapper">
      <div v-for="user in page_users" class="users-item-wrapper">
        <a :href="'/mypage/' + user.id"  class="anker-area">
          <div class="user-image-area">
            <img v-if="user.image_path !== null" :src="user.image_path">
            <img v-else src="/image/default.png">
          </div>
          <div class="user-name-area">
            <p>{{ user.name }}</p>
          </div>
          <div class="user-comment-area">
            <p v-if="user.comment !== null">{{ user.comment.substr(0,30) }}</p>
          </div>
        </a>
        <div v-if="login_user != 'undefined_user'">
          <form v-if="user.id == login_user" class="follow-button-area" :action="'/users/unfollow/' + user.id">
            <button type="submit" class="btn btn-secondary follow-btn" disabled>フォローする</button>
          </form>
          <form v-else-if="user.follow_flg" class="follow-button-area" :action="'/users/unfollow/' + user.id">
            <button type="submit" class="btn btn-secondary following-btn">フォロー中</button>
          </form>
          <form v-else class="follow-button-area" :action="'/users/follow/' + user.id">
            <button type="submit" class="btn btn-secondary follow-btn">フォローする</button>
          </form>
        </div>
        <div class="icon-area">
          <i class="bi bi-star-fill icon"></i>{{ user.followers.length }} followers
          <i class="bi bi-file-earmark-post-fill icon"></i>{{ user.plans.length }} posts
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    props: [
      'login_user',
      'response',
      'length',
      'search_key',
      'pagetype',
      'parameter',
    ],
    data() {
        return {
          page_current: '',
          page_length: '',
          page_users: '',
          page_currentUser: '',
        }
    },
    created: function(){
      console.log(this.response);
      this.page_current = 1;
      this.page_length = this.length;
      this.page_users = this.response;
    },
    methods: {
      getNextpage: function(){

        console.log(this.page_current);
        let params = {};
        let that = this;
        params.page = this.page_current;
        params.parameter = this.parameter;
        params.search_word = this.searchword;

        axios.post('/' + this.pagetype +'/nextuser', params)
        .then(function(response){
          console.log(response.data);
          that.page_length = response.data.response_length;
          that.page_users = response.data.response;
        }).catch(function(error){
          console.log(error);
        });
      }
    }
}
</script>

<style scoped>


.v-application {
  height: 0px;
}
.v-application--wrap {
  min-height: 0px;
}

.container {
  padding: 0px;
}

.users-contents-outer {
  padding: 0 20px 30px 20px;
  max-width: 1200px;
}

.users-contents-wrapper {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  margin-top: 50px;
}

.users-item-wrapper:hover {
  transform: scale(1.03);
}

.users-item-wrapper {
  border: solid 2px rgba(204, 204, 204, 0.9);
  border-radius: 10px;
  box-shadow: 0 8px 10px 0 rgba(0, 0, 0, .3);
  width: 300px;
  margin: 30px 20px;
  padding-bottom: 10px;
}

.anker-area {
  text-decoration: none;
  color: #24140e;
}

.user-image-area {
  display: flex;
  justify-content: center;
  margin: 20px 0;
}

.user-image-area img {
  width: 140px;
  height: 140px;
  border-radius: 50%;
}


.user-name-area {
  text-align: center;
}

.user-name-area p {
  font-size: 25px;
  font-weight: 700;
  margin: 0;
}


.user-comment-area {
  text-align: center;
  padding: 0 20px 10px 20px;
  height: 50px;
}

.user-comment-area p {
  font-size: 15px;
  margin: 0;
}


.follow-button-area {
  width: 100%;
  padding: 0px 35px;
  margin-bottom: 10px;
}

.following-btn {
  background-color: #FFF;
  color: #6c757d;
  font-weight: 600;
  border: solid 2px #6c757d;
}

.follow-btn {
  color: #FFF;
}

button {
  width: 100%;
}


.icon-area {
  text-align: center;
}

.bi-star-fill {
  color: #ffd700;
}

.bi-file-earmark-post-fill {
  color: #436be0;
}

.icon {
  margin:0 10px;
}


@media (min-width: 1301px) {
  .users-contents-wrapper {
    width: 1050px;
  }
}

@media (max-width: 1300px) {
  .users-contents-wrapper {
    width: 700px;
  }
}

</style>

<style media="screen">
.v-application--wrap {
  min-height: 0px;
}
</style>
