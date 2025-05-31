<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('client');
            $table->string('task_bug_name'); // Task/Bug Name
            $table->string('owner');
            $table->enum('priority', ['Low', 'Medium', 'High']);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('dev_status',['New', 'In-progress', 'Completed', 'On-hold','NA']);
            $table->enum('unit_test_status',['New', 'In-progress', 'Completed', 'On-hold','NA']);
            $table->enum('staging_status',['New', 'In-progress', 'Completed', 'On-hold','NA']);
            $table->enum('prod_status',['New', 'In-progress', 'Completed', 'On-hold','NA']);
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
