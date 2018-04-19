
@if($articles->isNotEmpty())
    <table class="table table-stripped table-hover small m-t-md footable">
        <tbody>
        @foreach($articles as $article)
            <tr onclick="location.href='{{action('Project\ArticlesController@show', [$article->project->id, $article])}}';"
                class="clickable-row b-r-md">
                <td class="no-borders">
                    <strong> {{_i('Created at')}} </strong> : {{$article->updated_at->format('m-d-Y')}}
                </td>
                <td class="no-borders">
                    <strong> {{_i('Client')}} </strong> : {{$article->project->client->name}}
                </td>
                <td class="no-borders">
                    <strong> {{_i('Disapprovals')}} </strong> : {{$article->attempts}}
                </td>
                <td class="no-borders">
                    <strong> {{_i('Rating')}} </strong> : {{round($article->avgRating, 2)}}
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5">
                <ul class="pagination pull-right"></ul>
            </td>
        </tr>
        </tfoot>
    </table>
@else
    <div class="p-sm text-muted">
        {{_i('No results')}}
    </div>
@endif