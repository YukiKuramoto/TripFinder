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
            <h3><span>Spot. </span>{{ spot.spot_title }}</h3>
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
              <a class="spot-fav-button btn btn-secondary" v-bind:href="'/comment/create?spot_id=' + spot.id">コメント投稿</a>
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

.item {
  min-width: 900px;
  width: 100%;
  height: 100%;
  min-height: calc(100vh - 60px);
  padding: 0 5%;
  background-color: #fff;
  display: flex;
  justify-content: center;
}

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

//ここからスタート
.spot-wrapper {
  height: 100%;
  padding: 0 50px;

  .contents-header {
    margin-bottom: 35px;
    border-bottom: 1px solid rgb(85,85,85, 0.3);
  }

  .plan-title {
    padding-top: 50px;
    display: inline-block;

    h2 {
      border-bottom: solid 1px;
    }
  }

  .spot-image-outline-wrapper {
    height: 100%;
    margin: 40px 0 0 0;

    .spot-image-wrapper {
      height: 300px;
      display: flex;
      justify-content: space-right;

      img {
        margin: 0 2px 2px 0;
      }

      .spot-main-image-wrapper {
        height: 100%;

        .spot-main-image {
          height: 100%;
          width: 250px;
          border-radius: 10px;
          object-fit: cover;
        }
      }

      .spot-sub-image-wrapper {
        width: 280px;
        display: flex;
        flex-wrap: wrap;

        .spot-sub-image {
          height: 50%;
          width: 250px;
          border-radius: 10px;
          object-fit: cover;
        }
      }
    }

    .spot-outline-wrapper {

      h3 {
        font-weight: 700;
      }

      span {
        margin-right: 20px;
      }

      .spot-detail-wrapper {

        .tag-name {
            display: inline-block;
            font-size: 15px;
            border-radius: 100vh;
            padding: 2px 10px;
            margin: 8px 10px 15px 0;
            color: #fff;
            background-color: #7dcafa;
        }

        .spot-detail-item {
          margin-top: 20px;
          font-size: 20px;

          i {
            margin-right: 10px;
            font-size: 25px;
          }
        }

        .spot-address-wrapper {
          margin-top: 50px;

          i {
            color: #ff3333;
          }
        }

        .spot-tag-wrapper {
          margin-bottom: 30px;
          i {
            color: #666666;
          }
        }

        .spot-fav-button {
          margin-right: 10px;
        }

        .spot-unfav-btn {
          background-color: #FFF;
          color: #fcc800;
          font-weight: 600;
          border: solid 2px #fcc800;
        }
      }
    }
  }

  .spot-infocomment-wrapper {
    margin-top: 80px;
    // display: flex;
    // justify-content: space-between;
    width: 100%;

    p {
      margin: 0;
      padding: 0;
    }

    .spot-info-wrapper {
      white-space: pre-wrap;
    }
  }

  .spot-link-wrapper{
    margin-top: 50px;

    .form-row {
      min-width: 800px;
      margin: 0 0 -1px 0;
      padding: 8px;
      border: 1px solid rgb(85,85,85, 0.5);
    }

    .form-header {
      background-color: #f6f6f6;
    }
  }

  .spot-map-wrapper {
    margin-top: 50px;

    .map {
      margin: 30px 0 60px 0;
      height: 400px;
      width: 100%;
    }
  }
}


//レスポンシブ対応用CSS
@media (min-width: 1341px) {
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

@media (max-width: 1340px) {
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
