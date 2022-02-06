<div class="modal fade" id="modal-recovery" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close close-auth" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <p class="h5 text-body text-center mt-2 mb-3">Recuperar Contraseña</p>

        <div class="form-group col-12">
          @if(!is_null(old('recovery')))
          @include('admin.partials.errors')
          @endif

          @if(session('error.recovery'))
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul>
              <li>{{ session('error.recovery') }}</li>
            </ul>
          </div>
          @endif

          @if(session('success.recovery'))
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul>
              <li>{{ session('success.recovery') }}</li>
            </ul>
          </div>
          @endif
        </div>

        <form action="{{ route('password.email') }}" method="POST" id="formRecovery">
          {{ csrf_field() }}
          <div class="form-group col-12">
            <input class="form-control @error('recovery') is-invalid @enderror py-4 pl-1" type="email" name="recovery" required placeholder="Email" value="{{ old('recovery') }}">
          </div>
          
          <div class="form-group col-12">
            <button type="submit" class="btn btn-primary rounded font-weight-bold text-white w-100" action="recovery">Recuperar</button>
          </div>
          
          <div class="form-group col-12">
            <p class="text-body text-center small mb-0">¿Todavía no tenés una cuenta? <a href="javascript:void(0);" data-dismiss="modal" data-toggle="modal" data-target="#modal-register">Registrate</a></p>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>