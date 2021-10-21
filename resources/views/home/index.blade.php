@extends('layouts.home')
@section('title', 'ホーム画面')

@section('css')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection


@section('content')
    <div class="contents">
        <div id="cover">
            <image id="cover-image" src="{{ asset('image/home_cover.png') }}">
        </div>
        <div id="contents-main">
            <div class="wrapper" id="search-wrapper">
                <h2>お出かけプランを検索してみよう</h2>
                <div id="search-form">
                  <form class="search_container" method="post" action="{{ action('SearchController@mainSearch') }}">
                    {{ csrf_field() }}
                    <div class="search-box-wrapper">
                      <input type="text" size="25" name="search_word" placeholder="キーワード検索">
                      <input type="text" size="25" name="search_type" value="plan" style="display:none;">
                      <button type="submit"><i class="bi bi-search"></i></button>
                    </div>
                  </form>
                </div>
            </div>
            <div class="wrapper" id="popular-area-wrapper">
                <h2>人気のエリアで検索</h2>
                <section id="popular-area-section">
                    <div id="popular-area-list">
                      <a href="#">
                        <div>
                          <img src="{{ asset('image/home_Tokyo.png') }}" class="popular-area-image">
                          <h4>東京</h4>
                        </div>
                      </a>
                      <a href="#">
                        <div>
                          <img src="{{ asset('image/home_Osaka.png') }}" class="popular-area-image">
                          <h4>大阪</h4>
                        </div>
                      </a>
                      <a href="#">
                        <div>
                          <img src="{{ asset('image/home_Kyoto.png') }}" class="popular-area-image">
                          <h4>京都</h4>
                        </div>
                      </a>
                      <a href="#">
                        <div>
                          <img src="{{ asset('image/home_Okinawa.png') }}" class="popular-area-image">
                          <h4>沖縄</h4>
                        </div>
                      </a>
                    </div>
                </section>
            </div>
            <div  class="wrapper" id="plan-wrapper">
                <div id="new-plan-wrapper">
                    <h2>新着プラン</h2>
                    <planitem-component
                      :plans="{{ json_encode($newArrivalPlans[0]) }}"
                      :length="{{ count($newArrivalPlans) }}"
                      pagetype="home"
                      parameter="{{ $param_newArrival }}"
                    ><planitem-component/>
                </div>
                <div id="popular-plan-wrapper">
                    <h2>人気プラン</h2>
                    <planitem-component
                      :plans="{{ json_encode($popularPlans[0]) }}"
                      :length="{{ count($popularPlans) }}"
                      pagetype="home"
                      parameter="{{ $param_popular }}"
                    ><planitem-component/>
                </div>
            </div>
            <div class="wrapper" id="spot-wrapper">
                <h2>人気スポット</h2>
                <div class="spot-component-area">
                  <div class="spot-component-outer">
                    <spotitem-component
                    :spots="{{ json_encode($popularSpots[0]) }}"
                    :length="{{ count($popularSpots) }}"
                    pagetype="home"
                    parameter="{{ $param_popular }}"
                    ><spotitem-component/>
                  </div>
                </div>
            </div>
        </div>
    </div>
@endsection
