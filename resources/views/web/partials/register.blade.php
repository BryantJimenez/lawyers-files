<div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close close-auth" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="h5 text-body text-center mt-2 mb-3">Registrate</p>

                <div class="form-group col-12">
                    @if(!is_null(old('name')) && !is_null(old('lastname')) && !is_null(old('email')))
                    @include('admin.partials.errors')
                    @endif

                    @if(session('error.register'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <ul>
                            <li>{{ session('error.register') }}</li>
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
                <form action="{{ route('register') }}" method="POST" id="formRegister">
                    {{ csrf_field() }}
                    <div class="form-group col-12">
                        <input class="form-control @error('name') is-invalid @enderror py-4 pl-1" type="text" name="name" required placeholder="Nombre" value="{{ old('name') }}" autocomplete="name">
                    </div>
                    <div class="form-group col-12">
                        <input class="form-control @error('lastname') is-invalid @enderror py-4 pl-1" type="text" name="lastname" required placeholder="Apellido" value="{{ old('lastname') }}" autocomplete="lastname">
                    </div>
                    <div class="form-group col-12">
                        <input class="form-control @error('email') is-invalid @enderror py-4 pl-1" type="email" name="email" required placeholder="Email"  value="{{ old('email') }}">
                    </div>
                    <div class="form-group col-12">
                        <input class="form-control @error('password') is-invalid @enderror py-4 pl-1" type="password" name="password" required placeholder="Contraseña">
                    </div>
                    <div class="form-group col-12 mb-2">
                        <input type="checkbox" name="terms" required id="terms-conditions">
                        <label class="text-body small mb-0" for="terms-conditions">Acepto <a href="javascript:void(0);" data-dismiss="modal" data-toggle="modal" data-target="#modal-terms">Términos y condiciones</a></label>
                    </div>
                    <div class="form-group col-12">
                        <button type="submit" class="btn btn-primary rounded font-weight-bold text-white w-100" action="register">Registrarme</button>
                    </div>
                    <div class="form-group col-12">
                        <p class="text-body text-center small mb-0">¿Ya tenés una cuenta? <a href="javascript:void(0);" data-dismiss="modal" data-toggle="modal" data-target="#modal-login">Ingresar</a></p>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>