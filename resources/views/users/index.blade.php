@extends('app')
@section('title', 'Users')

@section('css')
<link href="{{ asset('css/usersview.css') }}" rel="stylesheet">
<link href="{{ asset('css/tabs.css') }}" rel="stylesheet">
<script src="{{ asset('js/tabs.js') }}" defer></script>
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
              :response="{{ json_encode($users[0]) }}"
              :login_user="{{ $login_uid }}"
              :length="{{ json_encode(count($users)) }}"
              pagetype="users/all"
              parameter="all"
            ></useritem-component>
          </section>
          <section id="tabs-2">
            <useritem-component
              :response="{{ json_encode($favorite_users[0]) }}"
              :login_user="{{ $login_uid }}"
              :length="{{ count($favorite_users) }}"
              pagetype="users/favorite"
              parameter="favorite"
            ></useritem-component>
          </section>
          <section id="tabs-3">
            <useritem-component
              :response="{{ json_encode($follower_users[0]) }}"
              :login_user="{{ $login_uid }}"
              :length="{{ count($follower_users) }}"
              pagetype="users/follower"
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
