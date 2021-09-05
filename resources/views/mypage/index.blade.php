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
        <div class="profile-area">
          <h3>Profile</h3>
          <div class="profile-info-area">
            <div class="profile-image-area">
              <img src="{{ asset('image/test1.jpeg') }}">
            </div>
            <div class="profile-count-area">
              <div class="post-count-area">
                <h4>Post</h4>
                <h4>12</h4>
              </div>
              <div class="followed-count-area">
                <h4>Followed</h4>
                <h4>20</h4>
              </div>
            </div>
          </div>
          <div class="profile-name-area">
            <div class="profile-name">
              <p>Yuki Kuramoto</p>
            </div>
            <div class="profile-edit">
              <button type="submit" id="edit-button" class="btn btn-secondary btn-submit">編集</button>
            </div>
          </div>
          <div class="profile-introduction-area">
            <p>アウトドア好きなので自然をテーマに投稿しています。</p>
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
            <div class="plan-item-wrapper">
              @if (!is_null($user))
                @foreach($user->plans as $plan)
                <a href="{{ action('PlanpageController@index', ['user_id' => $plan->user_id, 'plan_id' => $plan->id]) }}" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ $plan->spots[0]->images[0]->image_path }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">{{ $plan->plan_title }}</p>
                        <div class="icon-area">
                          <i class="bi bi-star-fill"></i>15
                          <!-- <i class="bi bi-chat-left-dots-fill"></i>8 -->
                        </div>
                        @foreach($plan->tags as $tag)
                          <p class="plan-tag">#{{ $tag->name }}</p>
                        @endforeach
                        <p class="plan-introduction">{{ mb_strimwidth($plan->plan_information, 0, 60) }}</p>
                    </div>
                </a>
                @endforeach
              @endif
            </div>
          </section>
          <section id="tabs-2">
            <div class="plan-item-wrapper">
                <a href="" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ asset('image/test2.jpg') }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">これはテストプランです。</p>
                        <p class="plan-introduction">これはテストプラン紹介文です。</p>
                    </div>
                </a>
                <a href="" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ asset('image/test3.jpg') }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">これはテストプランです。</p>
                        <p class="plan-introduction">これはテストプラン紹介文です。</p>
                    </div>
                </a>
                <a href="" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ asset('image/test1.jpeg') }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">これはテストプランです。</p>
                        <p class="plan-introduction">これはテストプラン紹介文です。</p>
                    </div>
                </a>
                <a href="" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ asset('image/test1.jpeg') }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">これはテストプランです。</p>
                        <p class="plan-introduction">これはテストプラン紹介文です。</p>
                    </div>
                </a>
                <a href="" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ asset('image/test1.jpeg') }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">これはテストプランです。</p>
                        <p class="plan-introduction">これはテストプラン紹介文です。</p>
                    </div>
                </a>
            </div>
          </section>
          <section id="tabs-3">
            <div class="plan-item-wrapper">
                <a href="" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ asset('image/test1.jpeg') }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">これはテストプランです。</p>
                        <p class="plan-introduction">これはテストプラン紹介文です。</p>
                    </div>
                </a>
                <a href="" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ asset('image/test2.jpg') }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">これはテストプランです。</p>
                        <p class="plan-introduction">これはテストプラン紹介文です。</p>
                    </div>
                </a>
                <a href="" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ asset('image/test3.jpg') }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">これはテストプランです。</p>
                        <p class="plan-introduction">これはテストプラン紹介文です。</p>
                    </div>
                </a>
            </div>
          </section>
        </section>
      </div>
    </div>
  </div>
</div>
@endsection
