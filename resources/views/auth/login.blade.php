@extends('app')
@section('title', 'Login')

@section('css')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="login">
  <div class="contents-outer">
    <div class="form-wrapper">
      <h1>Log In</h1>
      <p>まずはログインしてみよう</p>
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-item">
          <label for="email"></label>
          <input type="email" name="email" required="required" value="{{ old('email') }}" placeholder="Email"></input>
        </div>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="form-item">
          <label for="password"></label>
          <input type="password" name="password" required="required"  placeholder="Password"></input>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="button-panel">
          <input type="submit" class="button" title="Log In" value="Log In"></input>
        </div>
      </form>
      <div class="form-footer">
        <p><a href="/register">Create an account</a></p>
      </div>
    </div>
  </div>
</div>
@endsection
