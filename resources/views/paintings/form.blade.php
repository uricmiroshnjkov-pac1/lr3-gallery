@extends('layouts.app')
@section('title', $painting->exists ? 'Редактирование картины' : 'Добавление картины')
@section('content')
<h1 class="mb-4">{{ $painting->exists ? 'Редактирование картины' : 'Добавление картины' }}</h1>
@if($errors->any())<div class="alert alert-danger" role="alert"><strong>Проверьте введённые данные.</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form class="bg-white rounded p-4 painting-form" method="post" enctype="multipart/form-data" action="{{ $painting->exists ? route('paintings.update', $painting) : route('paintings.store') }}">
@csrf @if($painting->exists) @method('PUT') @endif
<div class="row g-3">
@foreach(['title'=>['Название',255], 'artist'=>['Автор',255], 'creation_period'=>['Период создания',100], 'museum'=>['Музей или собрание',255], 'technique'=>['Техника',100]] as $name => [$label,$length])
<div class="col-md-6"><label for="{{ $name }}" class="form-label">{{ $label }}</label><input class="form-control @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $painting->$name) }}" maxlength="{{ $length }}" required>@error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
@endforeach
<div class="col-md-6"><label for="acquired_on" class="form-label">Дата добавления в каталог</label><input type="date" id="acquired_on" name="acquired_on" class="form-control @error('acquired_on') is-invalid @enderror" value="{{ old('acquired_on', $painting->acquired_on?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" min="1900-01-01" max="{{ now()->format('Y-m-d') }}" required>@error('acquired_on')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
@foreach(['description'=>['Описание',5000], 'technique_note'=>['Пояснение к технике',1000]] as $name => [$label,$length])
<div class="col-12"><label for="{{ $name }}" class="form-label">{{ $label }}</label><textarea class="form-control @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" rows="{{ $name === 'description' ? 5 : 2 }}" maxlength="{{ $length }}" required>{{ old($name, $painting->$name) }}</textarea>@error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
@endforeach
<div class="col-12"><label for="image" class="form-label">Изображение</label><input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" accept="image/jpeg,image/png,image/webp" @required(!$painting->exists) aria-describedby="image-help"><div class="form-text" id="image-help">JPEG, PNG или WebP, до 8 МБ и 5000 × 5000 px. При редактировании можно оставить прежнюю картинку.</div>@error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
@if($painting->exists)<img class="img-fluid rounded mt-3 preview-image" src="{{ route('paintings.image', $painting) }}" alt="Текущее изображение">@endif</div>
</div><div class="d-flex gap-2 mt-4"><button class="btn btn-primary" type="submit">Сохранить</button><a class="btn btn-outline-secondary" href="{{ $painting->exists ? route('paintings.show', $painting) : route('paintings.index') }}">Отмена</a></div></form>
@endsection
