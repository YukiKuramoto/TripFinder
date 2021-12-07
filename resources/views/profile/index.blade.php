
@extends('layouts.form')
@section('title', 'profile')

@section('child-css')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('child-js')
<script src="{{ asset('js/profile.js') }}" defer></script>
@endsection

@section('form-content')
  <div class="profile-edit-outer">
    <div class="profile-edit-wrapper">
      <div class="profile-edit-header">
        <h2>プロフィールを編集しよう！</h2>
      </div>
      <div class="profile-form-outer">
        <form class="profile-form-area" action="{{ action('ProfileController@update') }}" method="POST" enctype="multipart/form-data">
          <div class="profile-image-outer">
            <div class="profile-image-header form-row form-header">
              <p>写真を選択</p>
            </div>
            <div class="profile-image-area form-row form-content">
              <input type="file" class="profile-image-input" name="image_path">
              <div class="profile-image-view">
                <img class="preview-image profile-image preview-hide">
                @if(isset($postuser->image_path))
                <img class="preview-current-image profile-image" src="{{ $user->image_path }}">
                @else
                <img class="preview-current-image profile-image" src="{{ asset('image/default.png') }}">
                @endif
              </div>
            </div>
          </div>
          <div class="profile-username-outer">
            <div class="profile-username-header form-row form-header">
              <p>ユーザーネーム</p>
            </div>
            <div class="profile-username-area form-row form-content-outer">
              <input type="text" name="name" class="form-content" value="{{ $user->name }}" required>
            </div>
          </div>
          <div class="profile-comment-outer">
            <div class="profile-comment-header form-row form-header">
              <p>プロフィールコメント</p>
            </div>
            <div class="profile_comment-area form-row form-content-outer">
              <textarea name="comment" rows="4" cols="80" class="form-content">{{ $user->comment }}</textarea>
            </div>
            <div class="user_id">
              <input type="text" name="user_id" value="{{ $user->id }}">
            </div>
            <div class="submit-button-area">
              {{ csrf_field() }}
              <button type="submit" class="btn btn-success comment-submit-btn">プロフィールを更新</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
