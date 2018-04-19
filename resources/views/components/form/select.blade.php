<div class="form-group" id="{{$name}}-group">
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
    {{ Form::select(
        $name,
        $list,
        $selected,
        array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $select_attributes)) ,
        $options_attributes
    }}
    <div class="text-muted">{{$description}}</div>
</div>