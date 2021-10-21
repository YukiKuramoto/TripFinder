<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- <script src="{{ asset('js/main.js') }}" defer></script> -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @yield('css')
        @yield('js')
    </head>
    <body>
        <div id="app">
            <header id="header">
                <a href="{{ action('PostController@index') }}"><img src="{{ asset('image/logo.JPG') }}" alt="TripFinder" id="logo"></a>
                <div id="header-contents">
                  <nav id="header-list">
                      @guest
                        <!-- <a href="/register" id="sign-in" class="header-button" data-toggle="modal" data-target="#Modal">REGISTER</a>
                        <a href="/login" id="log-in" class="header-button" data-toggle="modal" data-target="#Modal">LOG IN</a> -->
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
                  <li><a href="{{ action('MypageController@index', ['user_id' => Auth::user()->id ]) }}">MyPage</a></li>
                  <li><a href="{{ action('PostController@show') }}">Post Plan</a></li>
                  <li><a href="{{ action('UsersViewController@index') }}">Users</a></li>
                  <li><a href="{{ action('SearchController@index')}}">Search</a></li>
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
              <form role="form" id="modal-form" method="POST" action="{{ action('UserAuthController@logoutuser') }}">
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
