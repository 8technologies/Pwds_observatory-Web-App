<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_providers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("registration_number")->nullable();
            $table->string("date_of_registration")->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->text("brief_profile")->nullable();

            $table->string("physical_address")->nullable();
            $table->json('attachments')->nullable();
            $table->string('logo')->nullable();
            $table->string('license')->nullablle();
            $table->string('certificate_of_registration')->nullable();
            $table->boolean('is_verified')->default(false);

            $table->string('email')->nullable();
            $table->text('telephone')->nullable();
            $table->text('services_offered')->nullable();
            $table->text('districts_of_operation')->nullable();
            $table->string('level_of_operation')->nullable();
            $table->text('mission')->nullable();
            $table->text('postal_address')->nullable();
            $table->string('target_group')->nullable();
            $table->string('disability_category')->nullable();
            $table->text('affiliated_organizations')->nullable();


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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('service_providers');
        Schema::enableForeignKeyConstraints();
    }
}
