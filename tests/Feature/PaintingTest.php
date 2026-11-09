<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\Painting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class PaintingTest extends TestCase {
 use RefreshDatabase;
 private function data(): array { return ['title'=>'  Новая картина  ','artist'=>'  Художник  ','creation_period'=>'2020','museum'=>'Галерея','description'=>'Описание картины','technique'=>'Масло','technique_note'=>'Масляные краски','acquired_on'=>'2026-01-01']; }
 public function test_seed_and_all_pages(): void {
  $this->seed(); $this->assertDatabaseCount('paintings', 5);
  $this->get('/')->assertRedirect('/paintings');
  $this->get('/paintings')->assertOk()->assertSee('Мона Лиза');
  $this->get('/paintings/create')->assertOk();
  foreach (Painting::all() as $p) {
   $this->get(route('paintings.show',$p))->assertOk()->assertSee($p->title);
   $this->get(route('paintings.edit',$p))->assertOk()->assertSee('Редактирование');
   $this->get(route('paintings.image',$p))->assertOk();
  }
  $this->seed(); $this->assertDatabaseCount('paintings',5);
 }
 public function test_full_crud_and_image_replacement(): void {
  Storage::fake('public');
  $this->post('/paintings',$this->data()+['image'=>UploadedFile::fake()->image('test.png',2000,1000)])->assertRedirect();
  $p=Painting::firstOrFail(); $old=$p->image_path;
  $this->assertSame('Новая картина',$p->title);
  $this->assertSame('Художник',$p->artist);
  $this->assertSame('2026-01-01',$p->acquired_on->format('Y-m-d'));
  $size=getimagesize(Storage::disk('public')->path($old));
  $this->assertSame(1400,$size[0]); $this->assertSame(700,$size[1]);
  $data=$this->data();$data['title']='Изменено';
  $this->put(route('paintings.update',$p),$data)->assertRedirect();
  $this->assertSame($old,$p->fresh()->image_path);
  $this->put(route('paintings.update',$p),$data+['image'=>UploadedFile::fake()->image('new.jpg',600,900)])->assertRedirect();
  $p->refresh();Storage::disk('public')->assertMissing($old);Storage::disk('public')->assertExists($p->image_path);
  $this->delete(route('paintings.destroy',$p))->assertRedirect('/paintings');
  $this->assertSoftDeleted('paintings',['id'=>$p->id]);
  $this->get(route('paintings.show',$p))->assertNotFound();
  $this->get(route('paintings.image',$p))->assertNotFound();
  $this->get('/paintings')->assertDontSee('Изменено');
 }
 public function test_server_validation(): void {
  $this->post('/paintings',[])->assertSessionHasErrors(['title','artist','image','acquired_on']);
  $data=$this->data();$data['acquired_on']='2099-01-01';
  $this->post('/paintings',$data+['image'=>UploadedFile::fake()->create('bad.txt',1,'text/plain')])->assertSessionHasErrors(['acquired_on','image']);
  $this->assertDatabaseCount('paintings',0);
 }
 public function test_date_mutator_normalizes_timestamp(): void {
  $p=new Painting($this->data()+['image_path'=>'paintings/test.jpg']);
  $p->acquired_on='2026-01-02 14:15:16';
  $this->assertSame('2026-01-02',$p->getAttributes()['acquired_on']);
 }
}
