@extends('master')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <a href="{{action('Resources\ProjectController@show', $project)}}">
                    <h5>
                        {{_i('Upload article for project: ')}}
                        {{$project->name}}
                    </h5>
                </a>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                {{Form::open(['id' => 'article-form', 'enctype'=>"multipart/form-data",  'role'=>'form', 'multipart', 'route'=>['project.articles.store', $project->id] ])}}
                <div class="bg-muted p-sm">
                    @include('entity.article.partials.checklist')
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-lg-offset-1 col-md-offset-1 m-t-md">
                        <h3 class="text-center">
                            {{_i('Content')}}
                        </h3>
                        {!! Form::bsSelect('type', $filters['types'], '', _i('Content Type'), null, ['required']) !!}

                        <div class="row">
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                {!! Form::bsSelect('idea_id', $filters['ideas'], '', _i('Associate with idea'), null, ['id' => 'ideas-select', 'required']) !!}
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <div id="idea-details" class="description p-h-md"></div>
                            </div>
                        </div>

                        {!! Form::bsText('file', null, _i('Article File'), _i('Upload file'), [], 'file') !!}
                        {!! Form::bsText('copyscape', null, _i('Copyscape Screenshot'), _i('Upload Copyscape Screenshot'), [], 'file') !!}
                        {{Form::submit('Upload article', ['class' => 'btn btn-primary'])}}
                    </div>
                </div>


            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type == "text")) {
                return false;
            }
        }
        document.onkeypress = stopRKey;

        $('#ideas-select').on('change', function () {
            var val = $(this).val();
            if (!val) {
                $("#idea-details").html('');
                return;
            }
            $("#idea-details").html(
                    '<a class="m-md" target="_blank" href="/ideas/' + val + '">' +
                    '<strong>{{_i('Check Idea')}}</strong>' +
                    '</a>'
            );
        });
    </script>
@endsection