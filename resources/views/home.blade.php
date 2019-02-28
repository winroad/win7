@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">画像一覧</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($photos)
                        @foreach($photos as $photo)
                        <a href="photo/view/{{ $photo->id }}">
                            <img src="{{ asset('storage/thumbnails/'.$photo->name) }}" alt="thumbnail">
                        </a>
                        @endforeach
                    @endif
                    <form method="post" action="{{ url('photo/upload') }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <hr>
                        <div class="form-group">
                                <input type="file" name="file">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="画像のアップロード" class="btn btn-primary">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
