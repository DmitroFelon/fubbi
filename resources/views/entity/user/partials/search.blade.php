<form method="get" class="input-group">
    <input type="text"
           value="{{(request()->input('s'))?request()->input('s'):''}}"
           name="s"
           data-provide="typeahead"
           data-source='{{$search_suggestions}}'
           placeholder="{{_i('Search user')}}"
           autocomplete="off"
           class="input form-control">
    <span class="input-group-btn">
        <button type="submit" class="btn btn btn-primary">
            <i class="fa fa-search"></i>{{_i('Search')}}
        </button>
    </span>
</form>