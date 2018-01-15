
    @foreach($columns as $column)
    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-2 col-form-label">{{strtoupper($column->Field)}}</label>
        <div class="col-sm-10">
            <select name="input[{{$column->Field}}]">
                <option value="null">null</option>
                @foreach($fields as $field)
                    <option {{$field==$column->Field?"selected":""}}>{{$field}}</option>
                @endforeach
            </select>
        </div>
    </div>

@endforeach

