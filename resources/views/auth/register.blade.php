@extends('adminlte::page')

@section('title', 'Registrar Usuário')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6 mt-40">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Registro de Usuário</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.register.store') }}" method="POST">
                    @csrf

                    {{-- Nome --}}
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Nome completo" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                        @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Senha --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Senha">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirmar senha --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Confirme a senha">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                        @error('password_confirmation')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Botão --}}
                    <button type="submit" class="btn btn-success btn-block">
                        <span class="fas fa-user-plus mr-2"></span> Registrar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop


