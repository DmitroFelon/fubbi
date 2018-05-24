{{Form::open(['method' => 'POST', 'action' => 'SettingsController@billing', 'id' => 'update-card-form'])}}

{{Form::hidden('stripeToken', null, ['id' => 'stripeToken'])}}

<div class='form-row'>
    <div class='col-xs-12 form-group card required'>
        <label for="card-number" class='control-label'>{{_i('Card Number')}}</label>
        <input id="card-number" value=""
               data-mask="9999-9999-9999-9999"
               placeholder="****-****-****-{{$user->card_last_four}}"
               maxlength="16" minlength="16" autocomplete='off' class='form-control card-number'
               size='20'
               type='text'>
    </div>
</div>
<div class='form-row'>
    <div class='col-xs-4 form-group cvc required'>
        <label for="card-cvc" class='control-label'>CVC</label>
        <input id="card-cvc" value="" autocomplete='off' class='form-control card-cvc'
               placeholder='ex. 311' size='4'
               type='text'>
    </div>
    <div class='col-xs-4 form-group expiration required'>
        <label for="card-exp-y" class='control-label'>{{_i('Expiration year')}}</label>
        <input id="card-exp-y" value="" maxlength="4" minlength="4" class='form-control card-expiry-year'
               placeholder='YYYY' size='4' type='text'>
    </div>
    <div class='col-xs-4 form-group expiration required'>
        <label for="card-exp-m" class='control-label'>{{_i('Expiration month')}}</label>
        <input id="card-exp-m" value="" maxlength="2" minlength="1" class='form-control card-expiry-month'
               placeholder='MM' size='2' type='text'>
    </div>
</div>

<div class='form-row'>
    <div class='col-md-12 error form-group hide'>
        <div class='alert-danger alert'>
            {{_i('Please correct the errors and try again.')}}
        </div>
    </div>
</div>

{{Form::submit(_i('Save'), ['class' => 'btn btn-primary', 'id' => 'card_update'])}}
{{Form::close()}}

@section('scripts')
<script>
    jQuery(function ($) {
        $('#card_update').click(function() {
            $(this).hide();
        });
        $('div[class="alert-danger alert"]').on('DOMSubtreeModified', function() {
            $('#card_update').show();
        });
    });
</script>
@endsection
