<div class="ibox-title">
    <h5>Select Keywords</h5>
</div>
<div class="ibox-content">
    {!! Form::model($project,
    ['files' => true, 'method' => 'PUT', 'role'=>'form', 'id' => 'keywords-form', 'action' => ['Resources\ProjectController@update', $project]])
    !!}
    {!! Form::hidden('_step', \App\Models\Helpers\ProjectStates::KEYWORDS_FILLING) !!}
    {!! Form::hidden('_project_id', $project->id)  !!}

    {{--Themes' keywords tabs--}}
    @foreach($project->ideas as $idea)
        <h1>{{$idea->theme}} </h1>
        <fieldset data-theme="{{$idea->theme}}" data-mode="async"
                  data-url="{{url()->action('KeywordsController@index', [$project, $idea])}}">
            {{--loaded by ajax--}}
        </fieldset>
    @endforeach
    {!! Form::close() !!}
</div>
