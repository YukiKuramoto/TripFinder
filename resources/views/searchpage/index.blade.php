
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
            <form class="search_container" method="post" action="{{ action('SearchController@mainSearch') }}">
              {{ csrf_field() }}
              <div class="search-box-wrapper">
                <input type="text" size="25" name="search_word" placeholder="キーワード検索">
                <button type="submit"><i class="bi bi-search"></i></button>
              </div>
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
            <a href="#">キータグ項目からプランを探す</a>
          </div>
          <div class="form-body-area accordion-content">
            <form class="search_container" method="post" action="{{ action('SearchController@mainSearch', ['search_type' => 'plan']) }}">
              {{ csrf_field() }}
              <div class="keytag-search">
                <div class="keytag-plan-search">
                  <div class="keytag-search">
                    <div class="search-item item-transportation">
                      <i class="fas fa-walking"></i>
                      <select name="transportation">
                        <option value="" name=""></option>
                        <option value="車">車</option>
                        <option value="電車">電車</option>
                        <option value="バス">バス</option>
                        <option value="徒歩">徒歩</option>
                        <option value="その他">その他</option>
                      </select>
                    </div>
                    <div class="search-item item-days">
                      <i class="bi bi-calendar-week-fill"></i>
                      <select name="duration">
                        <option value=""></option>
                        <option value="1">1Day</option>
                        <option value="2">2Days</option>
                        <option value="3">3Days</option>
                        <option value="4">4Days</option>
                        <option value="Over 5Days">Over 5Days</option>
                      </select>
                    </div>
                  </div>
                  <div class="search-item item-address item-address-plan">
                    <i class="bi bi-geo-alt-fill icon-address"></i>
                    <input type="text" name="address" value="" placeholder="所在地検索">
                  </div>
                </div>
                <div class="search-button-wrapper search-button-plan">
                  <button type="submit"><i class="bi bi-search"></i> PLAN</button>
                </div>
              </div>
            </form>
          </div>
        </section>
        <section class="form-search-keytag">
          <div class="form-title-area accordion-title">
            <a href="#">キータグ項目からスポットを探す</a>
          </div>
          <div class="form-body-area accordion-content">
            <form class="search_container" method="post" action="{{ action('SearchController@mainSearch', ['search_type' => 'spot']) }}">
              {{ csrf_field() }}
              <div class="keytag-search keytag-spot-search">
                <div class="search-item item-stay">
                  <i class="bi bi-stopwatch"></i>
                  <select name="stay">
                    <option value="" ></option>
                    <option value="0.5時間">0.5時間</option>
                    <option value="1時間">1時間</option>
                    <option value="1.5時間">1.5時間</option>
                    <option value="2時間">2時間</option>
                    <option value="2時間以上">2時間以上</option>
                  </select>
                </div>
                <div class="search-item item-address">
                  <i class="bi bi-geo-alt-fill icon-address"></i>
                  <input type="text" name="address" value="" placeholder="所在地検索">
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
        @elseif($result == 'plan')
          <planitem-component
            :response="{{ json_encode($response[0]) }}"
            :length="{{ count($response) }}"
            :search_key="{{ json_encode($search_key) }}"
            pagetype="search"
            parameter="{{ $parameter }}"
          ><planitem-component/>
        @elseif($result == 'spot')
          <spotitem-component
            :response="{{ json_encode($response[0]) }}"
            :length="{{ count($response) }}"
            :search_key="{{ json_encode($search_key) }}"
            pagetype="search"
            parameter="{{ $parameter }}"
          ><spotitem-component/>
          @elseif($result == 'user')
          <useritem-component
          :login_user="{{ json_encode($login_user) }}"
          :response="{{ json_encode($response[0]) }}"
          :length="{{ count($response) }}"
          :search_key="{{ json_encode($search_key) }}"
          pagetype="search"
          parameter="{{ $parameter }}"
          ></useritem-component>
        @endif
      @endif
    </div>
  </div>
@endsection
