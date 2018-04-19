<div class="i-checks">
    <label>
        <input
                {{(Auth::user()->disabled_notifications()->name($name)->get()->isNotEmpty())?'checked':''}}
                class="keywords-checkbox"
                type="checkbox"
                name="disabled_notifications[{{$name}}]"
                value="true"> <i></i>
        {{$label}}
    </label>
</div>
