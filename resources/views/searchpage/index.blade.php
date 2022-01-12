
@extends('layouts.form')
@section('title', 'Search')

@section('child-css')
<link href="{{ asset('css/searchpage.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
@endsection

@section('child-js')
<script src="{{ asset('js/accordion.js') }}" defer></script>
@endsection

@section('form-content')

@if(!is_null(Auth::user()))
  @if(isset($search_key))
  <searchbody-component
    :prop_search_key="{{ json_encode($search_key) }}"
    :prop_login_uid="{{ Auth::user()->id }}"
  ></searchbody-component>
  @else
  <searchbody-component
    :prop_login_uid="{{ Auth::user()->id }}"
  ></searchbody-component>
  @endif
@else
  @if(isset($search_key))
  <searchbody-component
    :prop_search_key="{{ json_encode($search_key) }}"
  ></searchbody-component>
  @else
  <searchbody-component
  ></searchbody-component>
  @endif
@endif

@endsection
