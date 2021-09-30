
@extends('layouts.form')
@section('title', 'profile')

@section('child-css')
<link href="{{ asset('css/searchpage.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
@endsection

@section('child-js')
<script src="{{ asset('js/searchpage.js') }}" defer></script>
@endsection

@section('form-content')
  <div class="searchpage-outer">
    <div class="searchpage-title">
      <h2>プランやユーザーを検索してみよう</h2>
    </div>
    <div class="search-box-area">
      <div class="search-box-area-wrapper accordion">
        <section class="form-search-fromword">
          <div class="form-title-area accordion-title">
            <a href="#">検索ワードから探す</a>
          </div>
          <div class="form-body-area accordion-content accordion-content-active">
            <form class="search_container" method="post" action="{{ action('SearchController@search') }}">
              {{ csrf_field() }}
              @if(isset($old_info))
              <div class="search-box-wrapper">
                <input type="text" size="25" name="search_word" placeholder="キーワード検索" value="{{ $old_info['word'] }}">
                <button type="submit"><i class="bi bi-search"></i></button>
              </div>
              @else
              <div class="search-box-wrapper">
                <input type="text" size="25" name="search_word" placeholder="キーワード検索">
                <button type="submit"><i class="bi bi-search"></i></button>
              </div>
              @endif
              <div class="radio-button-area">
                <input type="radio" name="search_type" value="plan"　checked="checked"><span>PLAN</span>
                <input type="radio" name="search_type" value="spot"><span>SPOT</span>
                <input type="radio" name="search_type" value="user"><span>USER</span>
              </div>
            </form>
          </div>
        </section>
        <section class="form-search-keytag">
          <div class="form-title-area accordion-title">
            <a href="#">キータグ項目から探す</a>
          </div>
          <div class="form-body-area accordion-content">
            <form class="search_container" method="post" action="{{ action('SearchController@search') }}">
              {{ csrf_field() }}
              <div class="keytag-search keytag-plan-search">
                <div class="search-item item-transportation">
                  <i class="fas fa-walking"></i>
                  <select>
                    <option value=""></option>
                    <option value="車">車</option>
                    <option value="電車">電車</option>
                    <option value="バス">バス</option>
                    <option value="徒歩">徒歩</option>
                    <option value="その他">その他</option>
                  </select>
                </div>
                <div class="search-item item-days">
                  <i class="bi bi-calendar-week-fill"></i>
                  <select>
                    <option value=""></option>
                    <option value="1Day">1Day</option>
                    <option value="2Days">2Days</option>
                    <option value="3Days">3Days</option>
                    <option value="4Days">4Days</option>
                    <option value="Over 5Days">Over 5Days</option>
                  </select>
                </div>
                <div class="search-button-wrapper">
                  <button type="submit"><i class="bi bi-search"></i> PLAN</button>
                </div>
              </div>
              <div class="keytag-search keytag-spot-search">
                <div class="search-item item-stay">
                  <i class="bi bi-stopwatch"></i>
                  <select>
                    <option value=""></option>
                    <option value="0.5時間">0.5時間</option>
                    <option value="1時間">1時間</option>
                    <option value="1.5時間">1.5時間</option>
                    <option value="2時間">2時間</option>
                    <option value="2時間以上">2時間以上</option>
                  </select>
                </div>
                <div class="search-item item-address">
                  <i class="bi bi-geo-alt-fill icon-address"></i>
                  <input type="text" name="" value="" placeholder="所在地検索">
                </div>
                <div class="search-button-wrapper">
                  <button type="submit"><i class="bi bi-search"></i> SPOT</button>
                </div>
              </div>
            </form>
          </div>
        </section>
      </div>
    </div>
    <div class="search-result-area">
      @if(isset($result))
        @if($result == 'no_result')
        <div class="search-error-result">
          <h4>検索結果はありません</h4>
        </div>
        @elseif($result == 'user')
        <div class="users-contents-outer">
          <div class="users-contents-wrapper">
            @foreach ($response as $user)
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
              <div class="icon-area">
                <i class="bi bi-star-fill icon"></i>{{ count($user->followers) }} followers
                <i class="bi bi-file-earmark-post-fill icon"></i>{{ count($user->plans) }} posts
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @elseif($result == 'plan')
        <div class="plan-item-wrapper">
          @foreach($response as $plan)
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
              <p class="plan-introduction">{{ mb_strimwidth($plan->plan_information, 0, 90) }}...</p>
            </div>
          </a>
          @endforeach
        </div>
        @elseif($result == 'spot')
        <div class="spot-item-outer">
          <div class="spot-item-wrapper">
            @foreach($response as $spot)
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
          </div>
        </div>
        @endif
      @endif
    </div>
  </div>
@endsection
