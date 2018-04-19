<div class="row">
    @foreach ($services->chunk(6) as $chunk)
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <table class="m-b-md">
                @foreach ($chunk as $service)
                    <tr>
                        <th>
                            <label for="{{$service->name}}">
                                {{$service->display_name}}
                            </label>
                        </th>
                        <td>
                            @if($service->type == \App\Models\Project\Service::TYPE_BOOLEAN)
                                <input type="hidden" name="{{$service->name}}" value="0">
                                <div class="i-checks">
                                    <label>
                                        <input
                                                type="checkbox"
                                                name="{{$service->name}}"
                                                value="true"
                                                {{($service->value)?'checked="checked"':''}}> <i></i>
                                    </label>
                                </div>
                            @else
                                <input class="form-control" id="{{$service->name}}"
                                       name="{{$service->name}}"
                                       value="{{$service->value}}">
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach
</div>
<div class="row">
    <div class="p-sm">
        {!! Form::submit('Update', ['class'=>'btn btn-primary']) !!}
    </div>
</div>