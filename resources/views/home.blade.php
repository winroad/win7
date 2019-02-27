@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    あなたはログインしています。
                    <form method="post" action="{{ url('avatar/upload') }}" enctype="multipart/form-data">
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
                        <div class="form-group">
                            @if($user->avatar_filename)
                                <p>
                                    <img src="{{ asset('storage/avatar/'.$user->avatar_filename) }}" alt="avatar">
                                </p>
                            @endif
                                <label for="file" class="control-label">画像アップロード</label>
                                <input type="file" name="file">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="アップロード" class="btn btn-primary">
                        </div>
                    </form>

                    <form method="post" action="{{ url('avatar/up') }}" enctype="multipart/form-data">
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
                        <div class="form-group">
                            @if($user->avatar_filename)
                                <p>
                                    <img src="{{ asset('storage/avatar/'.$user->avatar_filename) }}" alt="avatar">
                                </p>
                            @endif
                                <label for="file" class="control-label">リサイズ画像アップ</label>
                                <input type="file" name="file">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="アップロード" class="btn btn-primary">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
