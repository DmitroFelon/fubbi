@foreach(\App\Models\Project::$media_collections as $collection)
    @if(!$project->hasMedia($collection)) @continue @endif
    <div class="row">
        <div class="col col-xs-12">
            <h3 class="text-center">{{title_case(str_replace('_',' ',$collection))}}</h3>
            @each('entity.project.partials.files-row', $project->getMedia($collection), 'media', 'entity.project.partials.files-row-empty')
        </div>
    </div>
    <hr>
@endforeach

<form action="{{ route('add.files', ['project' => $project]) }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="files" class="control-label">Choose files</label>
        <input id="files" type="file" class="form-control" name="file[]" multiple required>
    </div>
    <div class="form-group">
        <label for="file_type" class="control-label">Chose files type</label>
        <select id="file_type" name="file_type" class="form-control" required>
            <option value="" disabled selected>Choose file type</option>
            <option value="article_images">Article images</option>
            <option value="compliance_guideline">Compliance guideline</option>
            <option value="logo">Logo</option>
            <option value="ready_content">Ready content</option>
        </select>
    </div>
    <button class="btn btn-primary" type="submit">Upload files</button>
</form>