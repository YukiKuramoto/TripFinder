@extends('app')
@section('title', 'PlanPage')

@section('css')
    <link href="{{ asset('css/jquery.tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/accordion.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
@endsection

@section('js')
    <script src="{{ asset('js/jquery.tagsinput.js') }}" defer></script>
    <script src="{{ asset('js/modal.js') }}" defer></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYiiRUxzLEZR56BBQFxGCgcIr88vcnhrU&language=ja"></script>
@endsection

@section('content')
    <div class="contents" id="post-page">
      <postbody-component
        :plandata="{{ $plan }}"
        :spotdata= "{{ json_encode($spot) }}"
        :postuser_view= "{{ $plan->user }}"
        loginuid_view= "{{ $login_uid }}"
        type= "view">
      </postbody-component>
    </div>
@endsection
