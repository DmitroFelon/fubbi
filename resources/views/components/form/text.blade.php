<div class="form-group" id="{!! isset($attributes['id']) ? $attributes['id'] : $name !!}-group">
    @if($label)
        {!!Form::label($name, $label, ['class' => 'control-label'])!!}
    @endif

    @switch($type)
        @case('url')
            {!!Form::url($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes))!!}
            @break
        @case('number')
            {!!Form::number($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes))!!}
            @break
        @case('email')
            {!!Form::email($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes))!!}
            @break
        @case('date')
            {!!Form::date($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes))!!}
            @break
        @case('datetime')
            {!!Form::datetime($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes))!!}
            @break
        @case('password')
            {!!Form::password($name, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes))!!}
            @break
        @case('file')
            {!!Form::file($name, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes))!!}
            @break
        @case('textarea')
            {!!Form::textarea($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes))!!}
            @break
        @default
            {!!Form::text($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes))!!}
    @endswitch

    @if($description)
            <div class="text-muted">{!! $description !!}</div>
    @endif
</div>

