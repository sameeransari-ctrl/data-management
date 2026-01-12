<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ms-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
                        class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="{{route('client.dashboard')}}" class="logo-link">
                    <x-logo logoClass="logo-dark-inner" ></x-logo>
                    <x-logo logoClass="logo-small" ></x-logo>
                </a>
            </div><!-- .nk-header-brand -->
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <x-notification notification="$notification"></x-notification>
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle me-n1" data-bs-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <img src="{{ getLoggedInUserDetail()->profile_image_url ?? asset_path('assets/images/default-user.jpg') }}" class="img-fluid h-100 object-fit-cover" alt="{{getLoggedInUserDetail()->name}}">
                                </div>
                                <div class="user-info d-none d-xl-block">
                                    <div class="user-name dropdown-indicator">{{ucwords(getLoggedInUserDetail()->name)}}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <img src="{{ getLoggedInUserDetail()->profile_image_url ?? asset_path('assets/images/default-user.jpg') }}" class="img-fluid h-100 object-fit-cover" alt="{{getLoggedInUserDetail()->name}}">
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ucwords(getLoggedInUserDetail()->name)}}</span>
                                        <span class="sub-text">{{getLoggedInUserDetail()->email}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li>
                                        <a href="{{route('client.profile.index')}}"><em class="icon ni ni-user-alt"></em><span>{{ __('labels.profile') }}</span></a>
                                    </li>
                                    <li>
                                        <a href="{{route('client.change-user-password')}}"><em class="icon ni ni-lock-alt"></em><span>{{ __('labels.change_password') }}</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{route('client.logout')}}"><em class="icon ni ni-signout"></em><span>{{ __('labels.sign_out') }}</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
