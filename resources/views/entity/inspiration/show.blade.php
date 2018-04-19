@extends('master')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>{{_i('')}}</h5>
                <div class="ibox-tools">
                    @if(Auth::user()->id == $inspiration->user_id)
                        <form method="post" action="{{route('inspirations.destroy', $inspiration)}}">
                            {{csrf_field()}}
                            {{method_field('delete')}}
                            <button type="submit" href="" class="btn btn-danger btn-xs pull-right">Delete</button>
                        </form>

                        <a href="{{route('inspirations.edit', $inspiration)}}"
                           class="m-r-md btn btn-success btn-xs pull-right">Edit</a>
                    @endif
                </div>
            </div>
            <div class="ibox-content">

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2">
                        <h3>
                            Have you noticed any interesting questions your customers
                            keep wanting answers to? If so, just list them below!
                        </h3>
                        <blockquote>{!! nl2br($inspiration->questions) !!}</blockquote>
                        <div class="row">
                            <div class="col col-xs-12">
                                @each('entity.project.partials.files-row', $inspiration->getMedia('questions'),
                                 'media', 'entity.project.partials.files-row-empty')
                            </div>
                        </div>

                        <hr class="m-b-lg">

                        <h3>
                            Any new industry stats or trends you want to tell us about?
                            Is there any reference article content you want to tell us so we can write about it in
                            future
                            articles?
                            Just insert links in the field below with all the info. Or you can securely attach documents
                        </h3>
                        <blockquote>{!! nl2br($inspiration->trends) !!}</blockquote>
                        <div class="row">
                            <div class="col col-xs-12">
                                @each('entity.project.partials.files-row', $inspiration->getMedia('trends'),
                                 'media', 'entity.project.partials.files-row-empty')
                            </div>
                        </div>

                        <hr class="m-b-lg">

                        <h3>
                            Any new case studies/success stories you want to tell us about?
                            Just insert links in the field below with all the info. Or you can securely attach documents
                        </h3>
                        <blockquote>{!! nl2br($inspiration->stories) !!}</blockquote>
                        <div class="row">
                            <div class="col col-xs-12">
                                @each('entity.project.partials.files-row', $inspiration->getMedia('stories'),
                                 'media', 'entity.project.partials.files-row-empty')
                            </div>
                        </div>

                        <hr class="m-b-lg">


                        <h3>
                            Do you have transcripts of fresh content e.g. interviews,
                            podcasts, live workshops, webinars? Just insert the links in the field below with all the
                            info.
                            Or you can securely attach documents.
                        </h3>
                        <blockquote>{!! nl2br($inspiration->transcripts) !!}</blockquote>
                        <div class="row">
                            <div class="col col-xs-12">
                                @each('entity.project.partials.files-row', $inspiration->getMedia('transcripts'),
                                 'media', 'entity.project.partials.files-row-empty')
                            </div>
                        </div>

                        <hr class="m-b-lg">

                        <h3>
                            Have you created any new products, special reports, webinars
                            that you would like us to create calls to action in your future articles? Just pop links to
                            the
                            info we need. Or you can securely attach documents.
                        </h3>
                        <blockquote>{!! nl2br($inspiration->cta) !!}</blockquote>
                        <div class="row">
                            <div class="col col-xs-12">
                                @each('entity.project.partials.files-row', $inspiration->getMedia('cta'), 'media',
                                 'entity.project.partials.files-row-empty')
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
