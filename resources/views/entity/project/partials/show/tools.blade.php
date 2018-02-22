@if(!in_array($project->state, [\App\Models\Helpers\ProjectStates::QUIZ_FILLING, \App\Models\Helpers\ProjectStates::KEYWORDS_FILLING]))
    <a href="{{action('Resources\ProjectController@export', $project)}}"
       class="btn btn-white yellow-bg btn-xs btn-xs m-r-sm p-w-sm">
        <i class="fa fa-download"></i> {{_i('Export')}}
    </a>
@endif

@can('project.accept-review', $project)
    @if($project->isOnReview())
        <a href="{{ action('Resources\ProjectController@accept_review', $project) }}"
           class="btn btn-primary btn-xs btn-xs m-r-sm p-w-sm">
            {{_i('Accept review')}}
        </a>
        <a href="{{action('Resources\ProjectController@reject_review', $project)}}"
           class="btn btn-danger btn-xs btn-xs m-r-sm p-w-sm">
            {{_i('Reject review')}}
        </a>
    @endif()


    @if($project->isManager(Auth::user()))
        @if(!in_array($project->state, [\App\Models\Helpers\ProjectStates::QUIZ_FILLING, \App\Models\Helpers\ProjectStates::KEYWORDS_FILLING]))
            <a href="{{action('Resources\ProjectController@allow_modifications', $project)}}"
               class="btn btn-primary btn-xs btn-xs m-r-sm p-w-sm">
                {{_i('Allow modifications')}}
            </a>
        @endif
    @endif

@endcan

@role([\App\Models\Role::ADMIN, \App\Models\Role::ACCOUNT_MANAGER, \App\Models\Role::CLIENT])
@can('project.update', $project)
    <a href="{{action('Resources\ProjectController@edit', $project)}}"
       class="btn btn-primary btn-xs btn-xs m-r-sm p-w-sm">
        {{_i('Edit project')}}
    </a>
@endcan
@endrole

@if(!in_array($project->state, [\App\Models\Helpers\ProjectStates::QUIZ_FILLING, \App\Models\Helpers\ProjectStates::KEYWORDS_FILLING]))
    @can('articles.create', $project)
        <a href="{{action('Project\ArticlesController@create', $project)}}"
           class="btn btn-success btn-xs btn-xs m-r-sm p-w-sm">
            {{_i('Add article')}}
        </a>
    @endcan
@endif
<a class="collapse-link">
    <i class="fa fa-chevron-up"></i>
</a>