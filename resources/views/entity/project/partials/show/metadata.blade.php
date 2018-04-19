<ul>
    @foreach($metadata as $meta)
        @if($meta->value == '' or empty($meta->value) or is_object($meta->value)) @continue @endif
        <li>
            <strong>{{ title_case( str_replace('_', ' ', $meta->key)) }} : </strong>
            @if(is_array($meta->value) and !empty($meta->value))
                <ul>
                    @foreach((array) $meta->value as $sub_key => $sub_value)
                        @if(!$sub_value) @continue @endif
                        <li>

                            {!!
                                (filter_var($sub_value, FILTER_VALIDATE_URL))
                                    ? '<a target="_blank" href="' . $sub_value . '">' . $sub_value . '</a>'
                                    : title_case( str_replace('_', ' ', $sub_value))
                             !!}

                        </li>
                    @endforeach
                </ul>
            @else
                {!!
                    (filter_var($meta->value, FILTER_VALIDATE_URL))
                        ? '<a target="_blank" href="' . $meta->value . '">' . $meta->value . '</a>'
                        : title_case( str_replace('_', ' ', $meta->value))
                !!}
            @endif
        </li>
    @endforeach
</ul>