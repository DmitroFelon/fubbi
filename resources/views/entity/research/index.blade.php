@extends('master')


@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('Research Tool')}}</h5>
        </div>
        <div class="ibox-content">
            {!! Form::bsText('theme', null, _i('Keyword'), '', ['id' => 'theme'] ) !!}
            {!! Form::bsText('country',null, _i('Country'), '', ['id' => 'country', 'list' => 'countries'] ) !!}
            @include('entity.research.partials.countries')
            <button id="research" class="btn btn-primary">{{_('Research')}}</button>
            <div style="display:none;" id="spinner-wrapper" class="text-center">
                @include('components.spinner')
            </div>
            <div id="result" class="m-t-md">
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        jQuery(document).ready(function ($) {

            $('#research').on('click', function () {
                var theme = $('#theme').val();
                var country = $('#country').val();

                var sources = [
                    "{{ \App\Services\Api\KeywordTool::SOURCE_GOOGLE }}"
                ];

                $("#result").show();
                $("#result").html('')

                sources.forEach(function (source) {
                    $.ajax({
                        url: "{{action('ResearchController@load')}}",
                        method: 'get',
                        data: {
                            theme: theme,
                            country: country,
                            source: source
                        },
                        beforeSend: function () {
                            $("#spinner-wrapper").css('display', 'block');
                        },
                        success: function (data) {
                            $("#spinner-wrapper").css('display', 'none');
                            $("#result").append(data);
                            console.log('data');
                        },
                        error: function (data) {
                            $("#result").append("<div>Can't load keywords</div>");
                        }
                    });
                });
            });
        });
    </script>
@endsection

