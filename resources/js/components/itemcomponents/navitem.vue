<template>
  <div id="nav-container">
    <div class="bg"></div>
    <div class="button" tabindex="0">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </div>
    <div id="nav-content" tabindex="0">
      <ul>
        <li><a href="/home">Top</a></li>
        <li><a :href="'/mypage/' + user_id">MyPage</a></li>
        <li><a href="/post">Post Plan</a></li>
        <li><a href="/users/index">Users</a></li>
        <li><a href="/search">Search</a></li>
        <li><a href="#!" id="log-out" data-toggle="modal" data-target="#Modal">LOGOUT</a></li>
      </ul>
    </div>
  </div>
</template>

<script>
export default{
  props:[
    'user_id',
  ],
}
</script>

<style media="screen" lang="scss" scoped>
#nav-container {
    display: flex;
    flex-direction: row-reverse;
    z-index: 1000;
    position: fixed;
    height: 100vh;
    width: 100%;
    pointer-events: none;

    a,
    a:visited,
    a:focus,
    a:active,
    a:link {
        text-decoration: none;
        outline: 0;
    }

    ul {
        padding: 0;
        list-style: none;
    }

    .bg {
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        height: calc(100% - 60px);
        visibility: hidden;
        opacity: 0;
        transition: .3s;
        background: #000;
    }

    .button {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        z-index: 1;
        -webkit-appearance: none;
        border: 0;
        background: transparent;
        border-radius: 0;
        height: 60px;
        width: 45px;
        cursor: pointer;
        pointer-events: auto;
        margin-right: 15px;
        padding-right: 20px;
        touch-action: manipulation;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }

    .icon-bar {
        display: block;
        width: 100%;
        height: 3px;
        color: #222222;
        background: #aaa;
        transition: .3s;
    }

    .icon-bar + .icon-bar {
        margin-top: 5px;
    }

    #nav-content {
        margin-top: 60px;
        padding: 20px;
        width: 100%;
        max-width: 300px;
        top: 0;
        left: 0;
        height: calc(100% - 60px);
        background: #ececec;
        pointer-events: auto;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
        transform: translateX(120%);
        transition: transform .3s;
        contain: paint;

        ul {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        li a {
            padding: 20px 5px;
            display: block;
            color: #222222;
            text-transform: uppercase;
            transition: color .1s;
        }

        li a:hover {
            color: #BF7497;
        }
    }
}



#nav-container * {
    visibility: visible;
}

#nav-container:focus-within .bg {
    visibility: visible;
    opacity: .6;
}

#nav-container:focus-within .button {
    pointer-events: none;
}

#nav-container:focus-within .icon-bar:nth-of-type(1) {
    transform: translate3d(0,8px,0) rotate(45deg);
}

#nav-container:focus-within .icon-bar:nth-of-type(2) {
    opacity: 0;
}

#nav-container:focus-within .icon-bar:nth-of-type(3) {
    transform: translate3d(0,-8px,0) rotate(-45deg);
}

#nav-container:focus-within #nav-content {
    transform: translateX(20%);
}
</style>
