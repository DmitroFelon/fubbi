<tr>
    <td class="project-status">
        <span class="label label-primary"></span>
    </td>
    <td class="project-title">
        <a href="{{action('Resources\PlanController@show', ['id' => $plan->id])}}">{{$plan->name}}</a>
        <br/>
        <small>{{$plan->id}}</small>
    </td>
    <td class="project-people">
        {{_i('Amount')}}: ${{$plan->amount/100}}
    </td>
    <td class="project-people">
        {{_i('Projects')}}: {{$plan->projects}}
    </td>
    <td class="project-actions">
        <a href="{{action('Resources\PlanController@show', ['id' => $plan->id])}}" class="btn btn-white btn-sm">
            <i class="fa fa-folder"></i> View
        </a>
        <a href="{{action('Resources\PlanController@edit', ['id' => $plan->id])}}" class="btn btn-white btn-sm">
            <i class="fa fa-pencil"></i> Edit
        </a>
    </td>
</tr>