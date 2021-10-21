<template>
  <div id="post-body">
      <div id="post-menu">
          <div>
            <section>
                <h2 class="accordion-title"><a href="#" v-on:click.prevent="showSpot">PlanOutline</a></h2>
                <div class="accordion-content accordion-content-active" v-on:click="carouselMove(-1)">PlanOutline</div>
            </section>
            <section v-for="day in dayInfo">
                <h2 class="accordion-title"><a href="#!" v-on:click.prevent="showSpot">Day{{ day.dayKey }}</a></h2>
                <div v-for="spot in day.spotInfo">
                  <div class="accordion-content" v-bind:style="spot.spotDisplay" v-on:click.prevent="carouselMove(spot.spotKey)">
                    <div class="accordion-content-area">
                      <div class="spot-area">
                        Spot{{ spot.spotKey + 1 }}
                      </div>
                    </div>
                  </div>
                </div>
            </section>
          </div>
      </div>
      <div id="post-form">
        <div>
          <div class="carousel">
            <div class="list" v-bind:style="_listStyle">
              <div class="list__item">
                <div class="item">
                  <div class="plan-outline-wrapper" v-bind:style="planOutline.displayStyle">
                    <div class="plan-title">
                      <h2>{{ planOutline.planTitle }}</h2>
                    </div>
                    <div class="plan-edit-button" v-if="postuser.id == login_uid">
                      <!-- <a v-bind:href="'/post/edit/' + planOutline.id_DB" class="btn btn-secondary">編集</a> -->
                      <form v-bind:action="'/post/delete/' + planOutline.id_DB" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" :value="csrf">
                        <input type="submit" class="btn btn-secondary" value="削除">
                      </form>
                    </div>
                    <div class="post-user-wrapper" v-if="postuser.id != login_uid">
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
                          <p class="tag-name">#{{ planOutline.planDuration }}Day Plan</p>
                        </div>
                        <div class="plan-tag-wrapper">
                          <i class="fas fa-walking"></i>
                          <p class="tag-name">#{{ planOutline.planTransportation }}</p>
                        </div>
                        <div class="plan-tag-wrapper">
                          <i class="fas fa-tags"></i>
                          <p class="tag-name" v-for="tag in planOutline.tags">#{{ tag.name }}</p>
                        </div>
                        <div class="fav-button-area">
                          <a v-if="planOutline.favStatus" class="btn btn-warning plan-unfav-btn" v-bind:href="'/index/unfavplan?planId=' + planOutline.id_DB">お気に入り登録済み</a>
                          <a v-else class="btn btn-warning plan-fav-btn" v-bind:href="'/index/favplan?planId=' + planOutline.id_DB">お気に入り登録</a>
                        </div>
                      </div>
                      <div class="plan-info-area">
                        <div class="plan-info-header contents-header">
                          <p>プラン情報</p>
                        </div>
                        <div class="plan-info">
                          <p>{{ planOutline.information }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <template class="spot-content" v-for="content in contentsInfo">
                <div class="list__item">
                  <div class="item">
                    <div class="spot-wrapper"  v-bind:style="content.displayStyle">
                      <div class="plan-title">
                        <h2>{{ planOutline.planTitle }}</h2>
                      </div>
                      <div class="spot-image-outline-wrapper">
                        <div class="spot-image-wrapper">
                          <div class="spot-main-image-wrapper">
                            <img class="spot-main-image" v-bind:src="content.spotImage.mainImage.image_path">
                          </div>
                          <div class="spot-sub-image-wrapper">
                            <img class="spot-sub-image" v-for="image in content.spotImage.subImage" v-bind:src="image.image_path">
                          </div>
                        </div>
                        <div class="spot-outline-wrapper">
                          <div class="spot-title-wrapper">
                            <h3><span>Spot.{{ content.spotkey+1 }}</span>{{ content.contentsTitle }}</h3>
                          </div>
                          <div class="spot-detail-wrapper">
                            <div class="spot-address-wrapper spot-detail-item">
                              <i class="bi bi-geo-alt-fill icon-address"></i>{{ content.contentsAddress }}
                            </div>
                            <div class="spot-duration-wrapper spot-detail-item">
                              <i class="bi bi-stopwatch"></i>
                              <p class="tag-name">#{{ content.contentsDuration }}</p>
                            </div>
                            <div class="spot-tag-wrapper spot-detail-item">
                              <i class="fas fa-tags"></i>
                              <p class="tag-name" v-for="tag in content.contentsTag">#{{ tag.name }}</p>
                            </div>
                            <div class="spot-detail-item">
                              <a v-if="content.favStatus" class="spot-fav-button btn btn-warning spot-unfav-btn" v-bind:href="'/index/unfavspot?planId=' + planOutline.id_DB + '&spotId=' + content.id_DB">登録済み</a>
                              <a v-else class="spot-fav-button btn btn-warning" v-bind:href="'/index/favspot?planId=' + planOutline.id_DB + '&spotId=' + content.id_DB">行きたいスポット</a>
                              <a class="spot-fav-button btn btn-secondary" v-bind:href="'/comment/create?spotId=' + content.id_DB">コメント投稿</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="spot-infocomment-wrapper">
                        <div class="spot-info-wrapper">
                          <div class="spot-info-header contents-header">
                            <p>スポット情報</p>
                          </div>
                          <div class="spot-info-contents">
                            <p>{{ content.contentsInfo }}</p>
                          </div>
                        </div>
                        <div class="spot-comment-wrapper">
                          <div class="spot-comment-header contents-header">
                            <p>コメント</p>
                          </div>
                          <div class="comment-item" v-for="comment in content.contentsComment">
                            <div class="balloon2-right">
                              <div class="comment-title">
                                <p>{{ comment.commentTitle }}</p>
                              </div>
                              <div class="comment-contents">
                                <p>{{ comment.commentContents }}</p>
                              </div>
                            </div>
                            <div class="comment-user-name">
                              <div class="">
                                <img v-bind:src="comment.commentUserImage">
                                <p>by {{ comment.commentUser }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="spot-map-wrapper">
                        <div class="spot-map-header contents-header">
                          <p>MAP</p>
                        </div>
                        <div class="map" v-bind:id="'map' + content.contentsKey"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
  export default{
    props:[
      'plan',
      'spot',
      'postuser',
      'login_uid',
    ],
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        dayInfo: [
          // {
          // dayKey: 0,
          // spotInfo: [
          //   {
          //   spotKey: 0,
          //   spotDisplay: ''
          // }
        // ],
        // }
      ],
        contentsInfo: [
        //   {
        //   contentsKey: 0,
        //   daykey: 0,
        //   spotkey: 0,
        //   contentsTitle: '',
        //   contentsDuration: '',
        //   contentsAddress: '',
        //   contentsTag: '',
        //   contentsInfo: '',
          // spotImage: [{
          //   mainImage: '',
          //   spotImage: [],
          // }],
        // }
      ],
        planOutline: {
          id_DB: '',
          planTitle: '',
          hashTag: '',
          information: '',
          tags: [],
        },
        currentNum: 0,
        mainImage: '',
        subImage: [],
      };
    },
    computed: {
      _listStyle() {
        return {
          transition: '',
          transform: `translatex(${-100 * this.currentNum}%)`,
        };
      },
    },
    created: function(){
      console.log(this.spot);
      console.log(this.plan);
      this.setPlan(this.plan);
      this.setSpot(this.spot);
      this.setImage(this.spot);
      this.setStyle(this.spot);
    },
    mounted: function(){
      this.setMap();
    },
    methods: {
      setMap() {
        for(let i = 0; i<this.contentsInfo.length; i++){
          this.initMapWithAddress(this.contentsInfo[i].contentsKey, this.contentsInfo[i].contentsAddress);
        }
      },
      initMapWithAddress(key, address) {
        var opts = {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
        var my_google_map = new google.maps.Map(document.getElementById(`map${key}`), opts);
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode(
          {
            'address': address,
            'region': 'jp'
          },
          function(result, status){
            if(status==google.maps.GeocoderStatus.OK){
                var latlng = result[0].geometry.location;
                my_google_map.setCenter(latlng);
                var marker = new google.maps.Marker({position:latlng, map:my_google_map, title:latlng.toString(), draggable:true});
                google.maps.event.addListener(marker, 'dragend', function(event){
                    marker.setTitle(event.latLng.toString());
                });
            }
          }
        );
      },
      setStyle: function(spots){
        // this.planOutline.displayStyle = 'display: block';
        for(let i = 0; i < spots.length; i++){
          this.contentsInfo[i].displayStyle = 'height: 0';
        }
      },
      setSpot: function(spots){

        for(let i = 0; i < spots.length; i++){
          if(this.dayInfo[spots[i].spot_day - 1] === undefined){
            this.dayInfo.push({
              dayKey: spots[i].spot_day,
              spotInfo: [],
            })
          }

          this.dayInfo[spots[i].spot_day - 1].spotInfo.push({
            spotKey: i,
            spotDisplay: 'none',
          })

          this.contentsInfo.push({
            id_DB: spots[i].id,
            contentsKey: i,
            daykey: spots[i].spot_day,
            spotkey: i,
            favStatus: spots[i].fav_status,
            contentsTitle: spots[i].spot_title,
            contentsDuration: spots[i].spot_duration,
            contentsAddress: spots[i].spot_address,
            contentsTag: spots[i].tags,
            contentsInfo: spots[i].spot_information,
            contentsComment: [],
            spotImage: {
              mainImage: '',
              subImage: [],
            },
          })

          let count;
          if(spots[i].comments.length < 3){
            count = spots[i].comments.length;
          }else{
            count = 3
          }

          for(let j = 0; j< count; j++){
            this.contentsInfo[i].contentsComment.push({
              commentTitle: spots[i].comments[j].comment_title,
              commentContents: spots[i].comments[j].comment_content,
              commentUser: spots[i].comments[j].user_name,
              commentUserImage: spots[i].comments[j].user_image,
            })
          }

          for(let j = 0; j < spots[i].images.length; j++){
            if(j === 0){
              this.contentsInfo[i].spotImage.mainImage = spots[i].images[j];
            }else if(j < 3){
              this.contentsInfo[i].spotImage.subImage.push(spots[i].images[j]);
            }
          }
        }
      },
      setPlan: function(plan){
        this.planOutline.id_DB = plan.id;
        this.planOutline.favStatus = plan.fav_status;
        this.planOutline.planTitle = plan.plan_title;
        this.planOutline.planDuration = plan.plan_duration;
        this.planOutline.planTransportation = plan.main_transportation;
        this.planOutline.tags = plan.tags;
        this.planOutline.information = plan.plan_information;
      },
      setImage: function(spots){
        let count = 0;

        for(let i = 0; i < spots.length; i++){
          for(let j = 0; j < spots[i].images.length; j++){
            if(i === 0 && j === 0){
              this.mainImage = spots[i].images[j].image_path;
              count ++;
            }else if(count < 5){
              this.subImage.push(spots[i].images[j].image_path);
              count ++;
            }
          }
        }
      },
      carouselMove: function(spotKey){
        this.currentNum = spotKey + 1;
        if(spotKey == -1){
          this.planOutline.displayStyle = 'height: 0';
          for(let i = 0; i < this.contentsInfo.length; i ++){
            this.contentsInfo[i].displayStyle = 'height: 0';
          }
        }else{
          this.planOutline.displayStyle = 'height: 0';
          for(let i = 0; i < this.contentsInfo.length; i ++){
            if(i === spotKey){
              this.contentsInfo[i].displayStyle = 'display: block';
            }else{
              this.contentsInfo[i].displayStyle = 'height: 0';
            }
          }
        }
      },
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
    },
  }
</script>
