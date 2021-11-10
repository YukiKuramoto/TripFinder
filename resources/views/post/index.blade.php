@extends('layouts.home')
@section('title', 'プラン投稿')

@section('css')
    <link href="{{ asset('css/post.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.tagsinput.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css">
@endsection

@section('js')
    <script src="{{ asset('js/jquery.tagsinput.js') }}" defer></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js" defer></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
@endsection

@section('content')
    <div class="contents" id="post-page">
      <postbody-component
        :url="{{ json_encode(action('PostController@create')) }}"
        :old="{{ json_encode(Session::getOldInput()) }}"
        :errors= "{{ $errors }}"
        type= "post">
      </postbody-component>
    </div>
@endsection
