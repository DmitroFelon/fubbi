<div class="btn-group">
    <button data-toggle="dropdown" class="btn btn-white dropdown-toggle yellow-bg">Export <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li>
            <a href="{{route('export.articles', ['ids' => $articles->pluck('id')->toArray(), 'as' => \App\Services\Google\Drive::PDF])}}">
                <i class="fa fa-file-pdf-o"></i> {{_i('PDF')}}
            </a>
        </li>
        <li>
            <a href="{{route('export.articles', ['ids' => $articles->pluck('id')->toArray(), 'as' => \App\Services\Google\Drive::MS_WORD])}}">
                <i class="fa fa-windows"></i> {{_i('MS Word')}}
            </a>
        </li>
        <li>
            <a href="{{route('export.articles', ['ids' => $articles->pluck('id')->toArray(), 'as' => \App\Services\Google\Drive::TEXT])}}">
                <i class="fa fa-file-text-o"></i> {{_i('Plain Text')}}
            </a>
        </li>
    </ul>
</div>