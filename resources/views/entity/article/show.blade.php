@extends('master')

@section('content')

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="message"></div>
        <div class="ibox">
            <div class="ibox-title">
                <h5>
                    {{$article->title}}
                </h5>
                @can('articles.accept', [$project, $article])
                    <div class="ibox-tools">
                        <a href="{{url()->action('Project\ArticlesController@accept', [$project, $article])}}"
                           class="btn btn-primary btn-xs">{{_i('Accept article')}}</a>
                        <a href="{{url()->action('Project\ArticlesController@decline', [$project, $article])}}"
                           class="btn btn-danger btn-xs">{{_i('Decline Article')}}</a>
                    </div>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2 p-xs">
                        @if($article->accepted === 1)
                            <strong>{{_i('Status')}} : {{_i('accepted')}} </strong>
                        @elseif($article->accepted === 0)
                            <strong>{{_i('Status')}} : {{_i('declined')}} </strong>
                        @else
                            <strong>{{_i('Status')}} : {{_i('new')}} </strong>
                        @endif
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        @can('articles.accept', [$project, $article])
                            <div data-rating="{{$article->ratingPercent}}" class="ratable">
                            </div>
                        @endcan
                        @cannot('articles.accept', [$project, $article])
                            <span data-rateyo-read-only="true" data-rating="{{$article->ratingPercent}}"
                                  class="ratable">
                            </span>
                        @endcannot
                    </div>
                </div>
                <hr>
                <div class="row">
                    @include('entity.article.partials.google-preview')
                </div>

                @if($article->hasMedia('copyscape'))
                    <div class="row">
                        <h3 class="text-center">
                            {{_i('Copyscape screenshot')}}
                        </h3>
                        <a target="_blank" href="{{$article->getMedia('copyscape')->first()->getFullUrl()}}">
                            <img class="img-thumbnail" width="250"
                                 src="{{$article->getMedia('copyscape')->first()->getFullUrl()}}" alt="">
                        </a>
                    </div>
                @endif
                @if(is_array($article->getMeta('socialposts')))
                    <div class="row">
                        <div class="row m-t-md">
                            <h3 class="text-center">
                                {{_i('Social posts')}}
                            </h3>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row m-t-lg">

                                @foreach($article->getMeta('socialposts') as $post)
                                    <blockquote>
                                        <p>
                                            {{$post}}
                                        </p>
                                    </blockquote>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(".ratable").each(function () {
            if({{ Auth::user()->id }} == {{ $articleClient }}) {
                var readOnly = false;
            }
            else {
                var readOnly = true;
            }
            $(this).rateYo({
                rating: $(this).attr('data-rating') + "%",
                precision: 0,
                readOnly: readOnly,
                fullStar: true,
                onSet: function (rating) {
                    console.log(rating);
                    $.post('{{action('Project\ArticlesController@rate', [$article])}}', {
                        rate: rating / 20
                    })
                    .done(function() {
                        $('.alert-success').remove();
                        $('#message').append(
                            '<div class="alert alert-success">'+
                            'Rating has been successfully updated!'+
                            '</div>'
                        );
                        $('.alert-success').delay(500).show(10, function() {
                            $(this).delay(3000).hide(10, function() {
                                $(this).remove();
                            });
                        });
                    })
                    .fail(function() {
                        $('.alert-danger').remove();
                        $('#message').append(
                            '<div class="alert alert-danger">'+
                            'Something wrong happened while rating info updating, please try later.'+
                            '</div>'
                        );
                        $('.alert-danger').delay(500).show(10, function() {
                            $(this).delay(3000).hide(10, function() {
                                $(this).remove();
                            });
                        });
                    });
                }
            })
        });
    </script>


@endsection