@foreach($iterable as $theme => $keywords)
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse-{{str_replace(' ', '', $theme)}}">{{$theme}}</a>
                </h4>
            </div>
            <div id="collapse-{{str_replace(' ', '', $theme)}}" class="panel-collapse collapse">
                <ul class="list-group">
                    @foreach($keywords as $keyword => $result)
                        @if($result)
                            <li class="list-group-item">{{$keyword}}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endforeach
