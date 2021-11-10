@extends('layouts.home')
@section('title', 'login')

@section('css')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="login">
  <div class="contents-outer">
    <div class="form-wrapper">
      <h1>Register</h1>
      <p>アカウントを作成しよう</p>
      <form method="POST" action="{{ route('register') }}">
        @csrf
        @foreach ($errors->all() as $error)
          <span class="danger">{!! $error !!}</span>
        @endforeach
        <div class="form-item">
          <label for="name"></label>
          <input type="text" name="name" required="required" value="{{ old('name') }}" placeholder="User Name"></input>
        </div>
        <div class="form-item">
          <label for="email"></label>
          <input type="email" name="email" required="required" value="{{ old('email') }}" placeholder="Email"></input>
        </div>
        <div class="form-item">
          <label for="password"></label>
          <input type="password" name="password" required="required"  placeholder="Password"></input>
        </div>
        <div class="form-item">
            <label for="password-confirm"></label>
            <input type="password" name="password_confirmation" required="required"  placeholder="Password Confirm"></input>
        </div>
        <div class="button-panel">
          <input type="submit" class="button" title="Log In" value="Create  account"></input>
        </div>
      </form>
      <div class="form-footer">
        <p><a href="/login">Login your account</a></p>
      </div>
    </div>
  </div>
</div>
@endsection
