@extends('layouts.app')
@section('content')
<h1 class="mb-2">Шедевры мировой живописи</h1><p class="text-secondary mb-4">Пять историй искусства и ваша собственная коллекция.</p>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 row-cols-wide-5 g-4 gallery">
@forelse($paintings as $painting)<div class="col"><article class="card h-100 border-0">
<a href="{{ route('paintings.show', $painting) }}"><img class="card-img-top img-fluid painting-image" src="{{ route('paintings.image', $painting) }}" alt="{{ $painting->title }}" loading="lazy"></a>
<div class="card-body d-flex flex-column"><h2 class="h5"><a class="text-reset text-decoration-none" href="{{ route('paintings.show', $painting) }}">{{ $painting->title }}</a></h2>
<p class="mb-1 fw-bold">{{ $painting->artist }}</p><p class="small text-secondary">{{ $painting->creation_period }}</p><p class="small">{{ Str::limit($painting->description, 130) }}</p>
<a class="btn btn-outline-primary mt-auto" href="{{ route('paintings.show', $painting) }}">Подробнее</a></div></article></div>
@empty<div class="col-12"><p>В коллекции пока нет картин. Добавьте первую.</p></div>@endforelse</div>
@endsection
