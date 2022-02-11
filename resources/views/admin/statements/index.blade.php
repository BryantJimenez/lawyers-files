@extends('layouts.admin')

@section('title', 'Lista de Casos')

@section('links')
<link rel="stylesheet" type="text/css" href="{{ asset('/admins/vendor/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/admins/vendor/table/datatable/custom_dt_html5.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/admins/vendor/table/datatable/dt-global_style.css') }}">
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
						<h4>Lista de Casos</h4>
					</div>                 
				</div>
			</div>
			<div class="widget-content widget-content-area shadow-none">

				<div class="row">
					<div class="col-12">
						@can('statements.create')
						<div class="text-right">
							<a href="{{ route('statements.create') }}" class="btn btn-primary">Agregar</a>
						</div>
						@endcan

						<div class="table-responsive mb-4 mt-4">
							<table class="table table-hover table-export">
								<thead>
									<tr>
										<th>#</th>
										<th>Nombre</th>
										<th>Empresa</th>
										<th>Tipo</th>
										<th>Estado</th>
										<th>Fecha</th>
										@if(auth()->user()->can('statements.show') || auth()->user()->can('statements.edit') || auth()->user()->can('statements.active') || auth()->user()->can('statements.deactive') || auth()->user()->can('statements.delete'))
										<th>Acciones</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($statements as $statement)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{ $statement->name }}</td>
										<td>{{ $statement['company']->name }}</td>
										<td>{!! type($statement->type) !!}</td>
										<td>{!! state($statement->state) !!}</td>
										<td>{{ $statement->created_at->format('d-m-Y') }}</td>
										@if(auth()->user()->can('statements.show') || auth()->user()->can('statements.edit') || auth()->user()->can('statements.active') || auth()->user()->can('statements.deactive') || auth()->user()->can('statements.delete'))
										<td>
											<div class="btn-group" role="group">
												@can('statements.show')
												<a href="{{ route('statements.show', ['statement' => $statement->slug]) }}" class="btn btn-primary btn-sm bs-tooltip" title="Perfil"><i class="fa fa-user"></i></a>
												@endcan
												@can('statements.edit')
												<a href="{{ route('statements.edit', ['statement' => $statement->slug]) }}" class="btn btn-info btn-sm bs-tooltip" title="Editar"><i class="fa fa-edit"></i></a>
												@endcan
												@if($statement->state=='Activo')
												@can('statements.deactive')
												<button type="button" class="btn btn-warning btn-sm bs-tooltip" title="Desactivar" onclick="deactiveStatement('{{ $statement->slug }}')"><i class="fa fa-power-off"></i></button>
												@endcan
												@else
												@can('statements.active')
												<button type="button" class="btn btn-success btn-sm bs-tooltip" title="Activar" onclick="activeStatement('{{ $statement->slug }}')"><i class="fa fa-check"></i></button>
												@endcan
												@endif
												@can('statements.delete')
												<button type="button" class="btn btn-danger btn-sm bs-tooltip" title="Eliminar" onclick="deleteStatement('{{ $statement->slug }}')"><i class="fa fa-trash"></i></button>
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
	</div>

</div>

@can('statements.deactive')
<div class="modal fade" id="deactiveStatement" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Estás seguro de que quieres desactivar este caso?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-dismiss="modal">Cancelar</button>
				<form action="#" method="POST" id="formDeactiveStatement">
					@csrf
					@method('PUT')
					<button type="submit" class="btn btn-primary">Desactivar</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endcan

@can('statements.active')
<div class="modal fade" id="activeStatement" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Estás seguro de que quieres activar este caso?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-dismiss="modal">Cancelar</button>
				<form action="#" method="POST" id="formActiveStatement">
					@csrf
					@method('PUT')
					<button type="submit" class="btn btn-primary">Activar</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endcan

@can('statements.delete')
<div class="modal fade" id="deleteStatement" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Estás seguro de que quieres eliminar este caso?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-dismiss="modal">Cancelar</button>
				<form action="#" method="POST" id="formDeleteStatement">
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