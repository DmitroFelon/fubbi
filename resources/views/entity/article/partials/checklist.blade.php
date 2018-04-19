<h3>
    {{_i('Checklist')}}
</h3>
<div class="row">
    @foreach($project->getRequirements()->chunk(6) as $chunk)
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            @foreach($chunk as $row)
                @if($row['media_collection'] and $project->getMedia($row['media_collection'])->isNotEmpty())
                    <div class="i-checks">
                        <label>
                            <input tabindex="{{$loop->parent->index+$loop->index}}" type="checkbox"
                                   required
                                   name="c"
                                   value="1"> <i></i>
                            {{_i($row['string'])}}
                        </label>
                    </div>
                    <div class="well m-t-xs m-b-md">
                        <h5><strong>{{_i('Files')}}:</strong></h5>
                        <div class="row">
                            @each('entity.project.partials.files-row',
                            $project->getMedia($row['media_collection']),
                             'media', 'entity.project.partials.files-row-empty')
                        </div>
                    </div>
                @endif
                @if($row['meta_name'] and collect($project->{$row['meta_name']})->isNotEmpty())
                    <div class="i-checks">
                        <label>
                            <input tabindex="{{$loop->parent->index+$loop->index}}" type="checkbox"
                                   required
                                   name="c"
                                   value="1"> <i></i>
                            {{_i($row['string'])}}
                        </label>
                    </div>
                    <div class=" well m-t-xs m-b-md">
                        <h5><strong>{{_i('Quiz result')}}:</strong></h5>
                        @foreach(collect($project->{$row['meta_name']}) as $meta)
                            <div class="border-bottom">
                                @if(filter_var($meta, FILTER_VALIDATE_URL))
                                    <a target="_blank" href="{{$meta}}">{{$meta}}</a>
                                @else
                                    {{$meta}}
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach
</div>