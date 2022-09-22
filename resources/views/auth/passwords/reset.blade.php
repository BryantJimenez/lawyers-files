@extends('layouts.auth')

@section('title', 'Restaurar Contraseña')

@section('content')

<div class="form-container outer">
  <div class="form-form">
    <div class="form-form-wrap">
      <div class="form-container">
        <div class="form-content">

          <h1 class="">Restaurar Contraseña</h1>

          <form class="text-left" action="{{ route('password.update') }}" method="POST" id="formReset">
            {{ csrf_field() }}

            @include('admin.partials.errors')

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form">

              <div id="email-field" class="field-wrapper input">
                <label for="email">CORREO</label>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" required placeholder="{{ 'correo@gmail.com' }}" value="{{ old('email') }}" minlength="5" maxlength="191">
              </div>

              <div id="password-field" class="field-wrapper input mb-2">
                <label for="password">NUEVA CONTRASEÑA</label>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Nueva Contraseña" autocomplete="new-password" minlength="8" maxlength="40">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              </div>

              <div id="password_confirm-field" class="field-wrapper input mb-2">
                <label for="password_confirm">CONFIRMAR CONTRASEÑA</label>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <input id="password_confirm" name="password_confirmation" type="password" class="form-control" required placeholder="Confirmar Contraseña" autocomplete="new-password" minlength="8" maxlength="40">
              </div>

              <div class="d-sm-flex justify-content-between">
                <div class="field-wrapper">
                  <button type="submit" class="btn btn-primary" action="reset">Enviar</button>
                </div>
              </div>

              <p class="signup-link">Deseas ingresar? <a href="{{ route('login') }}">Ingresa</a></p>
            </div>
          </form>

        </div>                    
      </div>
    </div>
  </div>
</div>

@endsection