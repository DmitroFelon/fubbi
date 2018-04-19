@extends('master')

@section('before-content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>{{_i('plans')}}</h2>
        </div>
    </div>

@endsection

@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{ucfirst($plan->name)}}</h5>
        </div>
        <div class="ibox-content">
            <div class="project-list">
                {!! Form::open(['action' => ['Resources\PlanController@update', $plan->id], 'method' => 'put']) !!}
                <div class="row">
                    @foreach ($plan->meta->split(2) as $chunk)
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <table class="m-b-md">
                                @foreach ($chunk as $key => $value)
                                    <tr>
                                        <th>
                                            <label for="{{$key}}">
                                                {{ucwords( str_replace('_',' ',$key) )}}
                                            </label>
                                        </th>
                                        <td>
                                            @if($value == 'true' or $value == 'false')
                                                <input type="hidden" name="{{$key}}" value="false">
                                                <div class="i-checks">
                                                    <label>
                                                        <input
                                                                type="checkbox"
                                                                name="{{$key}}"
                                                                value="true"
                                                                {{($value == 'true')?'checked="checked"':''}}> <i></i>
                                                    </label>
                                                </div>
                                            @else
                                                <input class="form-control" id="{{$key}}" name="{{$key}}"
                                                       value="{{$value}}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection