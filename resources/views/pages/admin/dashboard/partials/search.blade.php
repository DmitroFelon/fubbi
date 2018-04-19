{!! Form::open(['method' => 'get']) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="form-group">
            <label class="control-label" for="customer-select">{{_i('Customer')}}</label>
            <input type="text"
                   value="{{(request()->input('customer'))?request()->input('customer'):''}}"
                   name="customer"
                   id="customer-select"
                   data-provide="typeahead"
                   data-source='{{$search_suggestions}}'
                   placeholder="{{_i('Search user')}}"
                   autocomplete="off"
                   class="input form-control">
            <small class="description">{{_i('Start typing client\'s name or email')}}</small>
        </div>
    </div> {{-- Filter cutomer --}}
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="form-group">
            <label class="control-label" for="date_from">{{_i('From')}}</label>
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
                        id="date_from" name="date_from" type="text" class="form-control"
                        value="{{$date_from}}">
            </div>
        </div>
    </div> {{-- Filter date from --}}
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="form-group">
            <label class="control-label" for="date_to">{{_i('To')}}</label>
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
                        id="date_to" name="date_to" type="text" class="form-control"
                        value="{{$date_to}}">
            </div>
        </div>
    </div> {{-- Filter date to --}}
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="form-group">
            <label class="control-label">&nbsp;</label>
            <div class="input-group date">
                <button class="btn btn-primary" type="submit">{{_i('Search')}}</button>
                <a class="btn btn-default m-l-lg"
                   href="{{action('DashboardController@dashboard')}}">{{_i('Clear')}}</a>
            </div>
        </div>
    </div> {{-- Filter button search --}}
</div>
{!! Form::close() !!}
