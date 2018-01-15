@extends('webImport.layouts.app')


@section('main_container')

    <div class="container">

        <h1>Web Import</h1>
        <div class="bd-example">
            <form method="post" action="{{url('webimport/import')}}">
                {{ csrf_field() }}
                @include('webImport.forms.dropdown')
                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>


    </div>

@endsection


