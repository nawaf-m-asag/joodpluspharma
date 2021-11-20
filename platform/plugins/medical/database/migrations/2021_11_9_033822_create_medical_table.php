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
        Schema::create('med_laboratories', function (Blueprint $table) {
            $table->id();
            $table->string('lab_name', 255);
            $table->string('address',255);
            $table->string('phone', 255);
            $table->string('email', 255);;
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });
        Schema::create('med_consulting', function (Blueprint $table) {
            $table->id();
            $table->string('con_type', 255);
            $table->string('specialty_id',255);
            $table->string('doctor_id', 255);
            $table->string('p_name', 255);
            $table->string('p_age', 255)->nullable();
            $table->string('p_sex', 255)->nullable();
            $table->string('female_status', 255)->nullable();
            $table->string('chronic_diseases', 255)->nullable();
            $table->string('operations', 255)->nullable();
            $table->string('medicines', 255)->nullable();
            $table->string('desc_situation', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->string('user_id', 255)->nullable();
            $table->string('status', 60)->default('pending');
            $table->timestamps();
        });
        Schema::create('med_examinations', function (Blueprint $table) {
            $table->id();
            $table->string('p_name', 255);
            $table->string('p_age',255);
            $table->string('p_sex', 255);
            $table->string('d_name', 255);
            $table->string('address', 255)->nullable();
            $table->string('lab_id', 255)->nullable();
            $table->string('required_checks', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->string('user_id', 255)->nullable();
            $table->string('status', 60)->default('pending');
            $table->timestamps();
        });
        
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
            $table->string('services_id', 255)->nullable();
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
        
        Schema::create('med_maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('side_name', 255);
            $table->string('applicant_name',255)->nullable();
            $table->string('phone', 255);
            $table->string('address', 255)->nullable();
            $table->string('device_name', 255)->nullable();
            $table->string('descrip_defect', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->string('user_id', 255)->nullable();
            $table->string('status', 60)->default('pending');
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
        Schema::dropIfExists('med_maintenance');
        Schema::dropIfExists('med_consulting');
        Schema::dropIfExists('med_examinations');
        Schema::dropIfExists('med_laboratories');
        
    }
}
