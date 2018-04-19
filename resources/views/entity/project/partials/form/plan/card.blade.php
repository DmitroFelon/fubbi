<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    <div class="ibox product-box">
        <div class="ibox-title">
            <h5>{{$plan->name}}</h5>
            <div class="ibox-tools">
                <span class="label-primary p-xxs b-r-sm">
                    <strong>
                        $ {{$plan->amount/100}}
                    </strong>
                </span>
            </div>
        </div>
        <div class="ibox-content no-paddings">
            <div class="p-xxs m-b-md border-bottom">
                <table class="m-b-md">
                    @foreach($plan->metadata->jsonSerialize() as $key => $value)
                        <tr>
                            <th>
                                <small> {{ucwords( str_replace('_',' ',$key) )}}:</small>
                            </th>
                            <td>
                                <small>{{ (is_bool($value)) ? ($value) ?_i('Yes') : _i('No') : $value  }}</small>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="m-t text-righ">
                <a data-amount="{{$plan->amount/100}}"
                   data-plan="{{$plan->id}}"
                   href="#"
                   class="btn btn-sm btn-outline btn-primary subscribe-btn">
                    Subscribe <i class="fa fa-long-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>



