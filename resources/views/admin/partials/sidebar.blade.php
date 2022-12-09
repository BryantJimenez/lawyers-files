<div class="sidebar-wrapper sidebar-theme">
    
    <nav id="compactSidebar" @if(!is_null($setting->menu_background_color) && !empty($setting->menu_background_color) && !is_null($setting->menu_border_color) && !empty($setting->menu_border_color)) style="background-color: {{ $setting->menu_background_color }}!important; border-right: 1px solid {{ $setting->menu_border_color }}!important;" @elseif(!is_null($setting->menu_background_color) && !empty($setting->menu_background_color) && (is_null($setting->menu_border_color) || empty($setting->menu_border_color))) style="background-color: {{ $setting->menu_background_color }}!important;" @elseif((is_null($setting->menu_background_color) || empty($setting->menu_background_color)) && !is_null($setting->menu_border_color) && !empty($setting->menu_border_color)) style="border-right: 1px solid {{ $setting->menu_border_color }}!important;" @endif>
        <ul class="navbar-nav theme-brand flex-row">
            <li class="nav-item theme-logo">
                <a href="{{ route('admin') }}">
                    <img src="{{ asset('/admins/img/'.$setting->logo) }}" class="navbar-logo" alt="logo" title="logo">
                </a>
            </li>
        </ul>
        <ul class="menu-categories">
            <li class="menu menu-single {{ active(['admin', 'admin/perfil', 'admin/perfil/editar']) }}">
                <a href="{{ route('admin') }}" data-active="{{ menu_expanded(['admin', 'admin/perfil', 'admin/perfil/editar']) }}" class="menu-toggle" @if(menu_expanded(['admin', 'admin/perfil', 'admin/perfil/editar'])=='true') style="background-color: {{ $setting->menu_background_color_hover }}!important;" @endif>
                    <div class="base-menu">
                        <div class="base-icons" @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif>
                            <svg @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        </div>
                        <span @if(!is_null($setting->menu_text_color) && !empty($setting->menu_text_color)) style="color: {{ $setting->menu_text_color }}!important;" @endif>Inicio</span>
                    </div>
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </li>

            @can('statements.index')
            <li class="menu menu-single {{ active('admin/casos', 0) }}">
                <a href="{{ route('statements.index') }}" data-active="{{ menu_expanded('admin/casos', 0) }}" class="menu-toggle" @if(menu_expanded('admin/casos', 0)=='true') style="background-color: {{ $setting->menu_background_color_hover }}!important;" @endif>
                    <div class="base-menu">
                        <div class="base-icons" @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif>
                            <svg  @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        </div>
                        <span @if(!is_null($setting->menu_text_color) && !empty($setting->menu_text_color)) style="color: {{ $setting->menu_text_color }}!important;" @endif>Casos</span>
                    </div>
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </li>
            @endcan

            @can('customers.index')
            <li class="menu menu-single {{ active('admin/clientes', 0) }}">
                <a href="{{ route('customers.index') }}" data-active="{{ menu_expanded('admin/clientes', 0) }}" class="menu-toggle" @if(menu_expanded('admin/clientes', 0)=='true') style="background-color: {{ $setting->menu_background_color_hover }}!important;" @endif>
                    <div class="base-menu">
                        <div class="base-icons" @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif>
                            <svg @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <span @if(!is_null($setting->menu_text_color) && !empty($setting->menu_text_color)) style="color: {{ $setting->menu_text_color }}!important;" @endif>Clientes</span>
                    </div>
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </li>
            @endcan

            @can('companies.index')
            <li class="menu menu-single {{ active('admin/empresas', 0) }}">
                <a href="{{ route('companies.index') }}" data-active="{{ menu_expanded('admin/empresas', 0) }}" class="menu-toggle" @if(menu_expanded('admin/empresas', 0)=='true') style="background-color: {{ $setting->menu_background_color_hover }}!important;" @endif>
                    <div class="base-menu">
                        <div class="base-icons" @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif>
                            <i class="far fa-2x fa-building" @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif></i>
                        </div>
                        <span @if(!is_null($setting->menu_text_color) && !empty($setting->menu_text_color)) style="color: {{ $setting->menu_text_color }}!important;" @endif>Empresas</span>
                    </div>
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </li>
            @endcan

            @can('users.index')
            <li class="menu menu-single {{ active('admin/usuarios', 0) }}">
                <a href="{{ route('users.index') }}" data-active="{{ menu_expanded('admin/usuarios', 0) }}" class="menu-toggle" @if(menu_expanded('admin/usuarios', 0)=='true') style="background-color: {{ $setting->menu_background_color_hover }}!important;" @endif>
                    <div class="base-menu">
                        <div class="base-icons" @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif>
                            <svg @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <span @if(!is_null($setting->menu_text_color) && !empty($setting->menu_text_color)) style="color: {{ $setting->menu_text_color }}!important;" @endif>Usuarios</span>
                    </div>
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </li>
            @endcan

            @canany(['types.index', 'settings.edit'])
            <li class="menu {{ active(['admin/tipos', 'admin/ajustes'], 0) }}">
                <a href="#settings" data-active="{{ menu_expanded(['admin/tipos', 'admin/ajustes'], 0) }}" class="menu-toggle" @if(menu_expanded(['admin/tipos', 'admin/ajustes'], 0)=='true') style="background-color: {{ $setting->menu_background_color_hover }}!important;" @endif>
                    <div class="base-menu">
                        <div class="base-icons" @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif>
                            <svg @if(!is_null($setting->menu_icon_color) && !empty($setting->menu_icon_color)) style="color: {{ $setting->menu_icon_color }}!important;" @endif xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        </div>
                        <span @if(!is_null($setting->menu_text_color) && !empty($setting->menu_text_color)) style="color: {{ $setting->menu_text_color }}!important;" @endif>Ajustes</span>
                    </div>
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </li>
            @endcanany
        </ul>
    </nav>

    <div id="compact_submenuSidebar" class="submenu-sidebar">
        @canany(['types.index', 'settings.edit'])
        <div class="submenu {{ submenu(['admin/tipos', 'admin/ajustes'], 0) }}" id="settings">
            <ul class="submenu-list" data-parent-element="#settings">
                @can('settings.edit')
                <li class="{{ active('admin/ajustes', 0) }}">
                    <a href="{{ route('settings.edit') }}"> Ajustes Generales</a>
                </li>
                @endcan

                @can('types.index')
                <li class="{{ active('admin/tipos', 0) }}">
                    <a href="{{ route('types.index') }}"> Tipos de Casos</a>
                </li>
                @endcan
            </ul>
        </div>
        @endcanany
    </div>

</div>