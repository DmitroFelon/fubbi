<form action="">
    @for($i = 0; $i < 5; $i ++)
        <label for="socialpost-{{$i}}">{{_i('Social post')}}</label>
        <textarea class="form-control" name="socialpost[]" id="socialpost-{{$i}}" cols="30"
                  rows="5">@isset($article->getMeta('socialposts')[$i]){{$article->getMeta('socialposts')[$i]}}@endisset</textarea>
    @endfor
    <button type="submit" class="btn btn-primary">{{_i('Save Social Posts')}}</button>
</form>