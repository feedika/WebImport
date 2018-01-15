@extends('webImport.layouts.app')


@section('main_container')


    <div class="container">

        <h1>Web Import</h1>
        <p class="lead">Easy import data with csv file to database table.</p>

        <form method="post" enctype="multipart/form-data" action="{{url("webimport")}}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="exampleInputEmail1">File</label>
                <input type="file" name="inputFile" class="form-control">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.
                </small>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Example select</label>
                <select class="form-control" name="table_name">
                    @foreach($database_tables as $database_table)
                        <option>{{$database_table}}</option>
                    @endforeach
                </select>
            </div>


            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

    </div>





@endsection
