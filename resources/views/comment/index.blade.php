
@extends('layouts.form')
@section('title', 'comment')

@section('child-css')
<link href="{{ asset('css/comment.css') }}" rel="stylesheet">
@endsection

@section('form-content')
  <div class="comment-outer">
    <div class="comment-wrapper">
      <div class="comment-header">
        <h2>コメントを投稿しよう！</h2>
      </div>
      <div class="comment-contents-outer">
        <form class="comment-form-area" method="POST" action="{{ action('Main\CommentController@create', ['user_id' => $user, 'spot_id' => $spot->id, 'plan_id' => $plan->id]) }}">
          <div class="spot-title-header form-row form-header">
            <p>スポットタイトル</p>
          </div>
          <div class="spot-title form-row form-content">
            <p>{{ $spot->spot_title }}</p>
          </div>
          <div class="comment-title-header form-row form-header">
            <p>コメントタイトル</p>
          </div>
          <div class="comment-title form-row form-content">
            <input type="text" name="comment_title" required>
          </div>
          <div class="comment-contents-header form-row form-header">
            <p>コメント内容</p>
          </div>
          <div class="comment-contents form-row form-content">
            <textarea name="comment_content" rows="8" cols="80" required></textarea>
          </div>
          <div class="submit-button-area">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-success comment-submit-btn">コメント投稿</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
