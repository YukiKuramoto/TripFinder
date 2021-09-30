@extends('layouts.home')
@section('title', 'users')

@section('css')
<link href="{{ asset('css/usersview.css') }}" rel="stylesheet">
<script src="{{ asset('js/mypage.js') }}" defer></script>
@endsection

@section('content')
<div class="users-page">
  <div class="contents-outer">
    <h1 class="contents-title">USERS</h1>
    <div class="contents-wrapper">
      <div class="users-area">
        <ul class="tabs-menu">
          <li><a href="#tabs-1">All Users</a></li>
          <li><a href="#tabs-2">Favorite Users</a></li>
          <li><a href="#tabs-3">Followers</a></li>
        </ul>
        <section class="tabs-content">
          <section id="tabs-1">
            <div class="users-contents-outer">
              <div class="users-contents-wrapper">
                @foreach ($users as $user)
                @if($user->id != Auth::id())
                <div class="users-item-wrapper">
                  <a href="{{ action('MypageController@index', ['user_id' => $user->id]) }}"  class="anker-area">
                    <div class="user-image-area">
                      <img src="{{ $user->image_path }}">
                    </div>
                    <div class="user-name-area">
                      <p>{{ $user->name }}</p>
                    </div>
                    <div class="user-comment-area">
                      <p>{{ $user->comment }}</p>
                    </div>
                  </a>
                  @if($user->follow_flg)
                  <form class="follow-button-area" action="{{ action('UsersViewController@unfollow', ['user_id' => $user->id]) }}">
                    <button type="submit" class="btn btn-secondary following-btn">フォロー中</button>
                  </form>
                  @else
                  <form class="follow-button-area" action="{{ action('UsersViewController@follow', ['user_id' => $user->id]) }}">
                    <button type="submit" class="btn btn-secondary">フォローする</button>
                  </form>
                  @endif
                  <div class="icon-area">
                    <i class="bi bi-star-fill icon"></i>{{ count($user->followers) }} followers
                    <i class="bi bi-file-earmark-post-fill icon"></i>{{ count($user->plans) }} posts
                  </div>
                </div>
                @endif
                @endforeach
              </div>
            </div>
          </section>
          <section id="tabs-2">
            <div class="users-contents-outer">
              <div class="users-contents-wrapper">
                @foreach ($favorite_users as $user)
                <div class="users-item-wrapper">
                  <a href="{{ action('MypageController@index', ['user_id' => $user->id]) }}"  class="anker-area">
                    <div class="user-image-area">
                      <img src="{{ $user->image_path }}">
                    </div>
                    <div class="user-name-area">
                      <p>{{ $user->name }}</p>
                    </div>
                    <div class="user-comment-area">
                      <p>{{ $user->comment }}</p>
                    </div>
                  </a>
                  @if($user->follow_flg)
                  <form class="follow-button-area" action="{{ action('UsersViewController@unfollow', ['user_id' => $user->id]) }}">
                    <button type="submit" class="btn btn-secondary following-btn">フォロー中</button>
                  </form>
                  @else
                  <form class="follow-button-area" action="{{ action('UsersViewController@follow', ['user_id' => $user->id]) }}">
                    <button type="submit" class="btn btn-secondary">フォローする</button>
                  </form>
                  @endif
                  <div class="icon-area">
                    <i class="bi bi-star-fill icon"></i>{{ count($user->followers) }} followers
                    <i class="bi bi-file-earmark-post-fill icon"></i>{{ count($user->plans) }} posts
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </section>
          <section id="tabs-3">
            <div class="users-contents-outer">
              <div class="users-contents-wrapper">
                @foreach ($follower_users as $user)
                <div class="users-item-wrapper">
                  <a href="{{ action('MypageController@index', ['user_id' => $user->id]) }}"  class="anker-area">
                    <div class="user-image-area">
                      <img src="{{ $user->image_path }}">
                    </div>
                    <div class="user-name-area">
                      <p>{{ $user->name }}</p>
                    </div>
                    <div class="user-comment-area">
                      <p>{{ $user->comment }}</p>
                    </div>
                  </a>
                  @if($user->follow_flg)
                  <form class="follow-button-area" action="{{ action('UsersViewController@unfollow', ['user_id' => $user->id]) }}">
                    <button type="submit" class="btn btn-secondary following-btn">フォロー中</button>
                  </form>
                  @else
                  <form class="follow-button-area" action="{{ action('UsersViewController@follow', ['user_id' => $user->id]) }}">
                    <button type="submit" class="btn btn-secondary">フォローする</button>
                  </form>
                  @endif
                  <div class="icon-area">
                    <i class="bi bi-star-fill icon"></i>{{ count($user->followers) }} followers
                    <i class="bi bi-file-earmark-post-fill icon"></i>{{ count($user->plans) }} posts
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </section>
        </section>
      </div>
    </div>
  </div>
</div>
@endsection
