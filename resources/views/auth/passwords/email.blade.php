@extends('layouts.auth')

@section('title', 'Recuperar Contraseña')

@section('content')

<section class="login-block">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-5 col-12 login-sec">
        <h2 class="text-center">Recuperar Contraseña</h2>
        <form class="login-form" action="{{ route('password.email') }}" method="POST" id="formRecovery">
          {{ csrf_field() }}

          @include('admin.partials.errors')

          <div class="form-group">
            <label for="email" class="text-uppercase">CORREO</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" required placeholder="{{ 'correo@gmail.com' }}" value="{{ old('email') }}" minlength="5" maxlength="191">
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-login" action="recovery">Enviar</button>
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

@endsection