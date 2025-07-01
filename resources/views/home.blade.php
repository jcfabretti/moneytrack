@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard
        @php
            env('APP_LOCALE');
        @endphp

    </h1>
@stop

@section('content')
    <p>Admin Panel -Welcome</p>
    <x-adminlte-info-box title="Tasks" text="75/100" icon="fas fa-lg fa-tasks text-orange" theme="warning" icon-theme="dark"
        progress=75 progress-theme="dark" description="75% of the tasks have been completed" />

    <div class="info-box">
        <!-- Apply any bg-* class to to the icon to color it -->
        <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Likes</span>
            <span class="info-box-number">93,139</span>
        </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->

    <div class="info-box bg-success">
        <span class="info-box-icon"><i class="far fa-flag"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Bookmarks</span>
            <span class="info-box-number">410</span>
        </div>
    </div>

    <div class="info-box">
        <span class="info-box-icon bg-info"><i class="far fa-bookmark"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Bookmarks</span>
            <span class="info-box-number">41,410</span>
            <div class="progress">
                <div class="progress-bar bg-info" style="width: 70%"></div>
            </div>
            <span class="progress-description">
                70% Increase in 30 Days
            </span>
        </div>
    </div>
  
    <div class="overlay">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
  
    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="card h-100">
          <div class="card-header">
            <span class="me-2"><i class="bi bi-bar-chart-fill"></i></span>
            Area Chart Example
          </div>
          <div class="card-body">
            <canvas class="chart" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card h-100">
          <div class="card-header">
            <span class="me-2"><i class="bi bi-bar-chart-fill"></i></span>
            Area Chart Example
          </div>
          <div class="card-body">
            <canvas class="chart" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
 
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>

    </script>
@stop
