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
                <i class="bi bi-alarm"></i>
                <div id="search-form">
                    <form>
                        <div>
                            <input type="text" class="form-item" id="form-control" name="cond_title" value="">
                            <button class="form-item btn btn-secondary">検索</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="wrapper" id="popular-area-wrapper">
                <h2>人気のエリアで検索</h2>
                <section id="popular-area-section">
                    <div id="popular-area-list">
                        <a>東京</a>
                        <a>京都</a>
                        <a>大阪</a>
                        <a>沖縄</a>
                        <a>四国</a>
                        <a>名古屋</a>
                        <a>北海道</a>
                    </div>
                </section>
            </div>
            <div  class="wrapper" id="plan-wrapper">
                <div id="new-plan-wrapper">
                    <h2>新着プラン</h2>
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
                </div>
                <div id="popular-plan-wrapper">
                    <h2>人気プラン</h2>
                    <div class="plan-item-wrapper">
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
                                <img class="plan-image" src="{{ asset('image/test2.jpg') }}">
                            </div>
                            <div class="plan-outline-wrapper">
                                <p class="plan-title">これはテストプランです。</p>
                                <p class="plan-introduction">これはテストプラン紹介文です。</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="wrapper" id="spot-wrapper">
                <h2>人気スポット</h2>
                <div class="spot-item-wrapper">
                    <a href="" class="spot-item">
                        <div class="spot-image-wrapper">
                            <img class="spot-image" src="{{ asset('image/test1.jpeg') }}">
                        </div>
                        <div class="spot-outline-wrapper">
                            <p class="spot-title">これはテストスポットです。</p>
                            <p class="spot-introduction">これはテストスポット紹介です。</p>
                        </div>
                    </a>
                    <a href="" class="spot-item">
                        <div class="spot-image-wrapper">
                            <img class="spot-image" src="{{ asset('image/test1.jpeg') }}">
                        </div>
                        <div class="spot-outline-wrapper">
                            <p class="spot-title">これはテストスポットです。</p>
                            <p class="spot-introduction">これはテストスポット紹介です。</p>
                        </div>
                    </a>
                    <a href="" class="spot-item">
                        <div class="spot-image-wrapper">
                            <img class="spot-image" src="{{ asset('image/test1.jpeg') }}">
                        </div>
                        <div class="spot-outline-wrapper">
                            <p class="spot-title">これはテストスポットです。</p>
                            <p class="spot-introduction">これはテストスポット紹介です。</p>
                        </div>
                    </a>
                    <a href="" class="spot-item">
                        <div class="spot-image-wrapper">
                            <img class="spot-image" src="{{ asset('image/test1.jpeg') }}">
                        </div>
                        <div class="spot-outline-wrapper">
                            <p class="spot-title">これはテストスポットです。</p>
                            <p class="spot-introduction">これはテストスポット紹介です。</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection