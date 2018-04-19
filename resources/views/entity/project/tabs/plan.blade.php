<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    @each('entity.project.partials.form.plan.card', $plans, 'plan')
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="ibox" id="stripe-form-wrapper" style="display: none">
        <div class="ibox-title">
            <h5>{{_i('Subscribe')}}</h5>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <form accept-charset="UTF-8" action="{{action('SubscriptionController')}}"
                          class="require-validation"
                          data-stripe-publishable-key="test_public_key"
                          id="payment-form" method="post">
                        {!! Form::hidden('_step', \App\Models\Helpers\ProjectStates::PLAN_SELECTION) !!}
                        <input type="hidden" name="plan_id" id="plan_id" value="">
                        {{ csrf_field() }}
                        <div class='form-row'>
                            <div class='col-xs-12 form-group required'>
                                <label for="project_name" class='control-label'>{{_i('Project Name')}}</label>
                                <input id="project_name" name="project_name" class='form-control' size='4' type='text'
                                       value="">
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='col-xs-12 form-group required'>
                                <label for="card_holder_name" class='control-label'>{{_i('CardHolder Name')}}</label>
                                <input class='form-control'
                                       size='4' type='text'
                                       id="card_holder_name"
                                       value="{{\Illuminate\Support\Facades\Auth::user()->first_name . ' ' . \Illuminate\Support\Facades\Auth::user()->last_name}}">

                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='col-xs-12 form-group card required'>
                                <label for="card_number" class='control-label'>{{_i('Card Number')}}</label>
                                <input value=""
                                       id="card_number"
                                       data-mask="9999-9999-9999-9999"
                                       maxlength="16" minlength="16" autocomplete='off' class='form-control card-number'
                                       size='20'
                                       type='text'>
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='col-xs-4 form-group cvc required'>
                                <label for="csv" class='control-label'>{{_i('CVC')}}</label>
                                <input value="" autocomplete='off' class='form-control card-cvc'
                                       placeholder='ex. 311' size='4'
                                       id="csv"
                                       type='text'>
                            </div>
                            <div class='col-xs-4 form-group expiration required'>
                                <label for="expiration_year" class='control-label'>{{_i('Expiration year')}}</label>
                                <input value="" maxlength="4" minlength="4" class='form-control card-expiry-year'
                                       id="expiration_year"
                                       placeholder='YYYY' size='4' type='text'>
                            </div>
                            <div class='col-xs-4 form-group expiration required'>
                                <label for="expiration_month" class='control-label'>{{_i('Expiration month')}}</label>
                                <input value="" maxlength="2" minlength="1" class='form-control card-expiry-month'
                                       id="expiration_month"
                                       placeholder='MM' size='2' type='text'>
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='col-xs-4 col-xs-offset-4'>
                                <button class='btn btn-primary submit-button'
                                        type='submit' style="margin-top: 10px;">
                                    <strong>
                                        <span>$ </span><span class='amount'></span>
                                        {{_i('Subscribe')}}
                                        <i class="fa fa-long-arrow-right"></i>
                                    </strong>
                                </button>
                            </div>
                        </div>
                        <div class='form-row'>
                            <div class='col-md-12 error form-group hide m-t-md'>
                                <div class='alert-danger alert'>
                                    {{_i('Please correct the errors and try again.')}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>



