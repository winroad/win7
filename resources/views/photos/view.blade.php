@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <a href="/home" class="btn btn-primary">Topへ</a>
                <a href="#edit" data-toggle="modal" class="btn btn-success">修正</a>
{{--                <a href="{{ url('photo/delete',$base->photo->id) }}" class="btn btn-danger">削除</a>--}}
            </div>
            <div class="card-body">
                <div class="col no-gutters text-center">
                    <img class="img-fluid" src="{{ asset('storage/images/'.$base->photo->name) }}">
                </div>
            </div>
            <div class="card-footer">
                <table class="table">
                    <tr>
                        <th>{{ round($base->size / 1000000,3) }} M</th>
                        <th>{{ $base->width }} ☓　{{ $base->height }}</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- モーダル・ダイアログ -->
<div class="modal fade" id="edit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                <h4 class="modal-title">画像の修正</h4>
            </div>
            <div class="modal-body">
                <p>画像を修正します。</p>
                <p>修正すると元に戻せませんので、慎重にボタンをクリックして下さい。</p>
                <p>なお、画像キャッシュの関係ですぐには反映されませんが、画像の修正は完了しています</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                {{--<button type="button" class="btn btn-primary">ボタン</button>--}}
                <a href="{{ url('photo/turn',[$base->id,1]) }}" class="btn btn-primary">右回転</a>
                <a href="{{ url('photo/turn',[$base->id,2]) }}" class="btn btn-success">左回転</a>
                <a href="{{ url('photo/turn',[$base->id,3]) }}" class="btn btn-warning">上下反転</a>
                <a href="{{ url('photo/delete',$base->photo->id) }}" class="btn btn-danger">削除</a>
            </div>
        </div>
    </div>
</div>
@endsection
