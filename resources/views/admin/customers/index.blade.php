@extends('layouts.admin')

@section('title', 'Lista de Clientes')

@section('breadcrumb')
<li class="breadcrumb-item">
	<a href="javascript:void(0);">Clientes</a>
</li>
<li class="breadcrumb-item active" aria-current="page">
	<a href="javascript:void(0);">Lista</a>
</li>
@endsection

@section('links')
<link href="{{ asset('/admins/css/apps/invoice-list.css') }}" rel="stylesheet" type="text/css" />
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
						<h4>Lista de Clientes</h4>
						@can('customers.create')
						<div class="text-right mr-2">
							<a href="{{ route('customers.create') }}" class="btn btn-sm btn-primary">Agregar</a>
						</div>
						@endcan
					</div>
				</div>
			</div>
			<div class="widget-content widget-content-area shadow-none">

				<div class="row">
					<div class="col-12 actions-visible">
						<table class="table table-export">
							<thead>
								<tr>
									<th>#</th>
									<th>Nombre Completo</th>
									<th>Correo</th>
									<th>Nº Empresas</th>
									<th>Estado</th>
									@if(auth()->user()->can('customers.show') || auth()->user()->can('customers.edit') || auth()->user()->can('customers.active') || auth()->user()->can('customers.deactive') || auth()->user()->can('customers.delete'))
									<th class="no-content">Acciones</th>
									@endif
								</tr>
							</thead>
							<tbody>
								@foreach($customers as $customer)
								<tr>
									<td>
										<span class="inv-number">{{ $loop->iteration }}</span>
									</td>
									<td>
										<div class="d-flex">
											<div class="usr-img-frame mr-2 rounded-circle">
												<img alt="avatar" class="img-fluid rounded-circle" src="{{ image_exist('/admins/img/users/', $customer->photo, true) }}" alt="{{ $customer->name." ".$customer->lastname }}" title="{{ $customer->name." ".$customer->lastname }}">
											</div>
											<p class="align-self-center mb-0 user-name">{{ $customer->name." ".$customer->lastname }}</p>
										</div>
									</td>
									<td>
										<span class="inv-email"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> {{ $customer->email }}</span>
									</td>
									<td>{{ $customer['companies']->count() }}</td>
									<td>{!! state($customer->state, 'invoice') !!}</td>
									@if(auth()->user()->can('customers.show') || auth()->user()->can('customers.edit') || auth()->user()->can('customers.active') || auth()->user()->can('customers.deactive') || auth()->user()->can('customers.delete'))
									<td>
										<div class="dropdown">
											<a class="dropdown-toggle" href="#" role="button" id="actionsTable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
											</a>
											<div class="dropdown-menu" aria-labelledby="actionsTable">
												@can('customers.show')
												<a class="dropdown-item action-show" href="{{ route('customers.show', ['customer' => $customer->slug]) }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Perfil</a>
												@endcan
												@can('customers.edit')
												<a class="dropdown-item action-edit" href="{{ route('customers.edit', ['customer' => $customer->slug]) }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>Editar</a>
												@endcan
												@if($customer->state=='Activo')
												@can('customers.deactive')
												<a class="dropdown-item action-warning" href="javascript:void(0);" onclick="deactiveCustomer('{{ $customer->slug }}')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-power"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>Desactivar</a>
												@endcan
												@else
												@can('customers.active')
												<a class="dropdown-item action-success" href="javascript:void(0);" onclick="activeCustomer('{{ $customer->slug }}')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>Activar</a>
												@endcan
												@endif
												@can('customers.delete')
												<a class="dropdown-item action-delete" href="javascript:void(0);" onclick="deleteCustomer('{{ $customer->slug }}')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>Eliminar</a>
												@endcan
											</div>
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

</div>

@can('customers.deactive')
<div class="modal fade" id="deactiveCustomer" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Estás seguro de que quieres desactivar este cliente?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-dismiss="modal">Cancelar</button>
				<form action="#" method="POST" id="formDeactiveCustomer">
					@csrf
					@method('PUT')
					<button type="submit" class="btn btn-primary">Desactivar</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endcan

@can('customers.active')
<div class="modal fade" id="activeCustomer" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Estás seguro de que quieres activar este cliente?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-dismiss="modal">Cancelar</button>
				<form action="#" method="POST" id="formActiveCustomer">
					@csrf
					@method('PUT')
					<button type="submit" class="btn btn-primary">Activar</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endcan

@can('customers.delete')
<div class="modal fade" id="deleteCustomer" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Estás seguro de que quieres eliminar este cliente?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-dismiss="modal">Cancelar</button>
				<form action="#" method="POST" id="formDeleteCustomer">
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