<?php
namespace App\Http\Controllers;
use App\Models\Painting;
use App\Services\PaintingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class PaintingController extends Controller {
 public function index() { return view('paintings.index', ['paintings' => Painting::orderBy('id')->get()]); }
 public function create() { return view('paintings.form', ['painting' => new Painting]); }
 public function show(Painting $painting) { return view('paintings.show', compact('painting')); }
 public function edit(Painting $painting) { return view('paintings.form', compact('painting')); }
 public function image(Painting $painting) {
  abort_unless(Storage::disk('public')->exists($painting->image_path), 404);
  return response()->file(Storage::disk('public')->path($painting->image_path));
 }
 private function validated(Request $request, bool $creating): array {
  return $request->validate([
   'title' => ['required','string','max:255'], 'artist' => ['required','string','max:255'],
   'creation_period' => ['required','string','max:100'], 'museum' => ['required','string','max:255'],
   'description' => ['required','string','max:5000'], 'technique' => ['required','string','max:100'],
   'technique_note' => ['required','string','max:1000'],
   'acquired_on' => ['required','date_format:Y-m-d','before_or_equal:today','after_or_equal:1900-01-01'],
   'image' => [$creating ? 'required' : 'nullable','image','mimes:jpg,jpeg,png,webp','max:8192','dimensions:max_width=5000,max_height=5000'],
  ], [
   'required' => 'Заполните поле «:attribute».', 'max' => 'Превышен допустимый размер поля «:attribute».',
   'image' => 'Выберите изображение.', 'mimes' => 'Допустимы JPEG, PNG и WebP.',
   'dimensions' => 'Размер изображения не должен превышать 5000 × 5000 пикселей.',
   'date_format' => 'Введите корректную дату.', 'before_or_equal' => 'Дата не должна быть в будущем.',
   'after_or_equal' => 'Дата должна быть не ранее 01.01.1900.',
  ], ['title'=>'Название','artist'=>'Автор','creation_period'=>'Период создания','museum'=>'Музей','description'=>'Описание','technique'=>'Техника','technique_note'=>'Пояснение','acquired_on'=>'Дата добавления в каталог','image'=>'Изображение']);
 }
 public function store(Request $request, PaintingImage $images) {
  $data = $this->validated($request, true); unset($data['image']);
  $path = $images->store($request->file('image')); $data['image_path'] = $path;
  try { $painting = Painting::create($data); }
  catch (\Throwable $error) { Storage::disk('public')->delete($path); throw $error; }
  return redirect()->route('paintings.show', $painting)->with('status', 'Картина добавлена.');
 }
 public function update(Request $request, Painting $painting, PaintingImage $images) {
  $data = $this->validated($request, false); unset($data['image']);
  $old = $painting->image_path; $new = null;
  if ($request->hasFile('image')) { $new = $images->store($request->file('image')); $data['image_path'] = $new; }
  try { $painting->update($data); }
  catch (\Throwable $error) { if ($new) Storage::disk('public')->delete($new); throw $error; }
  if ($new && $old !== $new) Storage::disk('public')->delete($old);
  return redirect()->route('paintings.show', $painting)->with('status', 'Изменения сохранены.');
 }
 public function destroy(Painting $painting) {
  $painting->delete();
  return redirect()->route('paintings.index')->with('status', 'Картина удалена из каталога.');
 }
}
