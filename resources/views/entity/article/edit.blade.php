@extends('master')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>
                    {{_i('Upload article for project: ')}}
                    <a href="{{action('Resources\ProjectController@show', $project)}}">{{$project->name}}</a>
                </h5>
                 <span class="pull-right">
                     @foreach($article->tags as $tag)
                         <span class="label label-primary m-l-xs m-r-xs">{{$tag->name}}</span>
                     @endforeach
                </span>
            </div>
            <div class="ibox-content">
                <div class="ibox-content">
                    <div class="row">
                        @if($article->google_id)
                            @include('entity.article.partials.google-preview')
                        @endif
                    </div>
                    {{ Form::open(['method' => 'POST', 'action' => ['Project\ArticlesController@save_social_posts', $project, $article]]) }}

                    <div class="row">
                        <div class="row m-t-md">
                            <h3 class="text-center">
                                {{_i('Type')}}
                            </h3>
                        </div>
                        {!! Form::bsSelect('type',
                           ['' => 'Select Type', 'Article Outline' => 'Article Outline', 'Article Topic' => 'Article Topic', 'Article' => 'Article', 'Social Posts Texts' => 'Social Posts Texts'],
                           $article->type, _i('Content Type'), null, ['required'])
                        !!}
                    </div>
                    <div class="row">
                        <div class="row m-t-md">
                            <h3 class="text-center">
                                {{_i('Tags')}}
                            </h3>
                        </div>
                        {!! Form::bsText('tags', implode(',', $article->tags->pluck('name')->toArray()),_i("Tags"),_i("Separate by coma or click 'enter'."), ['required', 'class'=> 'tagsinput' ]) !!}
                    </div>
                    <div class="row">
                        <div class="row m-t-md">
                            <h3 class="text-center">
                                {{_i('Social posts')}}
                            </h3>
                        </div>
                        @for($i = 1; $i <= 5; $i ++)
                            {!! Form::bsText('socialposts[]', (isset($article->getMeta('socialposts')[$i-1]))?$article->getMeta('socialposts')[$i-1]:'', _i("Social Post text %d", [$i]), null, ['rows' => '3'],'textarea') !!}
                        @endfor

                        {{ Form::submit(_i('Save'), ['class' => 'btn btn-primary']) }}


                    </div>
                    {{ Form::close()  }}
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

    </script>

@endsection