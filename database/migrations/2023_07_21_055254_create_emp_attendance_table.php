<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_attendance', function (Blueprint $table) {
            $table->id();
            $table->date('leave_date');
            //$table->date('to_leave_date');
            $table->string('leave_day');
            $table->unsignedBigInteger('emp_id');
            $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
            $table->enum('active', ['1', '0'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emp_attendance');
    }
}
