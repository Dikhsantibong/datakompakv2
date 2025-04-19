<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meeting_shifts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('current_shift', ['A', 'B', 'C', 'D']);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('meeting_shift_machine_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_shift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('machine_id')->constrained();
            $table->set('status', ['operasi', 'standby', 'har_rutin', 'har_nonrutin', 'gangguan']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('meeting_shift_auxiliary_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_shift_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->set('status', ['normal', 'abnormal', 'gangguan', 'flm']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('meeting_shift_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_shift_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('category');
            $table->enum('status', ['0-20', '21-40', '41-61', '61-80', 'up-80']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('meeting_shift_k3ls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_shift_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['unsafe_action', 'unsafe_condition']);
            $table->text('uraian');
            $table->text('saran');
            $table->string('eviden_path')->nullable();
            $table->timestamps();
        });

        Schema::create('meeting_shift_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_shift_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['sistem', 'umum']);
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('meeting_shift_resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_shift_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('meeting_shift_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_shift_id')->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->enum('shift', ['A', 'B', 'C', 'D']);
            $table->enum('status', ['hadir', 'izin', 'sakit', 'cuti', 'alpha']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meeting_shift_attendances');
        Schema::dropIfExists('meeting_shift_resumes');
        Schema::dropIfExists('meeting_shift_notes');
        Schema::dropIfExists('meeting_shift_k3ls');
        Schema::dropIfExists('meeting_shift_resources');
        Schema::dropIfExists('meeting_shift_auxiliary_equipment');
        Schema::dropIfExists('meeting_shift_machine_statuses');
        Schema::dropIfExists('meeting_shifts');
    }
}; 