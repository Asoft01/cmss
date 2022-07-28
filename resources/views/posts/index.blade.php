@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-end my-2">
    <a href="{{ route('posts.create') }}" class="btn btn-success float-right">Add Post</a>
 </div>

 <div class="card card-default">
    <div class="card-header">
        <div class="card-body">

        </div>
    </div>
 </div>
@endsection