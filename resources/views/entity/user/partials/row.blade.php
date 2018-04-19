<tr>
    <td class="client-avatar"><i class="fa fa-user fa-2x"></i></td>
    <td>
        <a target="_blank"
           href="{{url()->action('Resources\UserController@show', $user)}}">
            {{$user->name}}
        </a>
    </td>
    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
    <td> {{$user->email}}</td>
    <td class="contact-type"><i class="fa fa-phone"> </i></td>
    <td> {{$user->phone}}</td>
    @if($user->projects->count())
        <td class="contact-type"><i class="fa fa-file-o"></i></td>
        <td><strong>{{_i('Projects count')}} : </strong>{{$user->projects->count()}}</td>
    @else
        <td></td>
        <td></td>
    @endif

    <td> {{_i('Registered')}} {{$user->create_at}}</td>


</tr>