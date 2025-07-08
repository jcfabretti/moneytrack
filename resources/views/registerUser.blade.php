@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<p class="mt-3 mb-1">
    Input with class w-25
</p>
<div class="form-outline w-25">
    <input type="text" id="input1" class="form-control" />
    <label class="form-label" for="input1">25% width of the parent</label>
</div>
<p class="mt-3 mb-1">
    Input with class w-50
</p>
<div class="form-outline w-50">
    <input type="text" id="input2" class="form-control" />
    <label class="form-label" for="input2">50% width of the parent</label>
</div>
<p class="mt-3 mb-1">
    Input with class w-75
</p>
<div class="form-outline w-75">
    <input type="text" id="input3" class="form-control" />
    <label class="form-label" for="input3">75% width of the parent</label>
</div>
<p class="mt-3 mb-1">
    Input without sizing class
</p>
<div class="form-outline">
    <input type="text" id="input4" class="form-control" />
    <label class="form-label" for="input4">100% width of the parent</label>
</div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
