@extends('layouts.app')

@section('title', 'Dashboard Principal')
@section('header', 'Dashboard Principal')

@section('content')
<div id="offers" class="flex">
    <menu-lateral-component></menu-lateral-component>
    <home-dashboard></home-dashboard>
</div
@endsection
