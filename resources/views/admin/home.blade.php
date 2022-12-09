@extends('layouts.admin')

@section('title', 'Inicio')

@section('links')
<link href="{{ asset('/admins/css/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" class="dashboard-analytics" />
@endsection

@section('content')

<div class="row layout-top-spacing">

	<div class="col-lg-6 col-md-8 col-12 layout-spacing mb-3">
		<div class="statbox widget box box-shadow">
			<div class="widget-content widget-content-area">
				<h6 class="font-weight-bold">Bienvenid@, {{ Auth::user()->name.' '.Auth::user()->lastname }}:</h6>
				<p class="font-weight-bold mb-0 mt-3">Administre todo su negocio de forma simple, fácil, comoda y a medida!</p>
			</div>
		</div>
	</div>

	@if(!Auth::user()->hasRole('Cliente'))
	@can('users.index')
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 layout-spacing">
        <div class="widget widget-one_hybrid widget-followers">
            <div class="widget-heading mb-0">
                <div class="w-title mb-0">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <div class="">
                        <p class="w-value">{{ $users }}</p>
                        <h5 class="">Usuarios</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('customers.index')
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 layout-spacing">
        <div class="widget widget-one_hybrid widget-engagement">
            <div class="widget-heading mb-0">
                <div class="w-title mb-0">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div class="">
                        <p class="w-value">{{ $customers }}</p>
                        <h5 class="">Clientes</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('companies.index')
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 layout-spacing">
        <div class="widget widget-one_hybrid widget-referral">
            <div class="widget-heading mb-0">
                <div class="w-title mb-0">
                    <div class="w-icon">
                        <span class="far fa-2x fa-building"></span>
                    </div>
                    <div class="">
                        <p class="w-value">{{ $companies }}</p>
                        <h5 class="">Empresas</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('statements.index')
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 layout-spacing">
        <div class="widget widget-one_hybrid widget-followers">
            <div class="widget-heading mb-0">
                <div class="w-title mb-0">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <div class="">
                        <p class="w-value">{{ $cases }}</p>
                        <h5 class="">Casos</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
    @endif


    @if(Auth::user()->hasRole('Cliente'))
    @can('companies.index')
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 layout-spacing">
        <div class="widget widget-one_hybrid widget-followers">
            <div class="widget-heading mb-0">
                <div class="w-title mb-0">
                    <div class="w-icon">
                        <span class="far fa-2x fa-building"></span>
                    </div>
                    <div class="">
                        <p class="w-value">{{ $companies }}</p>
                        <h5 class="">Empresas</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('statements.index')
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 layout-spacing">
        <div class="widget widget-one_hybrid widget-engagement">
            <div class="widget-heading mb-0">
                <div class="w-title mb-0">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <div class="">
                        <p class="w-value">{{ $cases }}</p>
                        <h5 class="">Casos</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
    @endif
</div>

@endsection