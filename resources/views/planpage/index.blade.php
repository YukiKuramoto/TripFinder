@extends('layouts.home')
@section('title', 'プラン投稿')

@section('css')
    <link href="{{ asset('css/planpage.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.tagsinput.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
@endsection

@section('js')
    <script src="{{ asset('js/jquery.tagsinput.js') }}" defer></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYiiRUxzLEZR56BBQFxGCgcIr88vcnhrU&language=ja"></script>
@endsection

@section('content')
    <div class="contents" id="post-page">
      <planpage-component
        :plan="{{ $plan }}"
        :spot= "{{ $spot }}">
      </planpage-component>
      <!-- <postpage-component
        :old="{{ json_encode(Session::getOldInput()) }}"
        :errors= "{{ $errors }}">
      </postpage-component> -->
    </div>
@endsection
