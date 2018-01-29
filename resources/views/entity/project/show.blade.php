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


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9"> {{-- Main --}}

        <div class="col col-lg-12 col-xs-12 ">
            @component('components.ibox')
            @slot('title') {{ $project->name }} @endslot
            @slot('tools')
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
            <a href="{{url()->action('ProjectController@edit', ['id' => $project->id, 's' => \App\Models\Helpers\ProjectStates::QUIZ_FILLING])}}"
               class="btn btn-primary btn-xs m-r-sm p-w-sm">
                {{_i('Edit')}}
            </a>
            <a class="collapse-link">
                <i class="fa fa-chevron-down"></i>
            </a>
            @endslot
            @include('entity.project.partials.show.metadata')
            @endcomponent
        </div> {{-- Quiz result --}}

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @component('components.ibox')
            @slot('title') Ideas @endslot
            @slot('tools')
            <a href="{{url()->action('ProjectController@edit', ['id' => $project->id, 's' => \App\Models\Helpers\ProjectStates::KEYWORDS_FILLING])}}"
               class="btn btn-primary btn-xs m-r-sm p-w-sm">
                {{_i('Edit')}}
            </a>
            <a class="collapse-link">
                <i class="fa fa-chevron-down"></i>
            </a>
            @endslot
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <h3>{{_('Themes')}}</h3>
                    <ul>
                        @foreach($project->ideas()->themes()->get() as $idea)
                            <li>
                                <a target="_blank" href="{{action('IdeaController@show', $idea)}}">
                                    {{$idea->theme}}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <h3>{{_('Questions')}}</h3>
                    <ul>
                        @foreach($project->ideas()->questions()->get() as $idea)
                            <li>
                                <a target="_blank" href="{{action('IdeaController@show', $idea)}}">
                                    {{$idea->theme}}
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

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
        @component('components.ibox')
        @slot('title') Activity @endslot
        @include('entity.project.partials.activity')
        @endcomponent
    </div> {{-- Activity --}}

@endsection