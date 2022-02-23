@extends('layouts.admin')

@section('title', 'Detalles del Caso')

@section('links')
<link href="{{ asset('/admins/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('/admins/vendor/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/admins/vendor/table/datatable/custom_dt_html5.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/admins/vendor/table/datatable/dt-global_style.css') }}">
@endsection

@section('content')

<div class="row">
	<div class="@if(!is_null($statement['company'])) col-xl-8 col-lg-6 col-md-6 @endif col-12 layout-top-spacing">

		<div class="user-profile layout-spacing">
			<div class="widget-content widget-content-area">
				<div class="d-flex justify-content-between">
					<h3 class="pb-3">Datos del Caso</h3>
				</div>
				<div class="user-info-list">

					<div class="">
						<ul class="contacts-block list-unstyled mw-100 mx-2">
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Nombre:</b> {{ $statement->name }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Tipo:</b> {!! type($statement->type) !!}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Descripción:</b> {{ $statement->description }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Cantidad de Resoluciones:</b> {{ $statement['resolutions']->count() }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Fecha:</b> {{ $statement->created_at->format('d-m-Y') }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Estado:</b> {!! state($statement->state) !!}</span>
							</li>
							<li class="contacts-block__item">
								<a href="{{ route('statements.index') }}" class="btn btn-secondary">Volver</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	@if(!is_null($statement['company']))
	<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 layout-top-spacing">

		<div class="user-profile layout-spacing">
			<div class="widget-content widget-content-area">
				<div class="d-flex justify-content-between">
					<h3 class="pb-3">Datos de la Empresa</h3>
				</div>
				<div class="user-info-list">

					<div class="">
						<ul class="contacts-block list-unstyled mw-100 mx-2">
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Nombre:</b> {{ $statement['company']->name }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Razón Social:</b> {{ $statement['company']->social_reason }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>RFC:</b> {{ $statement['company']->rfc }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Dirección:</b> {{ $statement['company']->address }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Propietario:</b> @if(!is_null($statement['company']['user'])){{ $statement['company']['user']->name.' '.$statement['company']['user']->lastname }}@else{{ 'Desconocido' }}@endif</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Estado:</b> {!! state($statement['company']->state) !!}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif

	<div class="col-12 layout-top-spacing">

		<div class="user-profile layout-spacing">
			<div class="widget-content widget-content-area">
				<div class="d-flex justify-content-between">
					<h3 class="pb-3">Resoluciones del Caso</h3>
				</div>
				<div class="user-info-list">

					<div class="">
						<ul class="contacts-block list-unstyled mw-100 mx-2">
							<li class="contacts-block__item">
								@can('resolutions.create')
								<div class="mb-3">
									<a href="{{ route('resolutions.create', ['statement' => $statement->slug]) }}" class="btn btn-primary">Agregar</a>
								</div>
								@endcan

								<div class="table-responsive">
									<table class="table table-normal table-hover">
										<thead>
											<tr>
												<th>#</th>
												<th>Nombre</th>
												<th>Nº Documentos</th>
												<th>Fecha</th>
												@if(auth()->user()->can('resolutions.show') || auth()->user()->can('resolutions.edit') || auth()->user()->can('resolutions.delete'))
												<th>Acciones</th>
												@endif
											</tr>
										</thead>
										<tbody>
											@foreach($statement['resolutions'] as $resolution)
											<tr>
												<td>{{ $loop->iteration }}</td>
												<td>{{ $resolution->name }}</td>
												<td>{{ $resolution['files']->count() }}</td>
												<td>{{ $resolution->date->format('d-m-Y') }}</td>
												@if(auth()->user()->can('resolutions.show') || auth()->user()->can('resolutions.edit') || auth()->user()->can('resolutions.delete'))
												<td>
													<div class="btn-group" role="group">
														@can('resolutions.show')
														<a href="{{ route('resolutions.show', ['statement' => $statement->slug, 'resolution' => $resolution->slug]) }}" class="btn btn-primary btn-sm bs-tooltip" title="Perfil"><i class="fa fa-user"></i></a>
														@endcan
														@can('resolutions.edit')
														<a href="{{ route('resolutions.edit', ['statement' => $statement->slug, 'resolution' => $resolution->slug]) }}" class="btn btn-info btn-sm bs-tooltip" title="Editar"><i class="fa fa-edit"></i></a>
														@endcan
														@can('resolutions.delete')
														<button type="button" class="btn btn-danger btn-sm bs-tooltip" title="Eliminar" onclick="deleteResolution('{{ $statement->slug }}', '{{ $resolution->slug }}')"><i class="fa fa-trash"></i></button>
														@endcan
													</div>
												</td>
												@endif
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@can('resolutions.delete')
<div class="modal fade" id="deleteResolution" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Estás seguro de que quieres eliminar esta resolución?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-dismiss="modal">Cancelar</button>
				<form action="#" method="POST" id="formDeleteResolution">
					@csrf
					@method('DELETE')
					<button type="submit" class="btn btn-primary">Eliminar</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endcan

@endsection

@section('scripts')
<script src="{{ asset('/admins/vendor/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('/admins/vendor/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/table/datatable/button-ext/jszip.min.js') }}"></script>    
<script src="{{ asset('/admins/vendor/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/table/datatable/button-ext/buttons.print.min.js') }}"></script>
@endsection