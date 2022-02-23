@extends('layouts.admin')

@section('title', 'Detalles de la Resolución')

@section('links')
<link href="{{ asset('/admins/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="row">
	<div class="col-12 layout-top-spacing">

		<div class="user-profile layout-spacing">
			<div class="widget-content widget-content-area">
				<div class="d-flex justify-content-between">
					<h3 class="pb-3">Datos de la Resolución</h3>
				</div>
				<div class="user-info-list">

					<div class="">
						<ul class="contacts-block list-unstyled mw-100 mx-2">
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Nombre:</b> {{ $resolution->name }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Descripción:</b> {{ $resolution->description }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Cantidad de Archivos:</b> {{ $resolution['files']->count() }}</span>
							</li>
							<li class="contacts-block__item">
								<span class="h6 text-black"><b>Fecha:</b> {{ $resolution->date->format('d-m-Y') }}</span>
							</li>
							<li class="contacts-block__item">
								<a href="{{ route('statements.show', ['statement' => $statement->slug]) }}" class="btn btn-secondary">Volver</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	@if($resolution['files']->count()>0)
	<div class="col-12 layout-top-spacing">

		<div class="user-profile layout-spacing">
			<div class="widget-content widget-content-area">
				<div class="d-flex justify-content-between">
					<h3 class="pb-3">Archivos de la Resolución</h3>
				</div>
				<div class="user-info-list">

					<div class="">
						<ul class="contacts-block list-unstyled mw-100 mx-2">
							@foreach($resolution['files'] as $file)
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