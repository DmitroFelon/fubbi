<div class="ibox-title">
    <h5>Fill quiz</h5>
</div>

<div class="ibox-content">
    {!! Form::model($project,
    ['files' => true, 'method' => 'PUT', 'role'=>'form', 'id' => 'quiz-form', 'action' => ['Resources\ProjectController@update',
     $project->id]])
    !!}
    {!! Form::hidden('_step', \App\Models\Helpers\ProjectStates::QUIZ_FILLING) !!}
    {!! Form::hidden('_project_id', $project->id ) !!}
    {!! Form::select('themes_order[]',[], null, ['id'=>'themes_order', 'multiple', 'style' => 'display:none;']) !!}
    <h1>Step 1</h1>
    <fieldset>
        {!! Form::bsSelect(
            'themes[]', $project->prepareTagsInputThemes(), null,
            _i("Type content themes here"),
            _i("Type at least 10 themes. Separate by coma or click 'enter'."),
            ['required', 'id' => 'themes', 'class'=>'tagsinput', 'multiple']
        ) !!}
        <div class="form-group @unless( isset($project) and $project->themes_order != '' ) @endunless"
             id="themes-order-list-wrapper">
            {!! Form::label('', _i("Please reorder themes in order of priority.")) !!}
            <div class="text-muted">{{_i('Drag and drop')}}</div>

            <ul id="themes-order-list" class="list-group sortable">
                @foreach($project->ideas()->themes()->get() as $row)
                    <li class="list-group-item" data-value="{{$row->theme}}">{{$row->theme}}</li>
                @endforeach
            </ul>
        </div>


        @if($project->ideas()->questions()->count() == 0)
            @for($i=0;$i<=6;$i++)
                @if($i == 0)
                    {!! Form::bsText(
                       'questions['.$i.']', null,
                       _i('What are the top 7 questions clients desperately want answers to?'),
                       null, ['required']
                   ) !!}
                @else
                    {!! Form::bsText('questions['.$i.']', null,
                     null, null, ['required']) !!}
                @endif
            @endfor
        @else
            @foreach($project->ideas()->questions()->get() as $idea)
                @if($loop->first)
                    {!! Form::bsText(
                       'questions['.$idea->id.']', $idea->theme,
                       _i('What are the top 7 questions clients desperately want answers to?'),
                       null, ['required']
                   ) !!}
                @else
                    {!! Form::bsText('questions['.$idea->id.']', $idea->theme,
                     null, null, ['required']) !!}
                @endif
            @endforeach

            @if($project->ideas()->questions()->count() < 7)
                @for($i=0; $i < 7 - $project->ideas()->questions()->count(); $i++)
                    {!! Form::bsText('questions['.$i.']', null,
                          null, null, ['required']) !!}
                @endfor
            @endif

        @endif

    </fieldset>
    <h1>Step 2</h1>
    <fieldset>
        {!! Form::bsText('relevance', null,
         _i('Is it essential that the content we create for you be relevant to a specific country, state or city? Please explain'),
          '', ['required']) !!}
        <div class="row">
            <h4 class="text-center"><strong>Who is your ideal target audience?</strong></h4>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {!! Form::bsText('audience[age]', null, _i('Age'), null, ['required'], 'number') !!}
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {!! Form::bsText('audience[sex]', null, _i('Sex'), null, ['required']) !!}
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {!! Form::bsText('audience[Income]', null, _i('Income'), null, ['required']) !!}
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {!! Form::bsText('audience[education]', null, _i('Education'), null, ['required']) !!}
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {!! Form::bsText('audience[profession]', null, _i('Profession'), null, ['required']) !!}
            </div>
        </div>
        {!! Form::bsText('homepage', null,
         _i('What is the home page url of the website we are creating content for?'), 'Should be a valid url, like: http://example.com', ['required'], 'url') !!}
    </fieldset>
    <h1>Step 3</h1>
    <fieldset>
        {!! Form::bsSelect('language', ['en-us' => 'English - United States', 'en-gb' => 'English - Great Britain',
         'en-au' => 'English - Australia'], null, _i('What language do you want us to write content in?'), '', ['required']) !!}
        <h4 class="text-center">
            <strong>
                {{_i("Here are our three writing styles. Please review each one of them and then select one.")}}
            </strong>
        </h4>
        <ol class="writting-style-list">
            <li>
                <strong>{{_i("Fact based")}}:</strong>
                <a target="_blank"
                   href="https://drive.google.com/open?id=1FcI8StKK3ykMggGZsQBzAw0gPdutfYC0pY1hTluxL1M">
                    {{_i("Click Here")}}
                </a>
            </li>
            <li>
                <strong>{{_i("Emotional")}}:</strong>
                <a target="_blank"
                   href="https://docs.google.com/document/d/1p8cmQaUfzK0tZfGWsmgLllcHDXlJs6TAFTqTr148iFk/edit">
                    {{_i("Click Here")}}
                </a>
            </li>
            <li>
                <strong>{{_i("Middle Road")}}:</strong>
                <a target="_blank"
                   href="https://docs.google.com/document/d/1NrZsKQHzt9_82nwxS9twRn3P1EdYNnaHlAvEc-0rW2Y/edit">
                    {{_i("Click Here")}}
                </a>
            </li>
        </ol>
        {!! Form::bsSelect('writing_style', config('fubbi.form.quiz.writing_style'),
         null, _i("Please select from the drop-down menu the style you prefer"), '', ['required']) !!}

        <h4 class="text-center">
            <strong>
                {{_i("Here are our graphic styles. Please check each one of them then
                    select ONE that you would like us to model in your social media posts.")}}
            </strong>
        </h4>
        <ol class="writting-style-list">
            <li>
                <strong>{{_i("Clean Lines")}}:</strong>
                <a target="_blank"
                   href="https://s3.us-east-2.amazonaws.com/fubbico/Graphics+Styles/Clean+lines+1.jpg">
                    {{_i("Click Here")}}
                </a>
            </li>
            <li>
                <strong>{{_i("Borders")}}:</strong>
                <a target="_blank"
                   href="https://s3.us-east-2.amazonaws.com/fubbico/Graphics+Styles/Boarders+2.jpg">
                    {{_i("Click Here")}}
                </a>
            </li>
            <li>
                <strong>{{_i("Fade")}}:</strong>
                <a target="_blank"
                   href="https://s3.us-east-2.amazonaws.com/fubbico/Graphics+Styles/fade+3.jpg">
                    {{_i("Click Here")}}
                </a>
            </li>
            <li>
                <strong>{{_i("Cut Out")}}:</strong>
                <a target="_blank"
                   href="https://s3.us-east-2.amazonaws.com/fubbico/Graphics+Styles/Cut+out+4.jpg">
                    {{_i("Click Here")}}
                </a>
            </li>
            <li>
                <strong>{{_i("Vibrant")}}:</strong>
                <a target="_blank"
                   href="https://s3.us-east-2.amazonaws.com/fubbico/Graphics+Styles/vibrant+5.jpg">
                    {{_i("Click Here")}}
                </a>
            </li>
            <li>
                <strong>{{_i("Boxed Text")}}:</strong>
                <a target="_blank"
                   href="https://s3.us-east-2.amazonaws.com/fubbico/Graphics+Styles/boxed+text+6.jpg">
                    {{_i("Click Here")}}
                </a>
            </li>
        </ol>
        {!! Form::bsSelect('graphic_styles', config('fubbi.form.quiz.graphic_style'), null,
         _i("Please select from the drop-down menu the style you prefer"), '', ['required']) !!}

        @if($project->services()->where('name', 'quora')->first() and $project->services()->where('name', 'quora')->first()->value)
            <div class="row">
                <label for="is-quora-block"
                       class="col-lg-2 col-md-12 col-sm-12 col-xs-12 control-label">
                    {{_i('For some of our packages we publish on Quora.
                                     Do you have a Quora account? (it’s a secure field)')}}
                </label>
                <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                    <strong class="m-r-sm">No</strong>
                    <input id="is-quora-block"
                           type="checkbox" value="yes" data-target="quora-block" class="js-switch condition-cb"/>
                    <strong class="m-l-sm">Yes</strong>
                </div>
            </div>
            <div style="display:none;" class="row" id="quora-block">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsText('quora_username', null, _i("Username"), null,
                     ['autocomplete' => 'off', 'required']) !!}
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsText('quora_password', null, _i("Password"), null,
                     ['autocomplete' => 'new-password', 'required'], 'password') !!}
                </div>
            </div>
        @endif


        <div class="row">
            <label for="is-seo-block"
                   class="col-lg-2 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i('Do you have an SEO Company?')}}
            </label>
            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                <strong class="m-r-sm">No</strong>
                <input id="is-seo-block"
                       type="checkbox" value="yes" data-target="seo-block" class="js-switch condition-cb"/>
                <strong class="m-l-sm">Yes</strong>
            </div>
        </div>

        <div style="display:none;" id="seo-block" class="row">
            <h4 class="text-center"><strong>{{_i('SEO Contact Name')}}</strong></h4>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                {!! Form::bsText('seo_first_name', null, ' ', _i("First Name"), ['required'], '') !!}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                {!! Form::bsText('seo_last_name', null, ' ', _i("Last Name"), ['required']) !!}
            </div>
            <h4 class="text-center"><strong>{{_i("SEO Contact Email")}}</strong></h4>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {!! Form::bsText('seo_email', null, ' ', null, ['required'], 'email') !!}
            </div>
        </div>

    </fieldset>
    <h1>Step 4</h1>
    <fieldset>
        {!! Form::bsText('example_article', null, _i("Please copy the URL of one article thet exhibits a
         writing style that You would like for your content"), null, [], 'url') !!}

        <div class="row m-t-md">
            <label for="is-compliance_guideline-block"
                   class="col-lg-4 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i('Do you have branding guidelines?')}}
            </label>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <strong class="m-r-sm">No</strong>
                <input id="is-compliance_guideline-block"
                       type="checkbox" value="yes" data-target="compliance_guideline-block"
                       class="js-switch condition-cb"/>
                <strong class="m-l-sm">Yes</strong>
            </div>
        </div>

        <div style="display: none" class="row" id="compliance_guideline-block">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="control-label"> </label>
                <div id="compliance_guideline-group" class="dropzone">
                </div>
            </div>
        </div>

        <div class="row m-t-md">
            <label for="is-logo-block"
                   class="col-lg-4 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i('Do you have a high resolution logo that we can use?')}}
            </label>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <strong class="m-r-sm">No</strong>
                <input id="is-logo-block"
                       type="checkbox" value="yes" data-target="logo-block"
                       class="js-switch condition-cb"/>
                <strong class="m-l-sm">Yes</strong>
            </div>
        </div>

        <div style="display: none" class="row" id="logo-block">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="control-label"></label>
                <div id="logo-group" class="dropzone">
                </div>
            </div>
        </div>

        <div class="row m-t-md">
            <label for="is-article_images-block"
                   class="col-lg-4 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i("Do you want us to add images to your articles?")}}
            </label>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <strong class="m-r-sm">No</strong>
                <input id="is-article_images"
                       type="checkbox" value="yes" data-target="article_images-block"
                       class="js-switch condition-cb"/>
                <strong class="m-l-sm">Yes</strong>
            </div>

        </div>

        <div style="display: none" class="row" id="article_images-block">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="control-label"></label>
                <div id="article_images-group" class="dropzone">
                </div>
                <span class="text-muted">
                {{_i("Not all clients do as it’s somewhat subjective. If yes, please help us prepare the perfect images
                 for your content. If you have photos you want to upload, you can do that too. Please understand that
                 images can be “very” expensive but we’ll do the best we can for you. Please upload at least 5 image
                  samples.")}}
            </span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {!! Form::bsSelect('article_images_links[]',$project->prepareTagsInput('article_images_links'),
                         null, _i("You can do that by sharing examples to 5 articles with images that you like – just type in the URL."),
                          _i('Separate by coma or click "enter".'), ['multiple' => 'multiple', 'required', 'class'=>'tagsinput' ] ) !!}

                {!! Form::bsSelect('image_pages[]',$project->prepareTagsInput('image_pages'),
                 null, _i("You can also send us URLs of web pages that have the kind of images you like.
                  Just type the urls below."), _i('Separate by coma or click "enter".'),
                   ['multiple' => 'multiple', 'class'=>'tagsinput']) !!}
            </div>

        </div>

        <div class="row m-t-md">
            <label for="is-avoid_keywords-block"
                   class="col-lg-4 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i("Do you have keywords that you want us to avoid or content that you would not want us to use?")}}
            </label>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <strong class="m-r-sm">No</strong>
                <input id="is-avoid_keywords"
                       type="checkbox" value="yes" data-target="avoid_keywords-block"
                       class="js-switch condition-cb"/>
                <strong class="m-l-sm">Yes</strong>
            </div>
        </div>

        <div style="display: none" class="" id="avoid_keywords-block">
            {!! Form::bsSelect('avoid_keywords[]',$project->prepareTagsInput('avoid_keywords'),
            null, _i('Avoid Keywords'), _i('Separate by coma or click "enter".'),
              ['multiple' => 'multiple', 'class'=>'tagsinput']) !!}
        </div>


    </fieldset>
    <h1>Step 5</h1>
    <fieldset>
        <div class="row m-t-md">
            <label for="is-cta-block"
                   class="col-lg-4 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i("Do you want us to add calls to action (CTA) to the end of your articles?")}}
            </label>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <strong class="m-r-sm">No</strong>
                <input id="is-cta"
                       type="checkbox" value="yes" data-target="cta-block"
                       class="js-switch condition-cb"/>
                <strong class="m-l-sm">Yes</strong>
            </div>
        </div>

        <div style="display: none" class="" id="cta-block">
            {!! Form::bsText('cta', null, _i(""),
             _i("Examples include phoning your office for a complimentary consultation or review. Other times it might
              be to opt in to receive a free report. If you want us to close your articles with a CTA please provide us with
               specific information i.e. if you want them to download a free report, please share a link to the download offer.
                If you want people to phone your office please provide details of what you’ll share on the phone call.
                 We will, in turn create a one paragraph call to action for your articles"), ['rows' => '3'],'textarea') !!}
        </div>


        <div class="row m-t-md">
            <label for="is-ready_content-block"
                   class="col-lg-4 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i("Do you already have content you've produced that you wish to
                see in future articles? If so, please upload transcripts.")}}
            </label>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <strong class="m-r-sm">No</strong>
                <input id="is-ready_content"
                       type="checkbox" value="yes" data-target="ready_content-block"
                       class="js-switch condition-cb"/>
                <strong class="m-l-sm">Yes</strong>
            </div>
        </div>


        <div style="display: none" class="row" id="ready_content-block">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="control-label">{{_i("")}}</label>
                <div id="ready_content-group" class="dropzone">
                </div>
                <span class="text-muted">
                {{_i("Note: You do not have to upload all your content now. Just enough to get us started with producing
                your articles and social posts. You can send us more at a later date. Please upload up to 10 transcripts")}}
            </span>
            </div>
        </div>

        <div class="row m-t-md">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {!! Form::bsSelect('articles_preview', ['yes' => 'Yes', 'no' => 'No'], 'yes',
                 _i("Do you want to review outlines before we send you articles to approve?"),
                  _i("Note: Most clients say 'no'. You however should say 'yes' if 1. you've
                  worked with writers before and you've never been happy, or... 2.
                   If you know you're very selective , or... 3. Your audience has 'very' acute
                   knowledge about a subject that is highly specialised"), []) !!}
            </div>
        </div>


    </fieldset>
    {!! Form::close() !!}
</div>


<script>
    window.onbeforeunload = function (e) {
        var message = "{{_i('Are You sure?')}}",
                e = e || window.event;
        // For IE and Firefox
        if (e) {
            e.returnValue = message;
        }
        // For Safari and Chrome
        return message;
    };
</script>

