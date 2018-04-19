@extends('master')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>{{_i('Ideas')}}</h5>
                <div class="ibox-tools">
                    <a href="{{route('inspirations.create')}}" class="btn btn-success btn-xs pull-right">Create new
                        Idea</a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-hover issue-tracker">
                    <thead>
                    <tr>
                        <th>Questions</th>
                        <th>Trends</th>
                        <th>Stories</th>
                        <th>Transcripts</th>
                        <th>Cta</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($inspirations as $inspiration)
                        <tr>

                            <td>{{Illuminate\Support\Str::limit($inspiration->questions, 75, '...')}}</td>
                            <td>{{Illuminate\Support\Str::limit($inspiration->trends, 75, '...')}}</td>
                            <td>{{Illuminate\Support\Str::limit($inspiration->stories, 75, '...')}}</td>
                            <td>{{Illuminate\Support\Str::limit($inspiration->transcripts, 75, '...')}}</td>
                            <td>{{Illuminate\Support\Str::limit($inspiration->cta, 75, '...')}}</td>
                            <td>
                                <a href="{{route('inspirations.show', $inspiration)}}" class="btn btn-default">Show</a>
                            </td>
                            <td>
                                <a href="{{route('inspirations.edit', $inspiration)}}" class="btn btn-primary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    {!! $inspirations->links() !!}

                </table>
            </div>
        </div>
    </div>
@endsection
