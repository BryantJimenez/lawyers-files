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
<link rel="stylesheet" href="{{ asset('/admins/vendor/dropify/dropify.min.css') }}">
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
						<form action="{{ route('settings.update') }}" method="POST" class="form" id="formSetting" enctype="multipart/form-data">
							@csrf
							@method('PUT')
							<div class="row">
								<div class="form-group col-12">
									<label class="col-form-label">Logo<b class="text-danger">*</b></label>
									<input type="file" name="logo" accept="image/*" class="dropify" data-height="125" data-max-file-size="20M" data-allowed-file-extensions="jpg png jpeg web3 svg" data-default-file="{{ image_exist('/admins/img/', $setting->logo, false, false) }}" />
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Color de Fondo de la Barra Superior<b class="text-danger">*</b></label>
									<input class="form-control @error('header_background_color') is-invalid @enderror" type="color" name="header_background_color" required placeholder="Introduzca un color de fondo de la barra superior" value="{{ $setting->header_background_color }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Color de Texto de la Barra Superior<b class="text-danger">*</b></label>
									<input class="form-control @error('header_text_color') is-invalid @enderror" type="color" name="header_text_color" required placeholder="Introduzca un color de texto de la barra superior" value="{{ $setting->header_text_color }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Color de Fondo del Menú<b class="text-danger">*</b></label>
									<input class="form-control @error('menu_background_color') is-invalid @enderror" type="color" name="menu_background_color" required placeholder="Introduzca un color de fondo del menú" value="{{ $setting->menu_background_color }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Color de Fondo del Menú (Activado)<b class="text-danger">*</b></label>
									<input class="form-control @error('menu_background_color_hover') is-invalid @enderror" type="color" name="menu_background_color_hover" required placeholder="Introduzca un color de fondo del menú (activado)" value="{{ $setting->menu_background_color_hover }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Color de Iconos del Menú<b class="text-danger">*</b></label>
									<input class="form-control @error('menu_icon_color') is-invalid @enderror" type="color" name="menu_icon_color" required placeholder="Introduzca un color de iconos del menú" value="{{ $setting->menu_icon_color }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Color de Texto del Menú<b class="text-danger">*</b></label>
									<input class="form-control @error('menu_text_color') is-invalid @enderror" type="color" name="menu_text_color" required placeholder="Introduzca un color de texto del menú" value="{{ $setting->menu_text_color }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Color del Borde del Menú<b class="text-danger">*</b></label>
									<input class="form-control @error('menu_border_color') is-invalid @enderror" type="color" name="menu_border_color" required placeholder="Introduzca un color del borde del menú" value="{{ $setting->menu_border_color }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Texto de la Barra Superior (Opcional)</label>
									<input class="form-control @error('header_text') is-invalid @enderror" type="text" name="header_text" placeholder="Introduzca un texto de la barra superior" value="{{ $setting->header_text }}">
								</div>

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
<script src="{{ asset('/admins/vendor/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/jquery.validate.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/additional-methods.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/messages_es.js') }}"></script>
<script src="{{ asset('/admins/js/validate.js') }}"></script>
<script src="{{ asset('/admins/vendor/sweetalerts/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/sweetalerts/custom-sweetalert.js') }}"></script>
<script src="{{ asset('/admins/vendor/lobibox/Lobibox.js') }}"></script>
@endsection