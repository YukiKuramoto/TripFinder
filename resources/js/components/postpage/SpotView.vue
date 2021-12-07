<template>
  <div class="item">
    <div class="spot-wrapper" :style="showstyle">
      <div class="plan-title">
        <h2>{{ planOutline.plan_title }}</h2>
      </div>
      <div class="spot-image-outline-wrapper">
        <div class="spot-image-wrapper">
          <div class="spot-main-image-wrapper">
            <img class="spot-main-image" v-bind:src="mainImage" @click="showImgModal(mainImage, spot.spot_title)">
          </div>
          <div class="spot-sub-image-wrapper">
            <img class="spot-sub-image" v-for="image in subImage" v-bind:src="image"  @click="showImgModal(image, spot.spot_title)">
          </div>
        </div>
        <div class="spot-outline-wrapper">
          <div class="spot-title-wrapper">
            <h3><span v-if="spotkey != null">Spot.{{ spotkey + 1 }}</span>{{ spot.spot_title }}</h3>
          </div>
          <div class="spot-detail-wrapper">
            <div class="spot-address-wrapper spot-detail-item">
              <i class="bi bi-geo-alt-fill icon-address"></i>{{ spot.spot_address }}
            </div>
            <div class="spot-duration-wrapper spot-detail-item">
              <i class="bi bi-stopwatch"></i>
              <p class="tag-name">#{{ spot.spot_duration }}</p>
            </div>
            <div class="spot-tag-wrapper spot-detail-item">
              <i class="fas fa-tags"></i>
              <p class="tag-name" v-for="tag in spot.tags">#{{ tag.name }}</p>
            </div>
            <div class="spot-detail-item">
              <a v-if="spot.fav_status" class="spot-fav-button btn btn-warning spot-unfav-btn" v-bind:href="'/index/unfavspot?planId=' + planOutline.id + '&spotId=' + spot.id">登録済み</a>
              <a v-else class="spot-fav-button btn btn-warning" v-bind:href="'/index/favspot?planId=' + planOutline.id + '&spotId=' + spot.id">行きたいスポット</a>
              <a class="spot-fav-button btn btn-secondary" v-bind:href="'/comment/create?spotId=' + spot.id">コメント投稿</a>
              <a v-if="postuser.id == login_uid" class="spot-fav-button btn btn-secondary" v-bind:href="'/post/spotedit/' + spot.id">編集</a>
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
            <p>{{ spot.spot_information }}</p>
          </div>
        </div>
        <div class="spot-comment-wrapper">
          <div class="spot-comment-header contents-header">
            <p>コメント</p>
          </div>
          <commentitem-component
            :plan="planOutline"
            :comments="commentArray"
            :login_uid="login_uid">
          </commentitem-component>
          <div class="" v-if="commentArray.length == 0">
            <p>コメントはありません</p>
          </div>
          <div class="comment-anker-area" v-else>
            <a :href="'/comment/show/?plan_id=' + planOutline.id + '&spot_id=' + spot.id" class="comment-anker">全てのコメントを見る >></a>
          </div>
        </div>
      </div>
      <div class="spot-link-wrapper">
        <div class="spot-link-header contents-header">
          <p>関連サイトURL</p>
        </div>
        <div class="spot-link-contents">
          <div v-if="spot.links.length > 0" v-for="link in spot.links" class="link-form-area">
              <div class="link-title-header form-row form-header">
                {{ link.link_title }}
              </div>
              <div class="form-row form-content">
                <a :href="link.link_url" target="_blank">{{ link.link_url }}</a>
              </div>
          </div>
          <div v-if="spot.links.length == 0">
            <p>関連リンクはありません</p>
          </div>
        </div>
      </div>
      <div class="spot-map-wrapper">
        <div class="spot-map-header contents-header">
          <p>MAP</p>
        </div>
        <div class="map" v-bind:id="'spotmap' + spot.spot_count"></div>
      </div>
    </div>
  </div>
</template>

<script>
  export default{
    props:[
      'planOutline',
      'spot',
      'spotkey',
      'postuser',
      'login_uid',
      'csrf',
      'showstyle',
    ],
    data() {
      return {
        mainImage: '',
        subImage: [],
        targetImage: '',
      };
    },
    computed: {
      _listStyle() {
        return {
          transition: '',
          transform: `translatex(${-100 * this.currentNum}%)`,
        };
      },
      commentArray() {
        return this.spot.comments.splice(0, 3);
      }
    },
    created: function(){
      this.setImage(this.spot);
      // console.log(this.spot.spot_title + ':' + this.showstyle)
      console.log(this.spot);
    },
    mounted: function(){
      this.initMapWithAddress(this.spot.spot_count, this.spot.spot_address);
    },
    methods: {
      initMapWithAddress(key, address) {
        var opts = {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
        var my_google_map = new google.maps.Map(document.getElementById(`spotmap${key}`), opts);
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
      setImage: function(spot){
        let count = 0;

        for(let j = 0; j < spot.images.length; j++){
          if(j === 0){
            this.mainImage = spot.images[j].image_path;
            count ++;
          }else if(count < 5){
            this.subImage.push(spot.images[j].image_path);
            count ++;
          }
        }

        if(this.subImage.length < 2){
          do {
            this.subImage.push("/image/no_image.png");
          } while (this.subImage.length < 2);
        }
      },
      showImgModal: function(targetImg, spotTitle){
        let div_element = $('#modal-content');
        let img_element = div_element.find('img');
        let h2_element = div_element.find('h2');

        div_element.attr({
          style: 'display: block',
        });

        h2_element.text('Spot Title: ' + spotTitle);

        img_element.attr({
          src: targetImg,
          style: 'max-height: 600px',
        })
      },
    },
  }
</script>

<style scoped lang="scss">
.comment-anker-area {
  text-align: right;
  margin-top: 30px;

  .comment-anker {
    list-style: none;
    text-decoration: none;
    color: #555;
  }
}

.spot-image-outline-wrapper img{
  cursor: pointer;
}

@media (min-width: 1251px) {
  .spot-image-outline-wrapper {
    display: flex;
    justify-content: space-around;
  }

  .spot-infocomment-wrapper {
    display: flex;
    justify-content: space-between;
  }

  .spot-outline-wrapper {
    width: 40%;
  }

  .spot-info-wrapper {
    width: 43%;
  }

  .spot-comment-wrapper {
    width: 48%;
  }

}

@media (max-width: 1250px) {
  .spot-image-outline-wrapper {
    display: block;
  }

  .spot-image-wrapper {
    display: flex;
    justify-content: center;
  }

  .spot-infocomment-wrapper {
    display: block;
  }

  .spot-outline-wrapper {
    margin-top: 50px;
    width: 100%;
  }

  .spot-info-wrapper {
    width: 100%;
  }

  .spot-comment-wrapper {
    margin-top: 50px;
    width: 100%;
  }
}
</style>
