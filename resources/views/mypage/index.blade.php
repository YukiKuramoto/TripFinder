@extends('app')
@section('title', 'MyPage')

@section('css')
<link href="{{ asset('css/mypage.css') }}" rel="stylesheet">
<link href="{{ asset('css/tabs.css') }}" rel="stylesheet">
<script src="{{ asset('js/tabs.js') }}" defer></script>
@endsection

@section('content')
<div class="mypage">
  <div class="contents-outer">
    <h1 class="contents-title">My Page</h1>
    <div class="contents-wrapper">
      <div class="contents-profile">
        <div class="profile-area-outer">
          <div class="profile-area">
            <h3>Profile</h3>
            <div class="profile-info-area">
              <div class="profile-image-area">
                @if(isset($postuser->image_path))
                <img src="{{ $postuser->image_path }}">
                @else
                <img src="{{ asset('image/default.png') }}">
                @endif
              </div>
              <div class="profile-count-area">
                <div class="post-count-area">
                  <h4>Post</h4>
                  <h4>{{ count($postuser->plans) }}</h4>
                </div>
                <div class="followed-count-area">
                  <h4>Followed</h4>
                  <h4>{{ count($postuser->followers) }}</h4>
                </div>
              </div>
            </div>
            <div class="profile-name-area">
              <div class="profile-name">
                <p>{{ $postuser->name }}</p>
              </div>
              @if($login_uid == $postuser->id)
              <div class="profile-edit">
                <a class="btn btn-secondary btn-submit" href="{{ action('Main\ProfileController@index') }}">
                  編集
                </a>
              </div>
              @endif
            </div>
            <div class="profile-introduction-area">
              <p>{{ $postuser->comment }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="contents-post">
        <ul class="tabs-menu">
          <li><a href="#tabs-1">My投稿</a></li>
          <li><a href="#tabs-2">お気に入りプラン</a></li>
          <li><a href="#tabs-3">行きたいスポット</a></li>
        </ul>
        <section class="tabs-content">
          <section id="tabs-1">
            <planitem-component
              :response="{{ json_encode($plans[0]) }}"
              :length="{{ count($plans) }}"
              pagetype="mypage/myplan"
              :search_key="{{ json_encode($postuser) }}"
            ><planitem-component/>
          </section>
          <section id="tabs-2">
            <planitem-component
              :response="{{ json_encode($plans_fav[0]) }}"
              :length="{{ count($plans_fav) }}"
              pagetype="mypage/favplan"
              :search_key="{{ json_encode($postuser) }}"
            ><planitem-component/>
          </section>
          <section id="tabs-3">
            <spotitem-component
              :response="{{ json_encode($spots_fav[0]) }}"
              :length="{{ count($spots_fav) }}"
              pagetype="mypage/favspot"
              :search_key="{{ json_encode($postuser) }}"
            ><spotitem-component/>
          </section>
        </section>
      </div>
    </div>
  </div>
</div>
@endsection
