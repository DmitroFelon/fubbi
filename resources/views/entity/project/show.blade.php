@extends('master')

@section('before-content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>{{_i('Project details')}}</h2>
        </div>
    </div>
    @if(!$project->hasWorker() and \Illuminate\Support\Facades\Auth::user()->hasInvitetoProject($project->id) )
        @include('entity.project.partials.form.invite')
    @endif
@endsection

@section('content')


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> {{-- Main --}}

        <div class="col col-lg-12 col-xs-12 ">
            @component('components.ibox')
            @slot('title') {{_i('Project: ')}} {{ $project->name }} @endslot
            @slot('tools')
            <a href="{{ route('project.plan.edit', ['project' => $project->id, 'plan' => $project->subscription->stripe_id]) }}" class="btn btn-success btn-xs btn-xs m-r-sm p-w-sm">Update plan requirements</a>
            @include('entity.project.partials.show.tools')
            @endslot
            <h3 class="text-center">{{_i('Summary')}}</h3>
            @include('entity.project.partials.show.head-info')
            @endcomponent
        </div> {{-- Head --}}

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @component('components.ibox')
            @slot('title') Progress @endslot

            @include('entity.project.partials.show.head-progress')
            @endcomponent
        </div> {{-- Progress --}}

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @component('components.ibox')
            @slot('title') Quiz result @endslot
            @slot('tools')
            @can('project.update', $project)
                @if(in_array($project->state, [\App\Models\Helpers\ProjectStates::QUIZ_FILLING]))
                    <a href="{{action('Resources\ProjectController@edit', [$project, 's' => \App\Models\Helpers\ProjectStates::QUIZ_FILLING])}}"
                       class="btn btn-primary btn-xs m-r-sm p-w-sm">
                        {{($project->state == \App\Models\Helpers\ProjectStates::QUIZ_FILLING) ? 'Complete' : 'Edit'}}
                    </a>
                @endif
            @endcan

            <a class="collapse-link">
                <i class="fa fa-chevron-down"></i>
            </a>
            @endslot
            @include('entity.project.partials.show.metadata')
            @endcomponent
        </div> {{-- Quiz result --}}

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @component('components.ibox')
            @slot('title') Ideas ({{ $projectData['ideasCompleted'] . '/' . $projectData['ideasQuantity'] }} completed) @endslot
            @slot('tools')

            <a class="collapse-link">
                <i class="fa fa-chevron-down"></i>
            </a>
            @endslot
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <h3>{{_('Themes')}}</h3>

                    <ul>
                        @if($projectData['themes']->isEmpty())
                            <div class="text-muted">
                                {{_i('Empty')}}
                            </div>
                        @endif
                        @foreach($projectData['themes'] as $theme)
                                <li>
                                <a target="_blank" href="{{action('IdeaController@show', $theme)}}" style="{{ $theme->completed ? '' : 'color:red'}}">
                                    {{ $theme->theme }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <h3>{{_('Questions')}}</h3>
                    <ul>
                        @if($projectData['questions']->isEmpty())
                            <div class="text-muted">
                                {{_i('Empty')}}
                            </div>
                        @endif
                        @foreach($projectData['questions'] as $question)
                                <li>
                                <a target="_blank" href="{{action('IdeaController@show', $question)}}" style="{{ $question->completed ? '' : 'color:red'}}">
                                    {{ $question->theme }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <h3>{{_('Ideas') }}</h3>
                    <ul>
                        @if($projectData['inspirations']->isEmpty())
                            <div class="text-muted">
                                {{_i('Empty')}}
                            </div>
                        @endif
                        @foreach($projectData['inspirations'] as $inspiration)
                            <li>
                                <a target="_blank"
                                   href="{{action('Resources\InspirationController@show', $inspiration)}}">
                                    Idea: {{$inspiration->id}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endcomponent
        </div> {{-- Ideas --}}

        <div class="col col-lg-12 col-xs-12">
            @component('components.ibox')
            @slot('title') Media files @endslot
            @slot('hide') @endslot
            @include('entity.project.partials.show.media')
            @endcomponent
        </div> {{-- Media --}}

        @can('project.invite', $project)
            @if($project->requireWorkers())
                <div class="col col-lg-6 col-xs-12">
                    @component('components.ibox')
                    @slot('title') Attach workers @endslot
                    @slot('hide')@endslot
                    @include('entity.project.partials.show.invite-workers')
                    @endcomponent
                </div>
            @endif

            @if($project->workers->isEmpty() and $project->requireWorkers())
                <div class="col col-lg-6 col-xs-12">
                    @component('components.ibox')
                    @slot('title') Attach team @endslot
                    @slot('hide')@endslot
                    @include('entity.project.partials.show.invite-team')
                    @endcomponent
                </div>
            @endif
        @endcan


    </div>

@endsection
