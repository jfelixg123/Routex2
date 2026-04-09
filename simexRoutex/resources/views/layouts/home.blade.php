@extends('layouts.app')

@section('title', 'Dashboard Principal')
@section('header', 'Dashboard Principal')

@section('content')
<div id="offers" class="flex w-full">
    <!--<menu-lateral-component></menu-lateral-component>
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        <home-dashboard></home-dashboard>
    </div>-->
    <app-wrapper></app-wrapper>
</div
@endsection
