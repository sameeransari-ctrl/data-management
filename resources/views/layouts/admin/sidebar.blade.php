<div class="nk-sidebar nk-sidebar-fixed is-light" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="{{route('admin.dashboard')}}" class="logo-link nk-sidebar-logo">
                <x-logo logoClass="logo-dark-inner" ></x-logo>
                <x-logo logoClass="logo-small" ></x-logo>
            </a>
        </div>
        <div class="nk-menu-trigger me-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>

                <ul class="nk-menu">
                    @can('admin.dashboard.index')
                    <li class="nk-menu-item {{ sidebarRouteCheck('admin.dashboard') }} ">
                        <a href="{{route('admin.dashboard')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.dashboard')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endcan
                    <!-- @can('admin.user.index')
                    <li class="nk-menu-item {{ sidebarRouteCheck('admin.user.*') }} ">
                        <a href="{{route('admin.user.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.user_management')}}</span>
                        </a>
                    </li>
                    @endcan -->
                    @can('admin.role.index')
                    <li class="nk-menu-item {{ sidebarRouteCheck('admin.role.index') }}">
                        <a href="{{route('admin.role.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-list-index-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.role_management')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endcan
                    @can('admin.staff.index')
                    <li class="nk-menu-item {{ sidebarRouteCheck('admin.staff.index') }}" >
                        <a href="{{route('admin.staff.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-user-list-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.staff_management')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endcan
                    @can('admin.data.index')
                    <li class="nk-menu-item {{ sidebarRouteCheck('admin.data.index') }}" >
                        <a href="{{route('admin.data.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-db-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.data_management')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endcan
                    <!-- @can('admin.client.index')
                    <li class="nk-menu-item {{ sidebarRouteCheck('admin.client.*') }}">
                        <a href="{{route('admin.client.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.client_management')}}</span>
                        </a>
                    </li>
                    @endcan -->
                    <!-- <li class="nk-menu-item {{ sidebarRouteCheck('admin.basicudi.index') }} ">
                        <a href="{{route('admin.basicudi.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-tranx-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.basic_udi')}}</span>
                        </a>
                    </li> -->
                    <!-- @can('admin.product.index')
                    <li class="nk-menu-item {{ sidebarRouteCheck('admin.product.*') }}">
                        <a href="{{route('admin.product.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.product')}}</span>
                        </a>
                    </li>
                    @endcan -->
                    <!-- <li class="nk-menu-item {{ sidebarRouteCheck('admin.fsn') }}">
                        <a href="{{route('admin.fsn')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-notice"></em></span>
                            <span class="nk-menu-text">{{__('labels.field_safety_notice')}}</span>
                        </a>
                    </li> -->
                    {{-- <li class="nk-menu-item has-sub ">
                        <a href="#" class="nk-menu-link nk-menu-toggle {{ sidebarRouteCheck() }}">
                            <span class="nk-menu-icon"><em class="icon ni ni-file-text"></em></span>
                            <span class="nk-menu-text">{{__('labels.cms')}}</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item {{ sidebarRouteCheck() }} ">
                                <a href="{{route('admin.cms.edit', 'about-us')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('labels.about_us')}}</span></a>
                            </li>
                            {{-- <li class="nk-menu-item {{ sidebarRouteCheck() }} ">
                                <a href="{{route('admin.cms.edit', 'terms-and-condition')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('labels.terms_n_condition')}} </span></a>
                            </li>
                            <li class="nk-menu-item {{ sidebarRouteCheck() }} ">
                                <a href="{{route('admin.cms.edit', 'privacy-policy')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('labels.privacy_n_policy')}} </span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item --> --}}
                    {{-- <li class="nk-menu-item {{ sidebarRouteCheck('admin.faq.*') }} ">
                        <a href="{{route('admin.faq.index')}}" class="nk-menu-link" >
                            <span class="nk-menu-icon"><em class="icon ni ni-list-round"></em></span>
                            <span class="nk-menu-text">{{__('labels.faqs')}}</span>
                        </a>
                    </li><!-- .nk-menu-item --> --}}
                    @if(auth()->user()->hasRole(App\Models\Role::TYPE_SUPER_ADMIN))
                        <!-- <li class="nk-menu-item {{ sidebarRouteCheck('admin.reports') }}">
                            <a href="{{route('admin.reports')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-report-fill"></em></span>
                                <span class="nk-menu-text">{{ __('labels.reports_&_analytics') }}</span>
                            </a>
                        </li> -->
                        <!-- <li class="nk-menu-item has-sub ">
                            <a href="#" class="nk-menu-link nk-menu-toggle {{ sidebarRouteCheck() }}">
                                <span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
                                <span class="nk-menu-text">{{__('labels.setting')}}</span>
                            </a>
                            <ul class="nk-menu-sub">
                                {{-- <li class="nk-menu-item  {{ sidebarRouteCheck('admin.setting.index') }}">
                                    <a href="{{route('admin.setting.index')}}" class="nk-menu-link " >
                                        <span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
                                        <span class="nk-menu-text">{{__('labels.application_setting')}}</span>
                                    </a>
                                </li> --}}
                                <li class="nk-menu-item  {{ sidebarRouteCheck('admin.setting.general') }}">
                                    <a href="{{route('admin.setting.general')}}" class="nk-menu-link " >
                                        <span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
                                        <span class="nk-menu-text">{{__('labels.general_setting')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                    @endif
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>
