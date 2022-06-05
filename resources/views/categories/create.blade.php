@extends('layouts.app')

@section('content')
    <div class="card card-default">
        <div class="card-header">Create Category</div>
        <div class="card-body">
            <form action="">
                <div class="form-group">
                    <label for="name">
                        <input type="text" id="name" class="form-control" name="name">
                    </label>
                    <div class="form-group">
                        <button class="btn btn-success">Add Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection