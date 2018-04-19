<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

<center>
    <h1>Project: {{$project->name}}</h1>
</center>
<h4>Client: {{$project->client->name}}</h4>
<h4>Plan: {{title_case(str_replace('-',' ',$project->subscription->stripe_plan))}} </h4>
<p>
    Link: <a href="{{action('Resources\ProjectController@show', $project)}}">Click</a>
</p>

<center>
    <h2>Plan requirments</h2>
</center>
<table class="table table-bordered">
    @foreach($project->services as $service)
        <tr>
            <td>
                {{ucwords( str_replace('_',' ',$service->display_name) )}}:
            </td>
            <td>
                {{$service->print_value}}
            </td>
        </tr>
    @endforeach
</table>

<hr>

<div class="page-break"></div>

<center>
    <h2>Quiz result</h2>
</center>
@foreach($meta as $key => $value)
    <div>
        @if(is_array($value))
            <b>{{$key}} : </b> <br>
            @foreach($value as $subkey => $subvalue)
                @if($subvalue)
                    <span style="text-indent:25px;">
                        - {!!  (is_int($subkey)) ? $subvalue : $subkey . ' : ' . $subvalue !!}
                    </span> <br>
                @endif
            @endforeach
        @else
            @if($value)
                <b> {{$key}} : </b> {{$value}}
            @endif
        @endif
    </div>
    <br>
@endforeach
<div class="page-break"></div>

<center>
    <h2>Ideas</h2>
</center>

@foreach($project->ideas()->themes()->get() as $idea)
    <div>
        <b>{{ucfirst($idea->theme)}} : </b> <br>
        <ul>
            @if($idea->keywords()->accepted()->get()->isEmpty())
                <li>-</li>
            @else
                @foreach($idea->keywords()->accepted()->get() as $keyword)
                    <li>{{$keyword->text}}</li>
                @endforeach
            @endif
        </ul>
        <br>
        <h3> Info: </h3>
        <ul>
            <li><b> Points covered : </b> <br> {{$idea->points_covered}} </li>
            <li><b> Points avoid : </b> <br> {{$idea->points_avoid}} </li>
            <li><b> References : </b> <br> {{$idea->references}} </li>
            <li><b> This month : </b> <br> {{ ($idea->this_month) ? 'Yes' : 'No' }} </li>
            <li><b> Next month : </b> <br> {{ ($idea->next_month) ? 'Yes' : 'No' }} </li>
        </ul>
    </div>
    <br>
    <hr>
@endforeach
<div class="page-break"></div>

<center>
    <h2>Questions</h2>
</center>
@foreach($project->ideas()->questions()->get() as $idea)
    <div>
        <b>{{ucfirst($idea->theme)}} : </b> <br>
        <ul>
            @if($idea->keywords()->accepted()->get()->isEmpty())
                <li>-</li>
            @else
                @foreach($idea->keywords()->accepted()->get() as $keyword)
                    <li>{{$keyword->text}}</li>
                @endforeach
            @endif
        </ul>
        <br>
        <h3> Info: </h3>
        <ul>
            <li><b> Points covered : </b> <br> {{$idea->points_covered}} </li>
            <li><b> Points avoid : </b> <br> {{$idea->points_avoid}} </li>
            <li><b> References : </b> <br> {{$idea->references}} </li>
            <li><b> This month : </b> <br> {{ ($idea->this_month) ? 'Yes' : 'No' }} </li>
            <li><b> Next month : </b> <br> {{ ($idea->next_month) ? 'Yes' : 'No' }} </li>
        </ul>

    </div>
    <br>
    <hr>
@endforeach
<div class="page-break"></div>

</body>
</html>