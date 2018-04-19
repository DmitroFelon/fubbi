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