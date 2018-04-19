@extends('master')

@section('before-content')

@endsection

@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                {{$user->name}}
                <span class="label label-default pull-right">{{$user->roles()->first()->display_name}}</span>
            </h5>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <table class="table small user-datails">
                        <tbody>
                        <tr>
                            <td>
                                <strong>
                                    {{_i('Phone')}}:
                                </strong>
                                {{$user->phone}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    {{_i('Email')}}:
                                </strong>
                                {{$user->email}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    {{_i('Projects')}}:
                                </strong>
                                {{$user->projects->count()}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    {{_i( 'Address Line 1' )}}:
                                </strong>
                                {{$user->address_line_1}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    {{_i( 'Address Line 2' )}}:
                                </strong>
                                {{$user->address_line_2}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    {{_i( 'Zip/Postal Code' )}}:
                                </strong>
                                {{$user->zip}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    {{_i( 'City' )}}:
                                </strong>
                                {{$user->city}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    {{_i( 'Country' )}}:
                                </strong>
                                {{$user->country}}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    {{_i( 'State/Province' )}}:
                                </strong>
                                {{$user->state}}

                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


@endsection