@extends('layouts.app')
@section('title', $painting->title)
@section('content')
<a href="{{ route('paintings.index') }}" class="d-inline-block mb-3">← Вся коллекция</a>
<article class="bg-white rounded p-4"><div class="row g-4 align-items-start"><div class="col-lg-7"><img class="img-fluid rounded detail-image" src="{{ route('paintings.image', $painting) }}" alt="{{ $painting->title }}"></div>
<div class="col-lg-5"><h1>{{ $painting->title }}</h1><dl><dt>Автор</dt><dd>{{ $painting->artist }}</dd><dt>Период создания</dt><dd>{{ $painting->creation_period }}</dd><dt>Собрание</dt><dd>{{ $painting->museum }}</dd><dt>Добавлено в каталог</dt><dd>{{ $painting->acquired_on->format('d.m.Y') }}</dd></dl>
<p class="description">{{ $painting->description }}</p><p>Техника: <button class="btn btn-link p-0 align-baseline term" type="button" data-bs-toggle="popover" data-bs-title="{{ $painting->technique }}" data-bs-content="{{ $painting->technique_note }}">{{ $painting->technique }}</button></p>
<div class="d-flex gap-2 flex-wrap"><a class="btn btn-primary" href="{{ route('paintings.edit', $painting) }}">Редактировать</a>
<form method="post" action="{{ route('paintings.destroy', $painting) }}" class="delete-form">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger">Удалить</button></form></div></div></div></article>
@endsection
