<!doctype html>
<html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Картинная галерея')</title><link rel="stylesheet" href="{{ mix('css/app.css') }}"><script defer src="{{ mix('js/app.js') }}"></script></head>
<body><div class="container py-4"><header class="bg-white rounded p-3 mb-4"><nav class="navbar flex-wrap gap-3" aria-label="Основная навигация">
<a class="navbar-brand fw-bold" href="{{ route('paintings.index') }}">🖌 Картинная галерея</a>
<ul class="nav gap-2"><li class="nav-item"><a class="nav-link" href="{{ route('paintings.index') }}">Коллекция</a></li><li class="nav-item"><a class="btn btn-primary" href="{{ route('paintings.create') }}">Добавить</a></li></ul></nav></header>
<main>@if(session('status'))<div class="alert alert-success" role="status">{{ session('status') }}</div>@endif
@yield('content')</main><footer class="border-top mt-5 pt-3 text-secondary">Мирошников Иван · Картинная галерея · ЛР3</footer></div></body></html>
