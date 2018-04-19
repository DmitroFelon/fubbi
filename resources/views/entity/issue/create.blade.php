@extends('master')

@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('Add Issue')}}</h5>
        </div>
        <div class="ibox-content">

            {!! Form::open(['method' => 'POST', 'role'=>'form', 'route'=>['issues.store']]) !!}

            {!! Form::bsText('title', null, _i('Title'), null, ['required'], 'text') !!}

            {!! Form::bsText('tags', null,_i("Tags"),_i("Separate by coma or click 'enter'."), ['required', 'class'=> 'tagsinput' ]) !!}

            {!! Form::label(_i('Desctiption'), _i('Desctiption')) !!}

            {!! Form::textarea('body', null, ['required', 'class' => 'form-control']) !!}

            {{Form::submit('Create an Issue', ['class' => 'btn btn-primary m-t-md'])}}

            {!! Form::close() !!}


        </div>
    </div>

    <script type="text/javascript">

        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type == "text")) {
                return false;
            }
        }

        document.onkeypress = stopRKey;

    </script>

@endsection