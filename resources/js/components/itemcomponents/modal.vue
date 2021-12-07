<template>
  <div id="modal-app">
    <p>test</p>
    <p>test</p>
    <p>test</p>
    <p>test</p>
    <p @click="hide">test</p>
    <button v-on:click.prevent="show" class="button">show!</button>
  </div>
</template>

<script>
  export default{
    props:[
    ],
    data() {
      return {
      };
    },
    computed: {

    },
    created: function(){

    },
    mounted: function(){

    },
    methods: {
      show : function() {
        let div_element = $('#modal-content');
        let img_element = div_element.find('img');
        // const template = div_element.find('div');
        // console.log(template);

        div_element.attr({
          style: 'display: block',
        });

        img_element.attr({
          src: "../image/no_image.png",
        })



        // $('body').append(div_element.html());
        // template.attr({
        //   id: 'newModal',
        // })
        // $('body').attr({
        //   style: 'display: block',
        // });
      },
      hide : function () {
        console.log('testremove');
        const div_element = $('#modal-content')

        div_element.remove();
      },
    },
  }
</script>

<style scoped lang="scss">

#modal-content {
  display: none;
}

#modal-background {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: #000;
  opacity: 0.5;
}

#modal-outer {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 1050;
  display: flex;
  justify-content: center;

  #modal-wrapper {
    background-color: #FFF;
    border-radius: 20px;
  }
}

.modal-header, .modal-body {
  padding: 5px 25px;
}
.modal-header {
  border-bottom: 1px solid #ddd;
}

</style>
