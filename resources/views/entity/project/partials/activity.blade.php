@foreach($project->activity()->where('log_name', '!=', 'default')->orderBy('id', 'desc')->limit(10)->get() as $activity)
    <div class="timeline-item">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 date">
                <div class="row">
                    <i class="fa {{$project->getTimelineIcon($activity->log_name)}}"></i>
                </div>
                <div class="row">
                    <small class="text-navy">{{$activity->created_at->diffForHumans()}}</small>
                </div>
                <br/>
            </div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 content {{($loop->first) ? 'no-top-border':'' }}">
                <p class="m-b-xs"><strong>{{str_replace('_', '->', $activity->log_name)}}</strong></p>
                <p>{{$activity->description}}</p>
            </div>

        </div>
    </div>
@endforeach