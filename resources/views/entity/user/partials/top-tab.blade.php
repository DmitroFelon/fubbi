<li class="{{($loop->first) ? 'active' : ''}}">
    <a class="no-paddings" data-toggle="tab"
       href="#tab-{{$role->name}}">
        <i class="fa fa-user"></i> {{$role->display_name}}
        @if( isset($groupedByRoles[$role->name]) and $groupedByRoles[$role->name]->count()>0 )
            <span class="badge badge-primary">
                {{$groupedByRoles[$role->name]->count()}}
            </span>
        @else
            <span class="badge">0</span>
        @endif
    </a>
</li>