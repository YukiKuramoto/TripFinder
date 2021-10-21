@extends('layouts.home')
@section('title', 'users')

@section('css')
<link href="{{ asset('css/usersview.css') }}" rel="stylesheet">
<script src="{{ asset('js/mypage.js') }}" defer></script>
@endsection

@section('content')
<div class="users-page">
  <div class="contents-outer">
    <h1 class="contents-title">USERS</h1>
    <div class="contents-wrapper">
      <div class="users-area">
        <ul class="tabs-menu">
          <li><a href="#tabs-1">All Users</a></li>
          <li><a href="#tabs-2">Favorite Users</a></li>
          <li><a href="#tabs-3">Followers</a></li>
        </ul>
        <section class="tabs-content">
          <section id="tabs-1">
            <useritem-component
              :users="{{ json_encode($users[0]) }}"
              :currentUid="{{ Auth::id() }}"
              :length="{{ count($users) }}"
              pagetype="users"
              parameter="all"
            ></useritem-component>
          </section>
          <section id="tabs-2">
            <useritem-component
              :users="{{ json_encode($favorite_users[0]) }}"
              :currentUid="{{ Auth::id() }}"
              :length="{{ count($favorite_users) }}"
              pagetype="users"
              parameter="favorite"
            ></useritem-component>
          </section>
          <section id="tabs-3">
            <useritem-component
              :users="{{ json_encode($follower_users[0]) }}"
              :currentUid="{{ Auth::id() }}"
              :length="{{ count($follower_users) }}"
              pagetype="users"
              parameter="follower"
            ></useritem-component>
          </section>
          </section>
        </section>
      </div>
    </div>
  </div>
</div>
@endsection
