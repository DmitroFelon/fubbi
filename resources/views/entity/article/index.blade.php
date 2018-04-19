@extends('master')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                @if(isset($project))
                    <h5> {{$project->name}} </h5>
                    <div class="ibox-tools">

                        @can('articles.create', $project)
                            <a target="_blank" href="{{url()->action('Project\ArticlesController@create', [$project])}}"
                               class="btn btn-primary btn-xs">{{_i('Create new Article')}}</a>
                        @endcan

                    </div>
                @else
                    <h5>
                        {{_i('All articles')}}
                    </h5>
                @endif
            </div>
            <div class="ibox-content">
                <div class="row m-b-sm m-t-sm">
                    @include('entity.article.partials.search')
                    @if($articles->count() > 0)
                        @include('entity.article.partials.export')
                    @endif
                </div>

                <hr>
                <div class="project-list">
                    <span class="pull-right">{{_i('Count')}} : {!! $articles->total() !!} </span>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="footable-sortable">{{_i('Info')}}</th>
                            <th class="footable-sortable">{{_i('Status')}}</th>
                            <th>{{_i('Type')}}</th>
                            <th class="footable-sortable">{{_i('Rating')}}</th>
                            <th class="">{{_i('Idea')}}</th>
                            <th class="">{{_i('This month')}}</th>
                            <th class="footable-sortable" class=""></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($articles as $article)
                            @include('entity.article.partials.row')
                        @endforeach
                        </tbody>
                    </table>
                    {!! $articles->links() !!}
                </div>

            </div>
        </div>
    </div>
    <a style="display: none" href="#" id="file-loader" frameborder="0"></a>
@endsection


@section('scripts')

    <style>
        .jq-ry-container > .jq-ry-group-wrapper > .jq-ry-group {
            z-index: 0 !important;
        }

        .jq-ry-container > .jq-ry-group-wrapper > .jq-ry-group {
            z-index: 0 !important;
        }
    </style>

    <script>
        $(".ratable").each(function (item) {
            $(this).rateYo({
                rating: $(this).attr('data-rating') + "%",
                precision: 0,
                readOnly: ($(this).attr('data-rating-read')) ? true : false
            });
        });

        $(".dropdown-menu a").on('click', function (e) {
            //e.preventDefault();
            //$('.ibox').children('.ibox-content').toggleClass('sk-loading');
        })

        window.addEventListener('beforeunload', function (event) {
            //$('.ibox').children('.ibox-content').toggleClass('sk-loading');
        });

        window.addEventListener('unload', function (event) {
            //$('.ibox').children('.ibox-content').toggleClass('sk-loading');
        });


    </script>
@endsection