@extends('layouts.admin')

@section('title', 'Editar Ajustes')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="javascript:void(0);">Ajustes</a>
</li>
<li class="breadcrumb-item active" aria-current="page">
    <a href="javascript:void(0);">Editar</a>
</li>
@endsection

@section('links')
<link rel="stylesheet" href="{{ asset('/admins/css/elements/alert.css') }}">
<link href="{{ asset('/admins/vendor/sweetalerts/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/admins/vendor/sweetalerts/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/admins/css/components/custom-sweetalert.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('/admins/vendor/lobibox/Lobibox.min.css') }}">
@endsection

@section('content')

<div class="row layout-top-spacing">
	<div class="col-12 layout-spacing">
    	<div class="statbox widget box box-shadow">
	        <div class="widget-header">
	            <div class="row">
	                <div class="col-12">
	                    <h4>Editar Ajustes</h4>
	                </div>
	            </div>
	        </div>
	        <div class="widget-content widget-content-area shadow-none">

				<div class="row">
					<div class="col-12">

						@include('admin.partials.errors')

						<p>Campos obligatorios (<b class="text-danger">*</b>)</p>
						<form action="{{ route('settings.update') }}" method="POST" class="form" id="formSetting">
							@csrf
							@method('PUT')
							<div class="row">
								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Client ID de Google Drive<b class="text-danger">*</b></label>
									<input class="form-control @error('google_drive_client_id') is-invalid @enderror" type="text" name="google_drive_client_id" required placeholder="Introduzca un client ID de google drive" value="{{ $setting->google_drive_client_id }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Client Secret de Google Drive<b class="text-danger">*</b></label>
									<input class="form-control @error('google_drive_client_secret') is-invalid @enderror" type="text" name="google_drive_client_secret" required placeholder="Introduzca un client secret de google drive" value="{{ $setting->google_drive_client_secret }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Refresh Token de Google Drive<b class="text-danger">*</b></label>
									<input class="form-control @error('google_drive_refresh_token') is-invalid @enderror" type="text" name="google_drive_refresh_token" required placeholder="Introduzca un refresh token de google drive" value="{{ $setting->google_drive_refresh_token }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">ID de Carpeta de Google Drive<b class="text-danger">*</b></label>
									<input class="form-control @error('google_drive_folder_id') is-invalid @enderror" type="text" name="google_drive_folder_id" required placeholder="Introduzca un ID de carpeta de google drive" value="{{ $setting->google_drive_folder_id }}">
								</div>

								<div class="form-group col-12">
									<div class="btn-group" role="group">
										<button type="submit" class="btn btn-primary mr-0" action="setting">Actualizar</button>
									</div>
								</div> 
							</div>
						</form>
					</div>                                        
				</div>

			</div>
		</div>
	</div>

</div>

@endsection

@section('scripts')
<script src="{{ asset('/admins/vendor/validate/jquery.validate.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/additional-methods.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/messages_es.js') }}"></script>
<script src="{{ asset('/admins/js/validate.js') }}"></script>
<script src="{{ asset('/admins/vendor/sweetalerts/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/sweetalerts/custom-sweetalert.js') }}"></script>
<script src="{{ asset('/admins/vendor/lobibox/Lobibox.js') }}"></script>
@endsection