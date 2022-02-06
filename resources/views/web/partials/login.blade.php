<div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close close-auth" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <p class="h5 text-body text-center mt-2 mb-3">Ingresar a mi Cuenta</p>

        <div class="form-group col-12">
          @if(is_null(old('name')) && is_null(old('lastname')) && !is_null(old('email')))
          @include('admin.partials.errors')
          @endif

          @if(session('error.login'))
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul>
              <li>{{ session('error.login') }}</li>
            </ul>
          </div>
          @endif
        </div>

        <div class="form-group col-12">
          <button type="submit" class="btn btn-facebook rounded text-white px-3 w-100">Continuar con Facebook</button>
        </div>
        <div class="form-group col-12">
          <div class="line line-top">
            <small class="sub">O ingresar con tu email</small>
            <hr>
          </div>
        </div>
        <form action="{{ route('login') }}" method="POST" id="formLogin">
          {{ csrf_field() }}
          <div class="form-group col-12">
            <input class="form-control @error('email') is-invalid @enderror py-4 pl-1" type="email" name="email" required placeholder="Email" value="{{ old('email') }}">
          </div>
          <div class="form-group col-12">
            <input class="form-control @error('password') is-invalid @enderror py-4 pl-1" type="password" name="password" required placeholder="Contraseña">
          </div>
          <div class="form-group col-12 d-flex justify-content-between">
            <div>
              <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'class="checked"' : '' }}>
              <label class="text-body small mb-0" for="remember">Recordarme</label>
            </div>
            <a href="javascript:void(0);" data-dismiss="modal" data-toggle="modal" data-target="#modal-recovery">Olvidé mi contraseña</a>
          </div>
          <div class="form-group col-12">
            <button type="submit" class="btn btn-primary rounded font-weight-bold text-white w-100" action="login">Ingresar</button>
          </div>
          <div class="form-group col-12">
            <p class="text-body text-center small mb-0">¿Todavía no tenés una cuenta? <a href="javascript:void(0);"  data-dismiss="modal" data-toggle="modal" data-target="#modal-register">Registrate</a></p>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>