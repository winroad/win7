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
                    @if($thumbs)
                        @foreach($thumbs as $thumb)
                        <a href="{{ url('photo/view',$thumb->photo_id) }}">
                            <img src="{{ asset('storage/thumbnails/'.$thumb->photo->name) }}" alt="thumbnail">
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
                        {{ $thumbs->links() }}
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
    <div class="container">
        <h1 style="text-align:center;color:#d36015;">[getCurrentPosition()]のサンプルデモ (Geolocation API)</h1>
        <p>Geolocation APIの[getCurrentPosition()]を利用して、ユーザーの現在位置を取得するサンプルデモです。</p>

        <HR style="margin: 3em 0 ;">

        <h2>取得したデータ</h2>

        <p>下記の位置情報を取得することができました。</p>

        <dl id="result"></dl>

        <h2>地図</h2>

        <p>Google Mapsに、位置情報を反映させたものです。</p>

        <div class="map-wrapper">
            <div id="map-canvas"></div>
        </div>


        <HR style="margin: 3em 0 ;">



        {{--<p style="text-align:center"><a href="https://syncer.jp/how-to-use-geolocation-api">配布元: Syncer</a></p>--}}




        <!-- JavaScriptの読み込み -->
        {{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7Pxqp7Asgi6D4eOtkCxnir5xPiIwY3EE"></script>--}}
        {{--<script src="{{ asset('js/gps.jp') }}"></script>--}}
        {{--<script src="get-current-position.js"></script>--}}
    </div>

@endsection
