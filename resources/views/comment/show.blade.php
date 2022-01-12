
@extends('layouts.form')
@section('title', 'Comment')

@section('child-css')
<link href="{{ asset('css/comment.css') }}" rel="stylesheet">
@endsection

@section('form-content')
  <div class="comment-outer">
    <div class="comment-wrapper">
      <div class="comment-header">
        <h2>コメント一覧</h2>
      </div>
      <div class="comment-contents-outer">
        <div class="comment-form-area">
          <div class="plan-title-header form-row form-header">
            <p>プランタイトル</p>
          </div>
          <div class="plan-title form-row form-content">
            <p>{{ $plan->plan_title }}</p>
          </div>
          <div class="spot-title-header form-row form-header">
            <p>スポットタイトル</p>
          </div>
          <div class="spot-title form-row form-content">
            <p>{{ $spot->spot_title }}</p>
          </div>
        </div>
      </div>
      <div class="comment-item-outer">
        <commentitem-component
          :plan="{{ $plan }}"
          :comments="{{ $spot->comments }}"
          login_uid="{{ Auth::id() != [] ? Auth::id() : 'undefined_user' }}">
        </commentitem-component>
      </div>
    </div>
  </div>
@endsection
