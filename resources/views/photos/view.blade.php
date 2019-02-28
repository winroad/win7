@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <a href="/home" class="btn btn-primary">Topへ</a>
                <a href="{{ url('photo/turn',$photo->id) }}" class="btn btn-success">回転</a>
                <a href="{{ url('photo/delete',$photo->id) }}" class="btn btn-danger">削除</a>
            </div>
            <div class="card-body">
                <div class="col no-gutters text-center">
                    <img class="img-fluid" src="{{ asset('storage/images/'.$photo->name) }}">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
