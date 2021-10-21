@extends('layouts.home')
@section('title', 'mypage')

@section('css')
<link href="{{ asset('css/mypage.css') }}" rel="stylesheet">
<script src="{{ asset('js/mypage.js') }}" defer></script>
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
                <img src="{{ $user->image_path }}">
              </div>
              <div class="profile-count-area">
                <div class="post-count-area">
                  <h4>Post</h4>
                  <h4>{{ count($user->plans) }}</h4>
                </div>
                <div class="followed-count-area">
                  <h4>Followed</h4>
                  <h4>{{ count($user->followers) }}</h4>
                </div>
              </div>
            </div>
            <div class="profile-name-area">
              <div class="profile-name">
                <p>{{ $user->name }}</p>
              </div>
              @if(Auth::user()->id == $user->id)
              <div class="profile-edit">
                <a class="btn btn-secondary btn-submit" href="{{ action('ProfileController@edit') }}">
                  編集
                </a>
              </div>
              @endif
            </div>
            <div class="profile-introduction-area">
              <p>{{ $user->comment }}</p>
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
              :user="{{ $user }}"
              :plans="{{ json_encode($plans[0]) }}"
              :length="{{ count($plans) }}"
              pagetype="mypage"
              parameter="myplan"
            ><planitem-component/>
          </section>
          <section id="tabs-2">
            <planitem-component
              :user="{{ $user }}"
              :plans="{{ json_encode($plans_fav[0]) }}"
              :length="{{ count($plans_fav) }}"
              pagetype="mypage"
              parameter="favplan"
            ><planitem-component/>
          </section>
          <section id="tabs-3">
            <spotitem-component
              :user="{{ $user }}"
              :spots="{{ json_encode($spots_fav[0]) }}"
              :length="{{ count($spots_fav) }}"
              pagetype="mypage"
              parameter="favspot"
            ><spotitem-component/>
          </section>
        </section>
      </div>
    </div>
  </div>
</div>
@endsection