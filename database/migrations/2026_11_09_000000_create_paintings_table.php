<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('paintings', function (Blueprint $table) {
   $table->id(); $table->string('title'); $table->string('artist');
   $table->string('creation_period', 100); $table->string('museum');
   $table->text('description'); $table->string('technique', 100);
   $table->text('technique_note'); $table->date('acquired_on');
   $table->string('image_path'); $table->timestamps(); $table->softDeletes();
  });
 }
 public function down(): void { Schema::dropIfExists('paintings'); }
};
