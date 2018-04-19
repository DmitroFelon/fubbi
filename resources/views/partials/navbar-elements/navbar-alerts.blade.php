<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
    <i class="fa fa-bell"></i>
    @if($notifications->count())
        <span id="alerts-notifications-count" class="label label-warning">
                {{$notifications->count()}}
        </span>
    @endif
</a>
<ul id="topnav-alerts-list" class="dropdown-menu dropdown-messages">
    @each('partials.navbar-elements.alert-row',
        $notifications->take(5),
         'notification',
         'partials.navbar-elements.alert-row-empty'
    )
    <li>
        <div class="text-center link-block">
            <a href="{{url('notification')}}">
                <strong>{{_i('See All Alerts')}}</strong>
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </li>
</ul>