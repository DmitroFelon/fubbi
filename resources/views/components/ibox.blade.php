<div class="ibox">
    <div class="ibox-title">
        <h5>{{_i($title)}}</h5>
        <div class="ibox-tools">
            @if(isset($tools))
                {!! $tools !!}
            @else
                <a class="collapse-link">
                    <i class="fa fa-chevron-down"></i>
                </a>
            @endif
        </div>
    </div>
    <div class="ibox-content" style="display: {{ (isset($hide)) ? 'none' : 'block' }}">
        {!! $slot !!}
    </div>
</div>