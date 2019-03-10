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
<script>
    if( navigator.geolocation ){    // 現在位置を取得できる場合の処理
        // 現在位置を取得する
        navigator.geolocation.getCurrentPosition( success, error, option);
        /*現在位置が取得できた時に実行*/
        function success(position){
            var data = position.coords;
            // 必要な緯度経度だけ取得
            var lat = data.latitude;
            var lng = data.longitude;
            // alert("緯度:" + lat + "  軽度:" + lng);
                //Google Mapsで住所を取得
                var geocoder = new google.maps.Geocoder();
                latlng = new google.maps.LatLng(lat, lng);
                geocoder.geocode({'latLng': latlng}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        document.getElementById('address').innerHTML = results[0].formatted_address;
                        alert("緯度:" + lat + "  軽度:" + lng);
                    }
                    else {
                        alert("エラー" + status);
                    }
                });
            }
            /*現在位置の取得に失敗した時に実行*/
            function error(error){
                var errorMessage = {
                    0: "原因不明のエラーが発生しました。",
                    1: "位置情報が許可されませんでした。",
                    2: "位置情報が取得できませんでした。",
                    3: "タイムアウトしました。",
                } ;
                //とりあえずalert
                alert( errorMessage[error.code]);
            }
            // オプション(省略可)
            var option = {
                "enableHighAccuracy": false,
                "timeout": 100 ,
                "maximumAge": 100 ,
            } ;
        } else {// 現在位置を取得できない場合の処理
        //とりあえずalert
            alert("あなたの端末では、現在位置を取得できません。");
        }
</script>
@endsection
