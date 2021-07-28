<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        @yield('css')
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/main.js') }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/vue-carousel@0.18.0/dist/vue-carousel.min.js"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/home.css') }}" rel="stylesheet">
        <link href="{{ asset('css/post.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <header id="header">
                <a href=""><img src="{{ asset('image/logo.JPG') }}" alt="TripFinder" id="logo"></a>
                <div id="header-contents">
                  <nav id="header-list">
                      @guest
                        <a href="#!" id="sign-in" class="header-button" data-toggle="modal" data-target="#Modal">SIGN IN</a>
                        <a href="#!" id="log-in" class="header-button" data-toggle="modal" data-target="#Modal">LOG IN</a>
                      @endguest
                  </nav>
                </div>
                @auth
                  <p id="user-name">ユーザー：{{ Auth::user()->name }}</p>
                @endauth
            </header>
            @auth
            <div id="nav-container">
              <div class="bg"></div>
              <div class="button" tabindex="0">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </div>
              <div id="nav-content" tabindex="0">
                <ul>
                  <li><a href="{{ action('Home\HomeController@index') }}">Top</a></li>
                  <li><a href="">MyPage</a></li>
                  <li><a href="{{ action('PostController@show') }}">Post Plan</a></li>
                  <li><a href="">Favorite User</a></li>
                  <li><a href="">History</a></li>
                  <li><a href="#!" id="log-out" data-toggle="modal" data-target="#Modal">LOGOUT</a></li>
                </ul>
              </div>
            </div>
            @endauth
            <div class="containers">
                @yield('content')
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form role="form" id="modal-form" method="POST">
                @guest
                  <div class="modal-body">
                      <p>ユーザーネーム</p>
                      <input id="login-name" name="name" type="text">
                      <p>メールアドレス</p>
                      <input id="login-address" name="email" type="e-mail">
                      <p>パスワード</p>
                    <input id="login-password" name="password" type="password">
                  </div>
                  <div class="modal-footer">
                    {{ csrf_field() }}
                    <button type="submit" id="button-login" class="btn btn-primary btn-submit" data-action="{{ action('UserAuthController@authenticate') }}">ログイン</button>
                    <button type="submit" id="button-signin" class="btn btn-primary btn-submit" data-action="{{ action('UserRegisterController@register') }}">登録</button>
                  </div>
                @endguest
                @auth
                  <div class="modal-body">
                      <p>ログアウトします。</p>
                  </div>
                  <div class="modal-footer">
                    {{ csrf_field() }}
                    <button type="submit" id="button-login" class="btn btn-primary btn-submit" data-action="{{ action('UserAuthController@logoutuser') }}">ログアウト</button>
                  </div>
                @endauth
              </form>
            </div>
          </div>
        </div>
    </body>
</html>