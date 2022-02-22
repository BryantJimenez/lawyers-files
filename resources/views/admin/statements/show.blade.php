@extends('layouts.admin')

@section('title', 'Detalles del Caso')

@section('links')
<link href="{{ asset('/admins/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
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
								<span class="h6 text-black"><b>Cantidad de Archivos:</b> {{ $statement['files']->count() }}</span>
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

	@if($statement['files']->count()>0)
	<div class="col-12 layout-top-spacing">

		<div class="user-profile layout-spacing">
			<div class="widget-content widget-content-area">
				<div class="d-flex justify-content-between">
					<h3 class="pb-3">Archivos del Caso</h3>
				</div>
				<div class="user-info-list">

					<div class="">
						<ul class="contacts-block list-unstyled mw-100 mx-2">
							@foreach($statement['files'] as $file)
							<li class="contacts-block__item">
								<span class="h6 text-black">
									<a href="{{ image_exist('/admins/files/statements/', $file->name) }}" download class="mr-3">{{ $file->name }}</a>
									<a href="{{ image_exist('/admins/files/statements/', $file->name) }}" download class="btn btn-sm btn-secondary px-2 py-1">
										<i class="fa fa-sm fa-download"></i>
									</a>
								</span>
							</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
</div>

@endsection