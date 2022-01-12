<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="shortcut icon" type="image/x-icon"  href="{{ asset('/favicon.ico') }}">
        <script src="https://cdn.jsdelivr.net/npm/vue-js-modal@1.3.31/dist/index.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-js-modal@1.3.31/dist/styles.css">
        @yield('css')
        @yield('js')
    </head>
    <body>
        <div id="app">
            <header id="header">
                <a href="{{ action('Main\HomeController@index') }}"><img src="{{ asset('image/logo.JPG') }}" alt="TripFinder" id="logo"></a>
                <div id="header-contents">
                  <nav id="header-list">
                      @guest
                        <a href="/register" id="sign-in" class="header-button">REGISTER</a>
                        <a href="/login" id="log-in" class="header-button">LOG IN</a>
                      @endguest
                  </nav>
                </div>
                @auth
                  <p id="user-name">ユーザー：{{ Auth::user()->name }}</p>
                @endauth
            </header>
            @auth
            <nav-component
            :user_id="{{ Auth::user()->id }}"
            ></nav-component>
            @endauth
            <div class="containers">
                @yield('content')
            </div>
        </div>
        <!-- 画像プレビュー用モーダル -->
        <div style="display: none" name="hello-world" :draggable="true" :resizable="true" id="modal-content">
          <div id="modal-template">
            <div id="modal-background" @click="hide"></div>
            <div id="modal-outer">
              <div id="modal-box">
                <div id="modal-wrapper">
                  <div class="modal-header">
                    <h2>Spot Image</h2>
                  </div>
                  <div class="modal-body">
                    <img>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- ログアウト用モーダル -->
        <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form role="form" id="modal-form" method="POST" action="{{ action('Auth\LoginController@logout') }}">
                @auth
                  <div class="modal-body">
                      <p>ログアウトします。</p>
                  </div>
                  <div class="modal-footer">
                    {{ csrf_field() }}
                    <button type="submit" id="button-login" class="btn btn-primary btn-submit">ログアウト</button>
                  </div>
                @endauth
              </form>
            </div>
          </div>
        </div>
    </body>
</html>
