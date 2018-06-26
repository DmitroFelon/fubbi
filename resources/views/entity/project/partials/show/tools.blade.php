@if(!in_array($project->state, [\App\Models\Helpers\ProjectStates::QUIZ_FILLING]))
    <a style="width:15em;" href="{{ route('export.project', $project) }}"
       class="btn btn-white yellow-bg btn-xs btn-xs m-r-sm p-w-sm">
        <i class="fa fa-download"></i> {{_i('Download Requirements')}}
    </a>
@endif

@can('project.accept-review', $project)
    @if($project->isOnReview())
        <a href="{{ action('Resources\ProjectController@acceptReview', $project) }}"
           class="btn btn-primary btn-xs btn-xs m-r-sm p-w-sm">
            {{_i('Accept review')}}
        </a>
        <a href="{{action('Resources\ProjectController@rejectReview', $project)}}"
           class="btn btn-danger btn-xs btn-xs m-r-sm p-w-sm">
            {{_i('Reject review')}}
        </a>
    @endif()

    @if($project->isManager(Auth::user()))
        @if(!in_array($project->state, [\App\Models\Helpers\ProjectStates::QUIZ_FILLING]))
            <a href="{{action('Resources\ProjectController@allow_modifications', $project)}}"
               class="btn btn-primary btn-xs btn-xs m-r-sm p-w-sm">
                {{_i('Allow modifications')}}
            </a>
        @endif
    @endif

@endcan

@role([\App\Models\Role::ADMIN, \App\Models\Role::ACCOUNT_MANAGER, \App\Models\Role::CLIENT])
@can('project.update', $project)
    @if(in_array($project->state, [\App\Models\Helpers\ProjectStates::QUIZ_FILLING]))
        <a href="{{action('Resources\ProjectController@edit', $project)}}"
           class="btn btn-primary btn-xs btn-xs m-r-sm p-w-sm">
            {{_i('Complete Quiz')}}
        </a>
    @endif
@endcan
@endrole

@if(!in_array($project->state, [\App\Models\Helpers\ProjectStates::QUIZ_FILLING]))
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
