{{-- 
@extends ('layouts.app')
@section('content');
<div class="container">
    <h1> </h1>
    <form action={{'image.upload'}} method="POST" enctype="multipart/form-data">
        {{ csrf_field()}}
        <div>
            <input type="file" name="image">
            <button class="btn btn_default" type="submit">Загрузка</button>
        </div>
    </form>
    @isset ($path)
    <img class="img-fluid" src="{{asset('/storage/' . $path)}}" alt="">
    @endisset
</div>
@endsection --}}