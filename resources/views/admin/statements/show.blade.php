@extends('layouts.admin')

@section('title', 'Detalles del Caso')

@section('breadcrumb')
<li class="breadcrumb-item">
	<a href="javascript:void(0);">Casos</a>
</li>
<li class="breadcrumb-item active" aria-current="page">
	<a href="javascript:void(0);">Perfil</a>
</li>
@endsection

@section('links')
<link href="{{ asset('/admins/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('/admins/vendor/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/admins/vendor/table/datatable/custom_dt_html5.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/admins/vendor/table/datatable/dt-global_style.css') }}">
<link href="{{ asset('/admins/css/components/custom-modal.css') }}" rel="stylesheet" type="text/css" />
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
					<div class="col-12 d-flex justify-content-between align-items-center">
						<h4>Resoluciones del Caso</h4>
						@can('resolutions.create')
						<div class="text-right mr-2">
							<a href="{{ route('resolutions.create', ['statement' => $statement->slug]) }}" class="btn btn-sm btn-primary">Agregar</a>
						</div>
						@endcan
					</div>
				</div>
			</div>
			<div class="widget-content widget-content-area shadow-none">

				<div class="row">
					<div class="col-12">
						<table class="table table-hover table-normal">
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
											<a href="{{ route('resolutions.show', ['statement' => $statement->slug, 'resolution' => $resolution->slug]) }}" class="btn btn-primary btn-sm bs-tooltip mr-0" title="Detalles">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder-plus"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
											</a>
											@endcan
											@can('resolutions.edit')
											<a href="{{ route('resolutions.edit', ['statement' => $statement->slug, 'resolution' => $resolution->slug]) }}" class="btn btn-info btn-sm bs-tooltip mr-0" title="Editar">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
											</a>
											@endcan
											@can('resolutions.delete')
											<button type="button" class="btn btn-danger btn-sm bs-tooltip mr-0" title="Eliminar" onclick="deleteResolution('{{ $statement->slug }}', '{{ $resolution->slug }}')">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
											</button>
											@endcan
										</div>
									</td>
									@endif
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="@if(!is_null($statement['company'])) col-xl-8 col-lg-6 col-md-6 @endif col-12 layout-spacing">

		<div class="user-profile">
			<div class="widget-content widget-content-area p-4">
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
								<span class="h6 text-black"><b>Tipo:</b> {!! type($statement['type'], 'invoice') !!}</span>
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
								<span class="h6 text-black"><b>Estado:</b> {!! state($statement->state, 'invoice') !!}</span>
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
	<div class="col-xl-4 col-lg-6 col-md-6 col-12 layout-spacing">

		<div class="user-profile layout-spacing">
			<div class="widget-content widget-content-area p-4">
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
								<span class="h6 text-black"><b>Estado:</b> {!! state($statement['company']->state, 'invoice') !!}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
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
<script src="{{ asset('/admins/vendor/sweetalerts/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/admins/vendor/sweetalerts/custom-sweetalert.js') }}"></script>
<script src="{{ asset('/admins/vendor/lobibox/Lobibox.js') }}"></script>
@endsection