<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
    <a href="{{action('Resources\ProjectController@index')}}" id="loading-example-btn"
       class="btn btn-white btn-sm"><i
                class="fa fa-refresh">
        </i> {{_i('Refresh')}}
    </a>
</div>
{{Form::open(['action' => 'Resources\ProjectController@index', 'method' => 'get'])}}
<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    <input type="text"
           value="{{(request()->input('search'))?request()->input('search'):''}}"
           name="search"
           id="customer-select"
           data-provide="typeahead"
           data-source='{{$searchSuggestions}}'
           placeholder="{{_i('Search')}}"
           autocomplete="off"
           class="input form-control">
    <small class="description">{{_i('Start typing client\'s name, email or project name')}}</small>
</div>
<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    {{ Form::select(
           'month',
           $filters['months'],
           request('month'),
           ['class' => 'form-control'])
    }}
</div>
<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    {{ Form::select(
       'status',
       $filters['status'],
       request('status'),
       ['class' => 'form-control'])
    }}
</div>
<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                    <span class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary"> {{_i('Filter')}}</button>
                    </span>
</div>
{{Form::close()}}
