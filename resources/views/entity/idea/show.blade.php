@extends('master')

@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('Idea')}} : {{$idea->theme}}</h5>
            <div class="ibox-tools">

            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h3>{{_('Themes')}}</h3>
                    @if($idea->keywords()->suggestions()->get()->isEmpty())
                        <div class="text-muted m-b-lg m-t-lg">
                            {{_i('Empty')}}
                        </div>
                    @endif
                    <ul>
                        @foreach($idea->keywords()->suggestions()->accepted()->get() as $keyword)
                            <li>{{$keyword->text}}</li>
                        @endforeach
                    </ul>

                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h3>{{_('Questions')}}</h3>
                    @if($idea->keywords()->questions()->get()->isEmpty())
                        <div class="text-muted m-b-lg m-t-lg">
                            {{_i('Empty')}}
                        </div>
                    @endif
                    <ul>
                        @foreach($idea->keywords()->questions()->accepted()->get() as $keyword)
                            <li>{{$keyword->text}}</li>
                        @endforeach
                    </ul>

                </div>
            </div>


            <div class="row">
                <h3 class="text-center">{{_i('Additional data')}}</h3>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @foreach($idea->getFillable() as $property)
                        @if(in_array($property, ['project_id', 'type']))
                            @continue
                        @endif
                        <div>
                            <strong>
                                {{ucfirst(str_replace('_', ' ', $property))}} :
                            </strong>
                            {{ (is_int($idea->{$property})) ? ($idea->{$property}) ? 'Yes' : 'No' : $idea->{$property}  }}
                        </div>
                    @endforeach
                </div>

            </div> {{-- Additional data --}}


            <div class="row">
                <h3 class="text-center">{{_i('Media')}}</h3>
                <div class="col col-xs-12">
                    @each('entity.project.partials.files-row', $idea->getMedia(), 'media', 'entity.project.partials.files-row-empty')
                </div>
            </div> {{-- Media --}}
        </div>
    </div>


@endsection