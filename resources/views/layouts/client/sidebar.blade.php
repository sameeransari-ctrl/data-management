<div class="nk-sidebar nk-sidebar-fixed is-light" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="{{route('client.dashboard')}}" class="logo-link nk-sidebar-logo">
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
                    <li class="nk-menu-item {{ sidebarRouteCheck('client.dashboard') }} ">
                        <a href="{{route('client.dashboard')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.dashboard')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item {{ sidebarRouteCheck('client.user.*') }} ">
                        <a href="{{route('client.user.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.user_management')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item {{ sidebarRouteCheck('client.basicudi.index') }} ">
                        <a href="{{route('client.basicudi.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-tranx-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.basic_udi')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item {{ sidebarRouteCheck('client.product.*') }}">
                        <a href="{{route('client.product.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                            <span class="nk-menu-text">{{__('labels.product_management')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item {{ sidebarRouteCheck('client.fsn.index') }} ">
                        <a href="{{route('client.fsn.index')}}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-notice"></em></span>
                            <span class="nk-menu-text">{{__('labels.field_safety_notice')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>
