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
              pagetype="users/all"
              :response="{{ json_encode($users[0]) }}"
              :login_user="{{ $login_uid }}"
              :prop_total_page="{{ json_encode(count($users)) }}"
            ></useritem-component>
          </section>
          <section id="tabs-2">
            <useritem-component
              pagetype="users/favorite"
              :response="{{ json_encode($favorite_users[0]) }}"
              :login_user="{{ $login_uid }}"
              :prop_total_page="{{ count($favorite_users) }}"
            ></useritem-component>
          </section>
          <section id="tabs-3">
            <useritem-component
              pagetype="users/follower"
              :response="{{ json_encode($follower_users[0]) }}"
              :login_user="{{ $login_uid }}"
              :prop_total_page="{{ count($follower_users) }}"
            ></useritem-component>
          </section>
          </section>
        </section>
      </div>
    </div>
  </div>
</div>
@endsection
