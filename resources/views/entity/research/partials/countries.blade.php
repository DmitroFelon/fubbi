<datalist id="countries">
    @foreach(config('fubbi.countries', []) as $code => $country)
        <option value="{{$country}}">
    @endforeach
</datalist>
