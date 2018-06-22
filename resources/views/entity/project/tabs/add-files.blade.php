@extends('master')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>{{_i('Project Files')}}</h5>
                <div class="ibox-tools">

                    <a href="{{ route('projects.show', ['id' => $project->id]) }}" class="btn btn-success btn-xs">Back to project</a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2">
                        <h3>Ready content</h3>
                        <div data-collection="ready_content" id="ready_content_group" class="dropzone m-b-lg"></div>
                        <h3>Compliance Guideline</h3>
                        <div data-collection="compliance_guideline" id="compliance_guideline_group" class="dropzone m-b-lg"></div>
                        <h3>Logo</h3>
                        <div data-collection="logo" id="logo_group" class="dropzone m-b-lg"></div>
                        <h3>Article Image</h3>
                        <div data-collection="article_images" id="article_images_group" class="dropzone m-b-lg"></div>
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
                'ready_content_group',
                'compliance_guideline_group',
                'logo_group',
                'article_images_group',
            ];

            dropzones.forEach(function (id) {
                var collection = $("#" + id).attr('data-collection');
                var dropzone = new Dropzone("div#" + id, {
                    url: "/files/store/project/{{ $project->id }}/" + collection,
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
                $.get("/files/get/project/{{ $project->id }}/" + thisDropzone.options.collection,
                        function (data) {
                            data.forEach(function (item) {
                                thisDropzone.emit("addedfile", item);
                                setDropzoneThumbnail(item, thisDropzone);
                                thisDropzone.emit("complete", item);
                            });
                        });
            }

            function dropzone_removedfile(item) {
                $.post({url: "/files/delete/project/{{ $project->id }}/" + item.id, method: "delete"});
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
