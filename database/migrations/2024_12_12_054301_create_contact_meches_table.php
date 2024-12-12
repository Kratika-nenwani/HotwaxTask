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
        Schema::create('contact_meches', function (Blueprint $table) {
            $table->id('contact_mech_id');
            $table->unsignedBigInteger('customer_id');
            $table->string("street_address");
            $table->string("city");
            $table->string("state");
            $table->string("phone_number")->nullable();
            $table->string("email")->nullable();
            $table->timestamps();

            
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_meches');
    }
};
