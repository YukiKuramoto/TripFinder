
@extends('layouts.form')
@section('title', 'profile')

@section('child-css')
<link href="{{ asset('css/searchpage.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
@endsection

@section('child-js')
<script src="{{ asset('js/accordion.js') }}" defer></script>
@endsection

@section('form-content')
@if(!isset($result))
<searchbody-component
></searchbody-component>
@elseif($result == 'no_result')
<searchbody-component
  :prop_result="{{ json_encode($result) }}"
  :prop_search_key="{{ json_encode($search_key) }}"
></searchbody-component>
@else
<searchbody-component
  :prop_response="{{ json_encode($response) }}"
  :prop_length="{{ count($response) }}"
  :prop_search_key="{{ json_encode($search_key) }}"
  :prop_parameter="{{ json_encode($parameter) }}"
  :prop_result="{{ json_encode($result) }}"
></searchbody-component>
@endif
@endsection
