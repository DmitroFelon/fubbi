@extends('master')

@section('content')

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>{{_i('Transactions')}}</h5>
            </div>
            <div class="ibox-content">
                {{--search start--}}

                <form action="" method="get">

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="customer-select">{{_i('Customer')}}</label>
                                <select class="form-control" name="customer" id="customer-select">
                                    <option value="">{{_i('All customers')}}</option>
                                    @foreach($clients as $client)
                                        <option
                                                {{(\Illuminate\Support\Facades\Request::input('customer') == $client->stripe_id) ? 'selected=selected':''}}
                                                value="{{$client->stripe_id}}">
                                            {{$client->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="date_from">{{_i('From')}}</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
                                            id="date_from" name="date_from" type="text" class="form-control"
                                            value="{{$date_from}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="date_to">{{_i('To')}}</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
                                            id="date_to" name="date_to" type="text" class="form-control"
                                            value="{{$date_to}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <div class="input-group date">
                                    <button class="btn btn-primary" type="submit">{{_i('Search')}}</button>
                                    <a class="btn btn-default m-l-lg"
                                       href="{{action('ChargesController@index')}}">{{_i('Clear')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{--search end--}}
            </div>

            <div class="ibox">
                <div class="ibox-content">
                    <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                        <thead>
                        <tr>
                            <th>{{_i('Order ID')}}</th>
                            <th>{{_i('Customer')}}</th>
                            <th>{{_i('Amount')}}</th>
                            <th>{{_i('Date added')}}</th>
                            <th>{{_i('Source')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($charges as $charge)
                            <tr>
                                <td>
                                    {{$charge->id}}
                                </td>
                                <td>

                                    @if(!is_null($charge->customer))
                                        <a href="{{action('Resources\UserController@show', $charge->customer)}}">
                                            {{$charge->customer->name }}, {{$charge->customer->email}}
                                        </a>
                                    @else
                                        <small>{{_i('removed customer')}}</small>
                                    @endif

                                </td>
                                <td>
                                    ${{ $charge->amount/100  }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::createFromTimestamp($charge->created) }}
                                </td>
                                <td>
                                    {{ $charge->source->object  }}, {{ $charge->source->brand }}
                                    ,{{ $charge->source->country}}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="7">
                                <ul class="pagination pull-right"></ul>
                            </td>
                        </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        $(document).ready(function () {

            $('#date_from').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_to').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
@endsection