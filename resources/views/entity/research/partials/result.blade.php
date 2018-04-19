<div class="row m-t-md">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h3 class="text-center m-b-md">{{$title}}</h3>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h4>{{_i('Questions')}} <small class="pull-right">total: {{collect($questions)->count()}}</small></h4>
            <div class="bg-muted p-md" style="max-height: 300px; overflow: auto">
                <div class="row">
                    @if(collect($questions)->isEmpty())
                        <div class="text-muted">
                            {{_i('Keyword Tool returned an empty result')}}
                        </div>
                    @else
                        <ul>
                            @foreach(collect($questions) as $suggestion)
                                <li class="p-xxs col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    {{$suggestion}}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h4>{{_i('Suggestions')}} <small class="pull-right">total: {{collect($suggestions)->count()}}</small></h4>
            <div class="bg-muted p-md" style="max-height: 300px; overflow: auto">
                <div class="row">
                    @if(collect($suggestions)->isEmpty())
                        <div class="text-muted">
                            {{_i('Keyword Tool returned an empty result')}}
                        </div>
                    @else
                        <ul>
                            @foreach(collect($suggestions) as $suggestion)
                                <li class="p-xxs col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    {{$suggestion}}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

