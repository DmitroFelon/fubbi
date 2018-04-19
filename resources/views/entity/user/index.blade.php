@extends('master')

@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('Users')}}</h5>
            <div class="ibox-tools">
                @role([\App\Models\Role::ADMIN])
                <a target="_blank" href="{{url()->action('Resources\UserController@create')}}"
                   class="btn btn-primary btn-xs">{{_i('Create new User')}}</a>
                @endrole
            </div>
        </div>
        <div class="ibox-content">
            <div class="">
                <label class="radio-inline bg-muted b-r-xl p-xs border-left border-right border-top border-bottom">
                    <input type="radio"
                           class="hidden"
                           name="role"
                           id="role_all"
                           value="all"
                           checked>
                    <span style="margin-top:-0.1em;" class="badge badge-primary">{{\App\User::withTrashed()->count()}}</span>
                    <b class="">All</b>
                </label>
                @foreach(\App\Models\Role::all() as $role)
                    <label class="radio-inline b-r-xl p-xs border-left border-right border-top border-bottom">
                        <input type="radio"
                               class="hidden"
                               name="role"
                               id="role_{{$role->name}}"
                               value="{{$role->display_name}}">
                        <span style="margin-top:-0.1em;"
                              class="badge badge-primary">{{\App\User::withRole($role->name)->withTrashed()->count()}}</span>
                        <b class="">{{$role->display_name}}</b>
                    </label>
                @endforeach
            </div>
            <table class="table table-hover"
                   id="users-table"
                   data-filtering="true"
                   data-filter-delay="50">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th data-filterable="true">Role</th>
                    <th>Block</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <a target="_blank"
                               href="{{action('Resources\UserController@show', $user)}}">
                                {{$user->name}}
                            </a>
                        </td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{@$user->roles()->first()->display_name}}</td>
                        <td>
                            {!! Form::open([ 'method'  => 'delete', 'route' => [ 'users.destroy', $user ] ]) !!}
                            {!! Form::submit( ($user->trashed()) ? 'Restore' : 'Block',
                            ['class' => ($user->trashed()) ? 'btn btn-success btn-xs' : 'btn btn-danger btn-xs'] ) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                <ul class="pagination"></ul>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.6/footable.core.min.js"
            integrity="sha256-0gbetQZJW5O/3L5HemCVmjRftfszer/l2fAOve5aOuk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.6/footable.filtering.min.js"
            integrity="sha256-F82P7rqZOCQ6AcRdGvkodKJa3QiLt/eL3hGflxEzYq8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.6/footable.paging.min.js"
            integrity="sha256-jVzB3aGlzQRRL5mFfZtw/buWVc6qoCDgpv+a4ACHTtk=" crossorigin="anonymous"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.6/footable.filtering.min.css"
          integrity="sha256-+/31hebBCC6L8fDhEk3v/GaSPj7LaXyhCpycfCs7KRI=" crossorigin="anonymous"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.6/footable.core.bootstrap.min.css"
          integrity="sha256-RT3whXYJ8IdLcSWcxz/wl1zD9EWTAzctE/szKayg1lA=" crossorigin="anonymous"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.6/footable.paging.min.css"
          integrity="sha256-nn/ARKd+l1ZLJK9Xw62iyxfdRyL3YXZU2EIItdBpqbE=" crossorigin="anonymous"/>
    <style>
        .input-group-btn .dropdown-toggle {
            display: none;
        }
    </style>
    <script>
        jQuery(function ($) {

            $('#users-table').footable({
                "columns": [
                    {"name": "name", "title": "Name"},
                    {"name": "email", "title": "Email"},
                    {"name": "phone", "title": "Phone"},
                    {"name": "role", "title": "Role"},
                    {"name": "block", "title": ""}
                ],
                "paging": {
                    "enabled": true,
                    "page-size": 1
                }
            });
            var table = FooTable.get('#users-table').use(FooTable.Filtering);
            $('input[type=text]').on('change', function () {
                if ($(this).val() === '') {
                    $('input[type=radio][name=role]').val(['all']);
                    $('[name=role]').parent('label').removeClass('bg-muted');
                    $('input[type=radio][name=role][value="all"]').parent('label').addClass('bg-muted');
                }
            });
            $('[name=role]').on('change', function () {
                var value = $(this).val();
                $('[name=role]').parent('label').removeClass('bg-muted');
                $(this).parent('label').toggleClass('bg-muted');
                if (value === 'all') {
                    table.removeFilter('role');
                } else {
                    table.addFilter('role', value, ['role']);
                }
                table.filter();
            });
        });
    </script>
@endsection