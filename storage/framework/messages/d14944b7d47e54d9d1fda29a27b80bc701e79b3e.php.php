
<div class="ibox-title">
    <h5>Fill quiz</h5>
</div>

<div class="ibox-content">

    <?php echo Form::model($project,
    ['files' => true, 'method' => 'PUT', 'role'=>'form', 'id' => 'quiz-form', 'action' => ['ProjectController@update', $project->id]]); ?>

    <?php echo Form::hidden('_step', \App\Models\Helpers\ProjectStates::QUIZ_FILLING); ?>

    <?php echo Form::hidden('_project_id', $project->id ); ?>

    <?php echo Form::hidden('themes_order', null, ['id'=>'themes_order']); ?>

    <h1>Step 1</h1>
    <fieldset>
        <?php echo Form::bsText(
            'themes', null,
            __("Type content themes here"),
            __("Type at least 10 themes. Separate by coma or click 'enter'."),
            ['required', 'class'=> 'tagsinput' ]
        ); ?>

        <div class="form-group <?php if (! ( isset($project) and $project->themes_order != '' )): ?> hide <?php endif; ?>"
             id="themes-order-list-wrapper">
            <?php echo Form::label('', __("Please reorder themes in order of priority.")); ?>

            <div class="text-muted">Drag and drop</div>
            <ul id="themes-order-list" class="list-group sortable">
                <?php if(isset($project)): ?>
                <?php $__currentLoopData = explode(',',$project->themes_order); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($row != ''): ?>
                        <li class="list-group-item" data-value="<?php echo e($row); ?>"><?php echo e($row); ?></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
        </div>
        <?php echo Form::bsText(
            'questions', null,
            __('What are the top 3 questions clients desperately want answers to?'),
            __("Separate by coma or click 'enter'"),
            ['required', 'class'=> 'tagsinput' ]
        ); ?>

    </fieldset>
    <h1>Step 2</h1>
    <fieldset>
        <?php echo Form::bsText('relevance', null, __('Is it essential that the content we create for you be relevant to a specific country, state or city? Please explain'), '', ['required']); ?>

        <div class="row">
            <h4 class="text-center"><strong>Who is your ideal target audience?</strong></h4>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <?php echo Form::bsText('audience[age]', null, __('Age'), null, [], 'number'); ?>

            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <?php echo Form::bsText('audience[sex]', null, __('Sex'), null, []); ?>

            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <?php echo Form::bsText('audience[Income]', null, __('Income'), null, []); ?>

            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <?php echo Form::bsText('audience[education]', null, __('Education'), null, []); ?>

            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <?php echo Form::bsText('audience[profession]', null, __('Profession'), null, []); ?>

            </div>
        </div>
        <?php echo Form::bsText('homepage', null, __('What is the home page url of the website we are creating content for?'), '', ['required'], 'url'); ?>



    </fieldset>
    <h1>Step 3</h1>
    <fieldset>
        <?php echo Form::bsSelect('language', ['en-us' => 'English - United States', 'en-gb' => 'English - Great Britain', 'en-au' => 'English - Australia'], null, __('What language do you want us to write content in?'), '', ['required']); ?>

        <h4 class="text-center"><strong><?php echo e(__("Here are our three writing styles. Please review each one of them and then select
            one.")); ?></strong></h4>
        <ol>
            <li>
                <strong><?php echo e(__("Fact based")); ?>:</strong>
                <a target="_blank"
                   href="https://drive.google.com/open?id=1FcI8StKK3ykMggGZsQBzAw0gPdutfYC0pY1hTluxL1M"><?php echo e(__("Click Here")); ?></a>
            </li>
            <li>
                <strong><?php echo e(__("Emotional")); ?>:</strong>
                <a target="_blank"
                   href="https://docs.google.com/document/d/1p8cmQaUfzK0tZfGWsmgLllcHDXlJs6TAFTqTr148iFk/edit"><?php echo e(__("Click Here")); ?></a>
            </li>
            <li>
                <strong><?php echo e(__("Middle Road")); ?>:</strong>
                <a target="_blank
            "
                   href="https://docs.google.com/document/d/1NrZsKQHzt9_82nwxS9twRn3P1EdYNnaHlAvEc-0rW2Y/edit"><?php echo e(__("Click Here")); ?></a>
            </li>
        </ol>
        <?php echo Form::bsSelect('writing_style', config('fubbi.form.quiz.writing_style'),
         null, __("Please select from the drop-down menu the style you prefer"), '', ['required']); ?>

        <?php echo Form::bsSelect('graphic_styles', config('fubbi.form.quiz.graphic_style'), null, __("Please select from the drop-down menu the style you prefer"), '', ['required']); ?>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading collapsible" data-toggle="collapse" href="#quora-block">
                            <h4 class="panel-title">
                                <span><?php echo e(__("For some of our packages we publish on Quora. Do you have a Quora account? (it’s a secure field)")); ?></span>
                                <i class="text-right fa fa-expand right" aria-hidden="true"></i>
                            </h4>
                        </div>
                        <div id="quora-block" class="panel-collapse row collapse">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <?php echo Form::bsText('quora_username', null, __("Username"), null, ['autocomplete' => 'off']); ?>

                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <?php echo Form::bsText('quora_password', null, __("Password"), null, ['autocomplete' => 'new-password'], 'password'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading collapsible" data-toggle="collapse" href="#seo-block">
                            <h4 class="panel-title">
                                <span><?php echo e(__("Do you have an SEO Company?")); ?></span>
                                <i class="text-right fa fa-expand right" aria-hidden="true"></i>
                            </h4>
                        </div>
                        <div id="seo-block" class="panel-collapse row collapse">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <?php echo Form::bsText('seo_first_name', null, __("SEO Contact Name"), __("First Name"), [], ''); ?>

                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <?php echo Form::bsText('seo_last_name', null, ' ', __("Last Name"), []); ?>

                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <?php echo Form::bsText('seo_email', null, __("SEO Contact Email"), null, [], 'email'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </fieldset>
    <h1>Step 4</h1>
    <fieldset>
        <?php echo Form::bsText('example_article', null, __("Please copy the URL of one article the exhibits a writing style that would like for your content"), null, [], 'url'); ?>

        <?php echo Form::bsText('compliance_guideline', null, __("Do you have branding guidelines?"), '', ['multiple'], 'file'); ?>

        <?php echo Form::bsText('logo', null, __("Do you have a high resolution logo that we can use?"), '', [], 'file'); ?>

        <?php echo Form::bsText('article_images[]', null, __("Do you want us to add images to your articles?"), __("Not all clients do as it’s somewhat subjective. If yes, please help us prepare the perfect images for your content. If you have photos you want to upload, you can do that too. Please understand that images can be “very” expensive but we’ll do the best we can for you. Please upload at least 5 image samples."), ['multiple'], 'file'); ?>

        <?php echo Form::bsText('article_images_links', null, __("You can do that by sharing examples to 5 articles with images that you like – just type in the URL."), __('Separate by coma or click "enter".'), ['required', 'class'=>'tagsinput' ] ); ?>

        <?php echo Form::bsText('avoid_keywords', null, __('Do you have keywords that you want us to avoid or content that you would not want us to use? Separate by coma or click "enter".'), '', [ 'class'=>'tagsinput']); ?>

        <?php echo Form::bsText('image_pages', null, __("You can also send us URLs of web pages that have the kind of images you like. Just type the urls below."), '', [ 'class'=>'tagsinput']); ?>

    </fieldset>
    <h1>Step 5</h1>
    <fieldset>
        <?php echo Form::bsText('google_access', null, __("We use Google docs to have you approve your articles. Please add all the email addresses of people in your organisation that need access."), '', [ 'class'=>"tagsinput"]); ?>

        <?php echo Form::bsText('cta', null, __("Do you want us to add calls to action (CTA) to the end of your articles?"), __("Examples include phoning your office for a complimentary consultation or review. Other times it might be to option in to receive a free report. If you want us to close your articles with a CTA please provide us with specific information i.e. if you want them to download a free report, please share a link to the download offer. If you want people to phone your office please provide details of what you’ll share on the phone call. We will, in turn create a one paragraph call to action for your articles"), ['rows' => '3'],'textarea'); ?>

        <?php echo Form::bsText('ready_content', null, __("Do you already have content you've produced that you wish to see in future articles? If so, please upload transcripts."), __("Note: You do not have to upload all your content now. Just enough to get us started with producing your articles and social posts. You can send us more at a later date. Please upload up to 10 transcripts"), ['multiple'], 'file'); ?>

        <?php echo Form::bsSelect('articles_preview', ['yes' => 'Yes', 'no' => 'No'], 'yes', __("Do you want to review outlines before we send you articles to approve?"), __("Note: Most clients say 'no'. You however should say 'yes' if 1. you've worked with writers before and you've never been happy, or... 2. If you know you're very selective , or... 3. Your audience has 'very' acute knowledge about a subject that is highly specialised"), []); ?>

    </fieldset>
    <?php echo Form::close(); ?>

</div>


