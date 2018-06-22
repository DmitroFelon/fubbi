@extends('master')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>{{_i('Article Files')}}</h5>
                <div class="ibox-tools">

                    <a href="{{ route('project.articles.show', ['project' => $project->id, 'article' => $article->id]) }}" class="btn btn-success btn-xs">Back to article</a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2">
                        <h3>Copyscape</h3>
                        <div data-collection="copyscape" id="copyscape_group" class="dropzone m-b-lg"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script data-cfasync='false'>
        jQuery(document).ready(function ($) {
            var dropzones = ['copyscape_group'];

            dropzones.forEach(function (id) {
                var collection = $("#" + id).attr('data-collection');
                var dropzone = new Dropzone("div#" + id, {
                    url: "/files/store/article/{{ $article->id }}/" + collection,
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
                $.get("/files/get/article/{{ $article->id }}/" + thisDropzone.options.collection,
                        function (data) {
                            data.forEach(function (item) {
                                thisDropzone.emit("addedfile", item);
                                setDropzoneThumbnail(item, thisDropzone);
                                thisDropzone.emit("complete", item);
                            });
                        });
            }

            function dropzone_removedfile(item) {
                $.post({url: "/files/delete/article/{{ $article->id }}/" + item.id, method: "delete"});
                item.previewElement.remove();
            }
            var i = 0;
            function dropzone_success(item, response) {
                var lenght = response.length;
                var thisDropzone = this;
                var response_data = response[i];
                item.id = response_data.id;
                item.url = response_data.url;
                item.model_id = response_data.model_id;
                item.mime_type = response_data.mime_type;
                setDropzoneThumbnail(item, thisDropzone);
                i++;
                if (i == lenght) {
                    i = 0;
                }
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
