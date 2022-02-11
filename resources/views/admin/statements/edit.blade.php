@extends('layouts.admin')

@section('title', 'Editar Caso')

@section('links')
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
					<div class="col-xl-12 col-md-12 col-sm-12 col-12">
						<h4>Editar Caso</h4>
					</div>                 
				</div>
			</div>
			<div class="widget-content widget-content-area">

				<div class="row">
					<div class="col-12">

						@include('admin.partials.errors')

						<p>Campos obligatorios (<b class="text-danger">*</b>)</p>
						<form action="{{ route('statements.update', ['statement' => $statement->slug]) }}" method="POST" class="form" id="formStatement">
							@csrf
							@method('PUT')
							<div class="row">
								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Nombre<b class="text-danger">*</b></label>
									<input class="form-control @error('name') is-invalid @enderror" type="text" name="name" required placeholder="Introduzca un nombre" value="{{ $statement->name }}">
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Tipo<b class="text-danger">*</b></label>
									<select class="form-control @error('type') is-invalid @enderror" name="type" required>
										<option value="1" @if($statement->type=="Caso") selected @endif>Caso</option>
										<option value="2" @if($statement->type=="Declaración") selected @endif>Declaración</option>
									</select>
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Empresa<b class="text-danger">*</b></label>
									<select class="form-control @error('company_id') is-invalid @enderror" name="company_id" required>
										<option value="">Seleccione</option>
										@foreach($companies as $company)
										<option value="{{ $company->slug }}" @if($statement->company_id==$company->id) selected @endif>{{ $company->name }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group col-lg-6 col-md-6 col-12">
									<label class="col-form-label">Estado<b class="text-danger">*</b></label>
									<select class="form-control @error('state') is-invalid @enderror" name="state" required>
										<option value="1" @if($statement->state=="Activo") selected @endif>Activo</option>
										<option value="0" @if($statement->state=="Inactivo") selected @endif>Inactivo</option>
									</select>
								</div>

								<div class="form-group col-12">
									<label class="col-form-label">Descripción<b class="text-danger">*</b></label>
									<textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Introduzca una descripción" rows="3">{{ $statement->description }}</textarea>
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
										@foreach($statement['files'] as $file)
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

								<input type="hidden" id="slug" value="{{ $statement->slug }}">

								<div class="form-group col-12">
									<div class="btn-group" role="group">
										<button type="submit" class="btn btn-primary" action="statement">Actualizar</button>
										<a href="{{ route('statements.index') }}" class="btn btn-secondary">Volver</a>
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
<script src="{{ asset('/admins/vendor/uploader/jquery.dm-uploader.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/jquery.validate.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/additional-methods.js') }}"></script>
<script src="{{ asset('/admins/vendor/validate/messages_es.js') }}"></script>
<script src="{{ asset('/admins/js/validate.js') }}"></script>
<script src="{{ asset('/admins/vendor/sweetalerts/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/sweetalerts/custom-sweetalert.js') }}"></script>
<script src="{{ asset('/admins/vendor/lobibox/Lobibox.js') }}"></script>
@endsection