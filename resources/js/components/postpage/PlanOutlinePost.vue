<template>
  <div class="item">
    <div v-if="type=='post'" class="content-title">
      PlanOutline
    </div>
    <div v-if="type=='planedit'" class="content-title">
      PlanOutline編集
    </div>
      <div class="title-area">
        <div class="error-mark">*</div>
        プランタイトル：
        <input type="text" name="" :value="errors" style="display:none">
        <input type="text"
        class="user-input"
        v-model="planOutline.plan_title">
      </div>
      <div class="transportation-label">
        <div class="title-area">
          主要交通手段　：
          <select  class="user-input"
          v-model="planOutline.main_transportation">
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
        v-model="planOutline.plan_tag">
        <div class="tagsinput">
          <div class="tagsinput-child">
            <input class="tagsinput-form" @blur="registarPlanTag()">
          </div>
          <div class="tags_clear">
          </div>
        </div>
      </div>
      <div class="plan-information-area">
        <div class="plan-textarea-title">
          <div class="error-mark">*</div>
          PLAN INFORMATION
        </div>
        <textarea class="plan-information-textarea user-input"
        v-model="planOutline.plan_information"
        placeholder="プランの見所を投稿しよう！">
        </textarea>
      </div>
  </div>
</template>

<script>
  export default{
    props:[
      'errors',
      'old',
      'planOutline',
      'type',
    ],
    beforeUpdate: function(){
      $('.hash-tag').tagsInput({width:'100%'});
    },
    updated: function(){
      this.$emit('outlineUpdate', this.planOutline);
    },
    methods: {
      registarPlanTag: function(){
        this.planOutline.plan_tag = document.getElementById('plantag-input').value;
      },
    }
  }
</script>

<style media="screen" lang="scss" scoped>

input, select, textarea{
  padding-left: 10px;
}

select {
  width: 80px;
}

.item {
  min-width: 770px;
  width: 100%;
  height: 100%;
  padding: 0 5%;
  background-color: #fff;
  box-shadow: 0 0 8px 0 rgb(0 0 0 / 15%);

  .content-title {
    text-align: center;
    padding: 30px 0;
    font-size: 30px;
  }

  .title-area {
    margin-top: 30px;

    input {
      width: calc(100% - 130px);
    }
  }

  .plan-textarea-title {
    margin-top: 30px;

  }

  textarea {
    width: 100%;
  }

  .plan-information-textarea {
    height: 250px;
    margin-bottom: 120px;
  }

  // 共通？？
  .user-input {
    border:1px solid #999;
  }

  // 共通？？
  .error-mark {
    text-decoration: none;
    display: inline;
    color: #FF3333;
  }

}

</style>
