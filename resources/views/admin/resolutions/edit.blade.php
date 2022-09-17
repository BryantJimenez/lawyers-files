@extends('layouts.admin')

@section('title', 'Editar Resolución')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="javascript:void(0);">Casos</a>
</li>
<li class="breadcrumb-item">
    <a href="javascript:void(0);">Resoluciones</a>
</li>
<li class="breadcrumb-item active" aria-current="page">
    <a href="javascript:void(0);">Editar</a>
</li>
@endsection

@section('links')
<link rel="stylesheet" href="{{ asset('/admins/css/elements/alert.css') }}">
<link href="{{ asset('/admins/vendor/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/admins/vendor/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('/admins/vendor/uploader/jquery.dm-uploader.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admins/vendor/uploader/styles.css') }}">
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
						<h4>Editar Resolución</h4>
					</div>                 
				</div>
			</div>
			<div class="widget-content widget-content-area shadow-none">

				<div class="row">
					<div class="col-12">

						@include('admin.partials.errors')

						<p>Campos obligatorios (<b class="text-danger">*</b>)</p>
						<form action="{{ route('resolutions.update', ['statement' => $statement->slug, 'resolution' => $resolution->slug]) }}" method="POST" class="form" id="formResolution">
							@csrf
							@method('PUT')
							<div class="row">
								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Nombre<b class="text-danger">*</b></label>
									<input class="form-control @error('name') is-invalid @enderror" type="text" name="name" required placeholder="Introduzca un nombre" value="{{ $resolution->name }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Fecha<b class="text-danger">*</b></label>
									<input class="form-control date @error('date') is-invalid @enderror" type="text" name="date" required placeholder="Seleccione una fecha" value="{{ $resolution->date->format('d-m-Y') }}" id="flatpickr">
								</div>

								<div class="form-group col-12">
									<label class="col-form-label">Descripción<b class="text-danger">*</b></label>
									<textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Introduzca una descripción" rows="3">{{ $resolution->description }}</textarea>
								</div>

								<div class="form-group col-12">
									<label class="col-form-label">Documentos (Opcional)</label>
									<div id="drop-area2" class="dm-uploader text-center bg-white py-4 px-2">
										<h3 class="text-muted">Arrastra aquí tus archivos</h3>
										<div class="btn btn-primary btn-block">
											<span>Selecciona un archivo</span>
											<input type="file" title="Selecciona un archivo" multiple>
										</div>
									</div>
									<p id="response" class="text-left py-0"></p>
								</div>

								<div class="col-12">
									<div class="row" id="files">
										@foreach($resolution['files'] as $file)
										<div class="form-group col-lg-3 col-md-3 col-sm-6 col-12" element="{{ $loop->iteration }}">
											<div class="card">
												<div class="card-body p-2">
													<i class="fa fa-2x fa-file mb-2"></i>
													<p class="text-truncate mb-0">{{ $file->name }}</p>
												</div>

												<button type="button" class="btn btn-danger btn-absolute-right removeFile" file="{{ $loop->iteration }}" urlFile="{{ asset('/admins/files/statements/'.$file->name) }}">
													<i class="fa fa-trash"></i>
												</button>
											</div>
										</div>
										@endforeach
									</div>
								</div>

								<input type="hidden" id="statement_slug" value="{{ $statement->slug }}">
								<input type="hidden" id="resolution_slug" value="{{ $resolution->slug }}">

								<div class="form-group col-12">
									<div class="btn-group" role="group">
										<button type="submit" class="btn btn-primary mr-0" action="resolution">Actualizar</button>
										<a href="{{ route('statements.show', ['statement' => $statement->slug, 'resolution' => $resolution->slug]) }}" class="btn btn-secondary">Volver</a>
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
<script src="{{ asset('/admins/vendor/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('/admins/vendor/flatpickr/es.js') }}"></script>
<script src="{{ asset('/admins/vendor/uploader/jquery.dm-uploader.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/jquery.validate.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/additional-methods.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/messages_es.js') }}"></script>
<script src="{{ asset('/admins/js/validate.js') }}"></script>
<script src="{{ asset('/admins/vendor/sweetalerts/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/sweetalerts/custom-sweetalert.js') }}"></script>
<script src="{{ asset('/admins/vendor/lobibox/Lobibox.js') }}"></script>
@endsection