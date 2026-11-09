<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
class Painting extends Model {
 use SoftDeletes;
 protected $fillable = ['title','artist','creation_period','museum','description','technique','technique_note','acquired_on','image_path'];
 protected function casts(): array { return ['acquired_on' => 'date']; }
 public function setTitleAttribute(string $value): void { $this->attributes['title'] = trim($value); }
 public function setArtistAttribute(string $value): void { $this->attributes['artist'] = trim($value); }
 public function setAcquiredOnAttribute($value): void {
  $this->attributes['acquired_on'] = Carbon::parse($value)->format('Y-m-d');
 }
}
