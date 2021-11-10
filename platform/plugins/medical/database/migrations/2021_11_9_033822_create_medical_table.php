<?php

use Botble\ACL\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMedicalTable extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med_services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('med_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 255);
            $table->string('notes',255)->nullable();
            $table->string('address_id', 255);
            $table->string('image_file', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->string('status', 60)->default('pending');
            $table->timestamps();
        });
        Schema::create('med_nursing_servicrs', function (Blueprint $table) {
            $table->id();
            $table->string('p_name', 255);
            $table->string('p_age',255)->nullable();
            $table->string('p_sex', 255);
            $table->string('doctor_id', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('attachedFile', 255)->nullable();
            $table->string('user_id', 255)->nullable();
            $table->string('status', 60)->default('pending');
            $table->timestamps();
        });
        Schema::create('med_selected_services', function (Blueprint $table) {
            $table->id();
            $table->string('nursing_servicrs_id', 255);
            $table->string('services_id',255);

        });
      
        Schema::create('med_doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('phone',255)->nullable();
            $table->string('email', 255);
            $table->string('address', 255)->nullable();
            $table->string('specialty_id', 255)->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });
        Schema::create('med_specialties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('status', 60)->default('published');
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
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('services');
        Schema::dropIfExists('nursing_servicrs');
        Schema::dropIfExists('selected_services');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('specialties');
    }
}
