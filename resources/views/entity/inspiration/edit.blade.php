@extends('master')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>{{_i('Idea')}}</h5>
                <div class="ibox-tools">
                    <form method="post" action="{{route('inspirations.destroy', $inspiration)}}">
                        {{csrf_field()}}
                        {{method_field('delete')}}
                        <button type="submit" class="btn btn-danger btn-xs pull-right">Delete</button>
                    </form>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row m-t-lg m-b-lg">
                    <h2 class="text-center">
                        Got new ideas for content you want to see in future articles? <br> Just fill out the fields
                        below!
                    </h2>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2">
                        {!! Form::model($inspiration, ['method' => 'put', 'route' => ['inspirations.update', $inspiration]]) !!}
                        {!! Form::bsText('questions', null, _i("Have you noticed any interesting questions your customers
                         keep wanting answers to? If so, just list them below!"), _i(""), ['rows' => '3'],'textarea') !!}

                        <div data-collection="questions" id="questions_group" class="dropzone m-b-lg">
                        </div>
                        {!! Form::bsText('trends', null, _i("Any new industry stats or trends you want to tell us about?
                         Is there any reference article content you want to tell us so we can write about it in future articles?
                          Just insert links in the field below with all the info. Or you can securely attach documents"),
                           _i(""), ['rows' => '3'],'textarea') !!}

                        <div data-collection="trends" id="trends_group" class="dropzone m-b-lg">
                        </div>
                        {!! Form::bsText('stories', null, _i("Any new case studies/success stories you want to tell us about?
                         Just insert links in the field below with all the info. Or you can securely attach documents"),
                         _i(""), ['rows' => '3'],'textarea') !!}

                        <div data-collection="stories" id="stories_group" class="dropzone m-b-lg">
                        </div>
                        {!! Form::bsText('transcripts', null, _i("Do you have transcripts of fresh content e.g. interviews,
                         podcasts, live workshops, webinars? Just insert the links in the field below with all the info.
                          Or you can securely attach documents."), _i(""), ['rows' => '3'],'textarea') !!}

                        <div data-collection="transcripts" id="transcripts_group" class="dropzone m-b-lg">
                        </div>
                        {!! Form::bsText('cta', null, _i("Have you created any new products, special reports, webinars
                         that you would like us to create calls to action in your future articles? Just pop links to the
                          info we need. Or you can securely attach documents."), _i(""), ['rows' => '3'],'textarea') !!}

                        <div data-collection="cta" id="cta_group" class="dropzone m-b-lg">
                        </div>

                        {!! Form::submit('Save', ['class' => 'btn btn-primary  btn-lg m-t-lg']) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script data-cfasync='false'>
        jQuery(document).ready(function ($) {
            var dropzones = [
                'questions_group',
                'trends_group',
                'stories_group',
                'transcripts_group',
                'cta_group'
            ];

            dropzones.forEach(function (id) {
                var collection = $("#" + id).attr('data-collection');
                var dropzone = new Dropzone("div#" + id, {
                    url: "/inspirations/{{$inspiration->id}}/storeFile/" + collection,
                    paramName: 'files',
                    method: 'POST',
                    uploadMultiple: true,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    addRemoveLinks: true,
                    collection: collection,
                    init: dropzone_init,
                    success: dropzone_success,
                    removedfile: dropzone_removedfile
                });
            });

            function dropzone_init() {
                var thisDropzone = this;
                $.get("/inspirations/{{$inspiration->id}}/getFiles/" + thisDropzone.options.collection,
                        {collection: thisDropzone.options.collection},
                        function (data) {
                            data.forEach(function (item) {
                                thisDropzone.emit("addedfile", item);
                                setDropzoneThumbnail(item, thisDropzone);
                                thisDropzone.emit("complete", item);
                            });
                        });
            }

            function dropzone_removedfile(item) {
                $.post({url: '/inspirations/' + item.model_id + '/removeFile/' + item.id, method: "delete"});
                item.previewElement.remove();
            }

            function dropzone_success(item, response) {
                var thisDropzone = this;
                var response_data = response[0];
                item.id = response_data.id;
                item.url = response_data.url;
                item.model_id = response_data.model_id;
                item.mime_type = response_data.mime_type;
                setDropzoneThumbnail(item, thisDropzone);
            }

            function setDropzoneThumbnail(item, thisDropzone) {
                switch (item.mime_type) {
                    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                        thisDropzone.options.thumbnail.call(
                                thisDropzone, item, '/img/docx.png'
                        );
                        break;
                    case 'application/pdf':
                        thisDropzone.options.thumbnail.call(
                                thisDropzone, item, '/img/pdf.png'
                        );
                        break;
                    case 'image/jpg':
                    case 'image/jpeg':
                    case 'image/png':
                    case 'image/gif':
                        thisDropzone.options.thumbnail.call(thisDropzone, item, item.url);
                        break;
                    default:
                        thisDropzone.options.thumbnail.call(
                                thisDropzone, item, '/img/file.png'
                        );
                        break;
                }
            }
        });
    </script>


@endsection
