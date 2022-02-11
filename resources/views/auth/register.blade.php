@extends('layouts.auth')

@section('title', 'Registro de Usuario')

@section('content')

<section class="login-block">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-5 col-12 login-sec">
                <h2 class="text-center">Registro de Usuario</h2>
                <form class="login-form" action="{{ route('register') }}" method="POST" id="formRegister">
                    {{ csrf_field() }}

                    @include('admin.partials.errors')

                    <div class="form-group">
                        <label class="col-form-label text-uppercase">NOMBRE<b class="text-danger">*</b></label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" required placeholder="Introduzca un nombre" value="{{ old('name') }}" minlength="2" maxlength="191">
                    </div>

                    <div class="form-group">
                        <label class="col-form-label text-uppercase">APELLIDO<b class="text-danger">*</b></label>
                        <input class="form-control @error('lastname') is-invalid @enderror" type="text" name="lastname" required placeholder="Introduzca un apellido" value="{{ old('lastname') }}" minlength="2" maxlength="191">
                    </div>

                    <div class="form-group">
                        <label class="col-form-label text-uppercase">CORREO<b class="text-danger">*</b></label>
                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" required placeholder="{{ 'correo@gmail.com' }}" value="{{ old('email') }}" minlength="5" maxlength="191">
                    </div>

                    <div class="form-group">
                        <label for="password" class="text-uppercase">CONTRASEÑA</label>
                        <input id="password" name="password" type="password" class="form-control @error('email') is-invalid @enderror" required placeholder="********" minlength="8" maxlength="40">
                    </div>

                    <div class="form-group mb-2">
                        <input type="checkbox" name="terms" required id="terms-conditions">
                        <label class="text-body small mb-0" for="terms-conditions">Acepto <a href="javascript:void(0);" class="text-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal-terms">Términos y condiciones</a></label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-login" action="register">Registrarse</button>
                    </div>

                    <div class="form-group">
                        Deseas ingresar? <a href="{{ route('login') }}"><b>Ingresa</b></a>
                    </div>
                </form>
            </div>

            <div class="col-lg-8 col-md-7 col-12 banner-sec">
                <img class="d-block img-fluid h-100 w-100" src="{{ asset("/auth/image.jpg") }}" alt="Imagen" title="Imagen">
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Términos y Condiciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 70vh; overflow-y: scroll;">
                <div class="row">
                    <div class="col-12">
                        {{-- {!! $setting->terms !!} --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection