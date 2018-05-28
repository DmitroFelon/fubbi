@extends('master')

@section('content')
    <div style="word-wrap: break-word">
        <h3>{{ $idea->theme }}</h3>
    </div>
    <form method="post" action="{{ route('updateIdea', ['idea' => $idea]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="project_id" value="{{ $idea->project_id }}">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group{{ $errors->has('article_format_type') ? ' has-error' : '' }}">
                <label for="article_format_type">Article format type</label>
                <textarea type="text" id="article_format_type" class="form-control" name="article_format_type" rows="3">{{ is_null(old('article_format_type')) ? $idea->article_format_type : old('article_format_type') }}</textarea>
                @if ($errors->has('article_format_type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('article_format_type') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group{{ $errors->has('link_to_model_article') ? ' has-error' : '' }}">
                <label for="link_to_model_article">Link to model article</label>
                <textarea type="text" id="link_to_model_article" class="form-control" name="link_to_model_article" rows="3">{{ is_null(old('link_to_model_article')) ? $idea->link_to_model_article : old('link_to_model_article') }}</textarea>
                @if ($errors->has('link_to_model_article'))
                    <span class="help-block">
                        <strong>{{ $errors->first('link_to_model_article') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group{{ $errors->has('references') ? ' has-error' : '' }}">
                <label for="references">Link to case study/data/References</label>
                <textarea type="text" id="references" class="form-control" name="references" rows="3">{{ is_null(old('references')) ? $idea->references : old('references') }}</textarea>
                @if ($errors->has('references'))
                    <span class="help-block">
                        <strong>{{ $errors->first('references') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group{{ $errors->has('points_covered') ? ' has-error' : '' }}">
                <label for="touch_on">Points to touch on</label>
                <textarea type="text" id="touch_on" class="form-control" name="points_covered" rows="3">{{ is_null(old('points_covered')) ? $idea->points_covered : old('points_covered') }}</textarea>
                @if ($errors->has('points_covered'))
                    <span class="help-block">
                        <strong>{{ $errors->first('points_covered') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group{{ $errors->has('points_avoid') ? ' has-error' : '' }}">
                <label for="avoid">Points to avoid</label>
                <textarea type="text" id="avoid" class="form-control" name="points_avoid" rows="3">{{ is_null(old('points_avoid')) ? $idea->points_avoid : old('points_avoid') }}</textarea>
                @if ($errors->has('points_avoid'))
                    <span class="help-block">
                        <strong>{{ $errors->first('points_avoid') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group{{ $errors->has('additional_notes') ? ' has-error' : '' }}">
                <label for="notes">Additional notes</label>
                <textarea type="text" id="notes" class="form-control" name="additional_notes" rows="3">{{ is_null(old('additional_notes')) ? $idea->additional_notes : old('additional_notes') }}</textarea>
                @if ($errors->has('additional_notes'))
                    <span class="help-block">
                        <strong>{{ $errors->first('additional_notes') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
        </div>
    </form>

@endsection
