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
        Schema::create('order_headers', function (Blueprint $table) {
            $table->id('order_id');
            $table->date('order_date');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('shipping_contact_mech_id');
            $table->unsignedBigInteger('billing_contact_mech_id');
            $table->timestamps();



            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->foreign('shipping_contact_mech_id')->references('contact_mech_id')->on('contact_meches')->onDelete('cascade');
            $table->foreign('billing_contact_mech_id')->references('contact_mech_id')->on('contact_meches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_headers');
    }
};
