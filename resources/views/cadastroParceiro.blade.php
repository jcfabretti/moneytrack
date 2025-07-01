@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1> <strong>CADASTRO DE PARCEIROS</strong></h1>
@stop

@section('content')
    <form>
        <div class="row my-3">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" size="40PX" />
                    <label class="form-label" for="typeText">First name</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">Surname</label>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-12">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">Adress 1</label>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-12">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">Adress 2</label>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">City</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" id="typeText" class="form-control" />
                    <label class="form-label" for="typeText">Zip code</label>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="email" id="typeEmail" class="form-control" />
                    <label class="form-label" for="typeEmail">Email</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="tel" id="typePhone" class="form-control" />
                    <label class="form-label" for="typePhone">Phone number </label>
                </div>
            </div>
        </div>
            <button type="button" class="btn btn-primary">Sign up</button>
        </div>
    </form>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop

