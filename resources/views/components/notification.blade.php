<li class="dropdown notification-dropdown">
    <a class="dropdown-toggle nk-quick-nav-icon notify" data-bs-toggle="dropdown">
        <div class="icon-status {{count($notification) > 0 ? 'icon-status-info': 'icon-status-na'}}">
            <em class="icon ni ni-bell"></em>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end">
        <div class="dropdown-head">
            <span class="sub-title nk-dropdown-title">{{ __('labels.notifications') }}</span>
            <a href="{{auth()->user()->user_type == App\Models\User::TYPE_CLIENT ? route('client.notification.read') : route('admin.notification.read') }}">{{count($notification) > 0 ? 'Mark All as Read': ''}}</a>
        </div>
        <div class="dropdown-body">
            <div class="nk-notification">
                @forelse($notification as $notifications)
                    <a href="{{notificationRoute($notifications->data['type'], $notifications->data['id'])}}" class="nk-notification-item dropdown-inner">
                        <div class="nk-notification-icon">
                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                        </div>
                        <div class="nk-notification-content">
                            <div class="nk-notification-text text-break">{{$notifications->data['title']}}
                            </div>
                            <div class="nk-notification-time">{{$notifications->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                @empty
                    <div class="alert alert-danger alert-icon m-2">
                        <em class="icon ni ni-cross-circle"></em> <strong>{{ __('labels.no_notification_found') }}</strong>!
                    </div>
                @endforelse
            </div><!-- .nk-notification -->
        </div><!-- .nk-dropdown-body -->
        <div class="dropdown-foot center">
            <a href="{{auth()->user()->user_type == App\Models\User::TYPE_CLIENT ? route('client.notification.index') : route('admin.notification.index') }}">{{count($notification) > 0 ? 'View All' :''}}</a>
        </div>
    </div>
</li>
