<div class="row">
    <div class="col-lg-12">
        <dl class="dl-horizontal">
            <dt>{{_i('Completed')}}:</dt>
            <dd>
                <div class="progress progress-striped active m-b-sm">
                    <div style="width: {{$project->getProgress()}}%;" class="progress-bar">
                        <span>{{$project->getProgress()}}%</span>
                    </div>
                </div>
            </dd>
        </dl>
    </div>
</div>

<hr>


<h3 class="m-b-lg">{{_i('Total articles:')}}</h3>

<div class="row">
    <div class="col-lg-4">
        <dl class="dl-horizontal">
            <dt>{{_i('Articles')}}:</dt>
            <dd>
                {{$project->articles->count()}} -
                <a class="btn btn-xs btn-primary"
                   href="{{action('Project\ArticlesController@index', [$project])}}">{{_i('Review')}}</a>
            </dd>
        </dl>
    </div>
    <div class="col-lg-4">
        <dl class="dl-horizontal">
            <dt>{{_i('Accepted')}}:</dt>
            <dd>
                {{$project->articles()->accepted()->count()}} - <a class="btn btn-xs btn-primary"
                                                                   href="{{action('Project\ArticlesController@index', [$project, 'status' => '1'])}}">{{_i('Review')}}</a>
            </dd>
        </dl>
    </div>
</div>

<hr>

<h3 class="m-b-lg">{{_i('Articles by type:')}}</h3>

@foreach($project->services()->withType(\App\Models\Project\Service::TYPE_INTEGER)->required()->get() as $service)
    <div class="row">
        <div class="col-lg-4">
            <dl class="dl-horizontal">
                <dt>{{$service->display_name}}:</dt>
                <dd>
                    {{$project->getArticleByType($service->name)->count()}} - <a class="btn btn-xs btn-primary"
                                                                           href="{{action('Project\ArticlesController@index', [$project, 'type' => $service->name])}}">{{_i('Review')}}</a>
                </dd>
            </dl>
        </div>
        <div class="col-lg-4">
            <dl class="dl-horizontal">
                <dt>{{_i('Accepted')}}:</dt>
                <dd>
                    {{$project->getArticleByType($service->name)->accepted()->count()}} - <a
                            class="btn btn-xs btn-primary"
                            href="{{action('Project\ArticlesController@index', [$project, 'type' => $service->name, 'status' => '1'])}}">{{_i('Review')}}</a>
                </dd>
            </dl>
        </div>
    </div>
@endforeach
