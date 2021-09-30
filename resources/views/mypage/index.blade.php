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
            <div class="plan-item-wrapper">
              @if (!is_null($user))
                @foreach($user->plans as $plan)
                <a href="{{ action('PlanpageController@index', ['plan_id' => $plan->id]) }}" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ $plan->spots[0]->images[0]->image_path }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">{{ $plan->plan_title }}</p>
                        <div class="icon-area">
                          <i class="bi bi-star-fill"></i>{{ count($plan->favs) }} favs
                          <i class="bi bi-geo-fill"></i>{{ count($plan->spots) }} spots
                        </div>
                        @foreach($plan->tags as $tag)
                          <p class="plan-tag">#{{ $tag->name }}</p>
                        @endforeach
                        <p class="plan-introduction">{{ mb_strimwidth($plan->plan_information, 0, 60) }}...</p>
                    </div>
                </a>
                @endforeach
              @endif
            </div>
          </section>
          <section id="tabs-2">
            <div class="plan-item-wrapper">
              @if (!is_null($user))
                @foreach($user->favPlans as $plan)
                <a href="{{ action('PlanpageController@index', ['plan_id' => $plan->id]) }}" class="plan-item">
                    <div class="plan-image-wrapper">
                        <img class="plan-image" src="{{ $plan->spots[0]->images[0]->image_path }}">
                    </div>
                    <div class="plan-outline-wrapper">
                        <p class="plan-title">{{ $plan->plan_title }}</p>
                        <div class="icon-area">
                          <i class="bi bi-star-fill"></i>{{ count($plan->favs) }} favs
                          <i class="bi bi-geo-fill"></i>{{ count($plan->spots) }} spots
                          <i class="bi bi-pencil-square"></i> by {{ $plan->user->name }}
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
          <section id="tabs-3">
            <div class="spot-item-outer">
              <div class="spot-item-wrapper">
                @if (!is_null($user))
                @foreach($user->favSpots as $spot)
                <a href="" class="spot-item">
                  <div class="spot-image-outer">
                    <img class="spot-image" src="{{ $spot->images[0]->image_path }}">
                  </div>
                  <div class="spot-outline-wrapper">
                    <div class="spot-title-area">
                      <p class="spot-title">{{ $spot->spot_title }}</p>
                    </div>
                    <div class="icon-area">
                      <i class="bi bi-star-fill"></i>{{ count($spot->favs) }} favs
                      <i class="bi bi-pencil-square"></i> by test
                    </div>
                  </div>
                </a>
                @endforeach
                @endif
              </div>
            </div>
          </section>
        </section>
      </div>
    </div>
  </div>
</div>
@endsection
