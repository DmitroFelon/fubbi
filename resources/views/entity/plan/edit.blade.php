@extends('master')

@section('message')

    <div id="add_field_form" hidden>
        <div id="message">
            <div class="form-group text-center">
                <label for="meta_field_name" class="control-label">Meta field name</label>
                <input id="meta_field_name" class="form-control" type="text">
                <input type="radio" name="type" value="text" checked> Text
                <br>
                <input type="radio" name="type" value="radio"> True/False
                <br>
                <button class="btn btn-primary col-md-6" type="button" id="add_field">Add field</button>
                <button class="btn btn-danger col-md-6" type="button" id="close">Close</button>
            </div>
        </div>
    </div>

@endsection

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
                <div id="start" class="row">
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
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: inline-block; float: right;">
                        <button id="add_field_button" type="button" class="btn btn-success" style="float: right;">Add meta field</button>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: inline-block; float: left;">
                        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .input-group-btn .dropdown-toggle {
            display: none;
        }
        #add_field_form {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 900;
        }
        .blur {
            filter: blur(2px);
            -webkit-filter: blur(2px);
            -moz-filter: blur(2px);
            -o-filter: blur(2px);
            -ms-filter: blur(2px);
        }
        #message {
            border: 1px solid rgba(1,1,1,0.3);
            position: absolute;
            top: 30%;
            left: 33%;
            z-index: 1000;
            width: 35%;
            height: 142px;
            background-color: rgb(247,247,247);
            border-radius: 10px;
            -moz-border-radius:10px;
            -webkit-border-radius:10px;
        }
        #message button {
            margin-top: 7px;
            border-radius: 10px;
            -moz-border-radius:10px;
            -webkit-border-radius:10px;
        }
    </style>

    <script>
        $('#add_field_button').click(function() {
            $('#wrapper').addClass('blur');
            $('#add_field_form').show();
        });
        $('#close').click(function() {
            $('#wrapper').removeClass('blur');
            $('#meta_field_name').parent('div').removeClass('has-error');
            $('#add_field_form').hide();
        });
        $('#add_field').click(function() {
            var field_name = $('#meta_field_name').val();
            var type = $('input:checked').val();
            if(field_name != ' ' && field_name != '') {
                if(type == 'text') {
                    $('#start').append(
                        '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">' +
                            '<table class="m-b-md">' +
                                '<tr>' +
                                    '<th>' +
                                        '<label for="' + field_name + '">' + field_name + '</label>' +
                                    '</th>' +
                                    '<td>' +
                                        '<input type="text" name="' + field_name + '" class="form-control">' +
                                    '</td>' +
                                '</tr>' +
                            '</table>' +
                        '</div>'
                    );
                }
                else {
                    $('#start').append(
                        '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">' +
                            '<table class="m-b-md">' +
                                '<tr>' +
                                    '<th>' +
                                        '<label for="' + field_name + '">' + field_name + '</label>' +
                                    '</th>' +
                                    '<td>' +
                                        '<input type="hidden" name="' + field_name + '" value="false">' +
                                        '<div class="i-checks">' +
                                            '<label>' +
                                                '<input type="checkbox" name="' + field_name + '" value="true"> <i></i>' +
                                            '</label>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>' +
                            '</table>' +
                        '</div>'
                    );
                }
                $('#add_field_form').hide();
                $('#wrapper').removeClass('blur');
                $('#meta_field_name').val('').parent('div').removeClass('has-error');
            }
            else {
                $('#meta_field_name').parent('div').addClass('has-error');
                $('#meta_field_name').focus();
            }
        });
    </script>
@endsection