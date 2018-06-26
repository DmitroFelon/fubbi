<tr class="pointer">
    <td onclick="window.location='{{action('Project\ArticlesController@show', [$article->project, $article])}}'" class="project-title">
        <a href="{{action('Project\ArticlesController@show', [$article->project, $article])}}">{{$article->title}}</a>
        <br/>
        <small>
            {{_i('Created')}} {{$article->created_at->format('Y-m-d H:m')}}
        </small>
    </td>
    @if($article->accepted === 1)
        <td onclick="window.location='{{action('Project\ArticlesController@show', [$article->project, $article])}}'">
            <span class="badge badge-primary p-xs">{{_i('Accepted')}}</span>
        </td>
    @elseif($article->accepted === 0)
        <td onclick="window.location='{{action('Project\ArticlesController@show', [$article->project, $article])}}'">
            <span class="badge badge-danger p-xs">{{_i('Rejected')}}</span>
        </td>
    @else
        <td onclick="window.location='{{action('Project\ArticlesController@show', [$article->project, $article])}}'">
            <span class="badge p-xs">{{_i('New')}}</span>
        </td>
    @endif
    <td onclick="window.location='{{action('Project\ArticlesController@show', [$article->project, $article])}}'">
        {{$article->type}}
    </td>
    <td onclick="window.location='{{action('Project\ArticlesController@show', [$article->project, $article])}}'">
        @if($article->ratingPercent > 0)
            <span class="ratable" data-rateyo-read-only="true"
                  data-rating="{{$article->ratingPercent}}"></span>
        @endif
    </td>
    <td class="except">
        @if($article->idea)
            <a target="_blank"
               href="{{action('IdeaController@show', $article->idea)}}">"{{ucfirst($article->idea->theme)}}"</a>
        @endif
    </td>
    <td>
        {{($article->is_this_month) ? 'Yes' : 'No'}}
    </td>
    <td class="except">
        <div style="" class="btn-group">
            <button data-toggle="dropdown" class="btn btn-white btn-sm dropdown-toggle yellow-bg ladda-button">
                 Export
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a title="Export"
                       href="{{ route('export.article', [$article, 'as' => \App\Services\Google\Drive::PDF]) }}"
                       class="">
                        <i class="fa fa-file-pdf-o"></i> {{_i('PDF')}}
                    </a>
                </li>
                <li>
                    <a title="Export"
                       href="{{ route('export.article', [$article, 'as' => \App\Services\Google\Drive::MS_WORD]) }}"
                       class=" ">
                        <i class="fa fa-windows"></i> {{_i('MS Word')}}
                    </a>
                </li>
                <li>
                    <a title="Export"
                       href="{{ route('export.article', [$article, 'as' => \App\Services\Google\Drive::TEXT]) }}"
                       class=" ">
                        <i class="fa fa-file-text-o"></i> {{_i('Plain Text')}}
                    </a>
                </li>
            </ul>
        </div>
    </td>
</tr>