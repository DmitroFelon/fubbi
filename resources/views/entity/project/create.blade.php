@extends('master')

@section('content')
    @include('entity.project.form')
@endsection



@section('before-scripts')

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

@endsection
