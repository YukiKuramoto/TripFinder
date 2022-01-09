
@extends('app')

@section('css')
<link href="{{ asset('css/formpage.css') }}" rel="stylesheet">
@yield('child-css')
@endsection

@section('js')
@yield('child-js')
@endsection

@section('content')
<div class="formpage">
  <div class="form-outer">
    <div class="form-containers">
      @yield('form-content')
    </div>
  </div>
</div>
@endsection
