<div class="row text-center">
    <h3>{{_i('Please, fill out the fields below')}} </h3>
</div>

<script data-cfasync='false' type="text/javascript">
    var idea_id = "{{$idea->id}}";
</script>

<div class="row p-h-lg">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <input value="0" name="meta[{{$idea->id}}][this_month]" type="hidden"/>
        <input value="0" name="meta[{{$idea->id}}][next_moth]" type="hidden"/>
        <div class="row">
            <label for="meta-{{$idea->id}}-points_covered"
                   class="col-lg-2 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i('What points should we cover in the content?')}}
            </label>
            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                <textarea id="meta-{{$idea->id}}-points_covered"
                          name="meta[{{$idea->id}}][points_covered]"
                          type="text" class="autoheight form-control">{{$idea->points_covered}}</textarea>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="row">
            <label for="meta-{{$idea->id}}-points_avoid"
                   class="col-lg-2 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i('What points should we avoid in content?')}}
            </label>
            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                <textarea id="meta-{{$idea->id}}-points_avoid"
                          name="meta[{{$idea->id}}][points_avoid]"
                          type="text" class="autoheight form-control">{{$idea->points_avoid}}</textarea>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="row">
            <label for="meta-{{$idea->id}}-references"
                   class="col-lg-2 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i('Links to references articles, research, trancripts references to help us write the contract? Please copy and paste links here')}}
            </label>
            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                <textarea id="meta-{{$idea->id}}-references"
                          name="meta[{{$idea->id}}][references]"
                          type="text" class="autoheight form-control">{{$idea->references}}</textarea>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="row">
            <label for="meta-{{$idea->id}}-next_moth"
                   class="col-lg-2 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i('Upload documents')}}
            </label>
            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div onload="" data-theme="{{$idea->id}}" id="meta-{{$idea->id}}-files"
                         class="dropzone keyword-dropzone">
                    </div>
                </div>
                <span class="help-block"></span>
            </div>

        </div>
        <br>
        <div class="row">
            <label for="meta-{{$idea->id}}-this_month"
                   class="col-lg-2 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i('Write about topic this month?')}}
            </label>
            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">

                <input id="meta-{{$idea->id}}-this_month"
                       name="meta[{{$idea->id}}][this_month]"
                       {{($idea->this_month) ? 'checked' : ''}}
                       type="checkbox" value="1" class="js-switch"/>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="row">
            <label for="meta-{{$idea->id}}-next_month"
                   class="col-lg-2 col-md-12 col-sm-12 col-xs-12 control-label">
                {{_i('Write about topic next month?')}}
            </label>
            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">

                <input id="meta-{{$idea->id}}-next_month"
                       name="meta[{{$idea->id}}][next_month]"
                       {{($idea->next_month) ? 'checked' : ''}}
                       type="checkbox" value="1" class="js-switch"/>
                <span class="help-block"></span>
            </div>
        </div>
    </div>
</div>

<div class="row text-center">
    <h3>{{_i('Keywords')}}</h3>
</div>

<div class="tabs-container keyword-question">

    <ul class="nav nav-tabs">
        <li class="active">
            <a role="tab" aria-expanded="true" class="keyword-question-tab" data-toggle="tab"
               href="#tab-themes-{{str_replace(' ', '-', $idea->theme)}}">
                {{_i('Keywords Suggestions')}}
            </a>
        </li>

        <li>
            <a role="tab" aria-expanded="false" class="keyword-question-tab" data-toggle="tab"
               href="#tab-questions-{{str_replace(' ', '-', $idea->theme)}}">
                {{_i('Questions')}}
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" id="tab-themes-{{str_replace(' ', '-', $idea->theme)}}" class="tab-pane active">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-2 m-t-xs">
                                    <label for="input-{{str_replace(' ', '', $idea->id)}}"
                                           class="control-label">{{_i('Add keyword manually')}}
                                    </label>
                                </div>
                                <div class="col-lg-4">
                                    <input required data-theme="{{$idea->id}}"
                                           id="input-{{str_replace(' ', '', $idea->id)}}"
                                           type="text"
                                           class="form-control keyword-input">
                                </div>
                                <div class="col-lg-2">
                                    <button data-theme="{{$idea->id}}"
                                            id="keyword-input-submit-{{str_replace(' ', '', $idea->id)}}"
                                            class="form-control btn-primary btn keyword-input-submit">
                                        {{_i('Add')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row p-h-lg">
                            <div data-theme="{{$idea->id}}" id="wrapper-{{str_replace(' ', '', $idea->id)}}"
                                 class="col-xs-12 col-sm-12 col-md-12 col-lg-12 keywords-wrapper">
                                @foreach($keywords as $keyword)
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                        <div class="i-checks">
                                            <label>
                                                <input class="keywords-checkbox"
                                                       {{($keyword->accepted)?'checked':''}}
                                                       type="checkbox"
                                                       name="keywords[{{$idea->id}}][{{$keyword->text}}]"> <i></i>
                                                {{ucfirst($keyword->text)}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div role="tabpanel" id="tab-questions-{{str_replace(' ', '-', $idea->theme)}}" class="tab-pane">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-2 m-t-xs">
                                    <label for="input-{{str_replace(' ', '', $idea->id)}}"
                                           class="control-label">{{_i('Add question manually')}}
                                    </label>
                                </div>
                                <div class="col-lg-4">
                                    <input required data-question-theme="{{$idea->id}}"
                                           id="input-{{str_replace(' ', '', $idea->id)}}"
                                           type="text"
                                           class="form-control keyword-input">
                                </div>
                                <div class="col-lg-2">
                                    <button data-question-theme="{{$idea->id}}"
                                            id="keyword-input-submit-{{str_replace(' ', '', $idea->id)}}"
                                            class="form-control btn-primary btn keyword-question-input-submit">
                                        {{_i('Add')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row p-h-lg">
                            <div data-question-theme="{{$idea->id}}"
                                 id="wrapper-{{str_replace(' ', '', $idea->id)}}"
                                 class="col-xs-12 col-sm-12 col-md-12 col-lg-12 keywords-wrapper">
                                @foreach($keywords_questions as $keyword)
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                        <div class="i-checks">
                                            <label>
                                                <input class="keywords-checkbox"
                                                       {{($keyword->accepted)?'checked':''}}
                                                       type="checkbox"
                                                       name="keywords_questions[{{$idea->id}}][{{$keyword->text}}]">
                                                <i></i>
                                                <i></i>
                                                {{ucfirst($keyword->text)}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




