

@if(isset($project))
    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
        <a href="{{action('Project\ArticlesController@index', $project)}}"
           id="loading-example-btn"
           class="m-t-xxs btn btn-white btn-sm"><i
                    class="fa fa-refresh">
            </i> {{_i('Refresh')}}
        </a>
    </div>
    {{Form::open(['action' => ['Project\ArticlesController@index', $project], 'method' => 'get'])}}
@else
    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
        <a href="{{action('Resources\ArticlesController@index')}}" id="loading-example-btn"
           class="m-t-xxs  btn btn-white btn-sm"><i
                    class="fa fa-refresh">
            </i> {{_i('Refresh')}}
        </a>
    </div>
    {{Form::open(['action' => ['Resources\ArticlesController@index'], 'method' => 'get'])}}
@endif
<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    {{ Form::select(
           'type',
           $filters['types'],
           request('type'),
           ['class' => 'form-control'])
       }}
</div>
<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    {{ Form::select(
           'status',
           $filters['statuses'],
           request('status'),
           ['class' => 'form-control'])
       }}
</div>
<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
    <div class="i-checks">
        <label class="m-t-xs">
            <input class="keywords-checkbox"
                   type="checkbox"
                   {{(request('active') ? 'checked=checked' : '')}}
                   value="1"
                   name="active"> <i></i>
            {{_i('This month')}}
        </label>
    </div>
</div>
<div class="m-t-xxs col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <span class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary"> {{_i('Filter')}}</button>
                    </span>
</div>
{{Form::close()}}