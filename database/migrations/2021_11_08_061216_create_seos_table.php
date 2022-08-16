<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seos', function (Blueprint $table) {
            $table->id();

            $table->string('website_name')->nullable();
            $table->string('website_description')->nullable();
            $table->string('website_keywords')->nullable();
            $table->string('website_logo')->nullable();
            $table->string('website_image')->nullable();

            $table->string('schema_person_name')->nullable();
            $table->string('schema_person_firstname')->nullable();
            $table->string('schema_person_lastname')->nullable();
            $table->string('schema_person_description')->nullable();
            $table->string('schema_person_image')->nullable();
            $table->string('schema_person_websites')->nullable();

            $table->string('schema_business_name')->nullable();
            $table->string('schema_business_image')->nullable();
            $table->string('schema_business_description')->nullable();
            $table->string('schema_business_street')->nullable();
            $table->string('schema_business_post_office')->nullable();
            $table->string('schema_business_locality')->nullable();
            $table->string('schema_business_region')->nullable();
            $table->string('schema_business_postal')->nullable();
            $table->string('schema_business_country')->nullable();
            $table->string('schema_business_latitude')->nullable();
            $table->string('schema_business_longitude')->nullable();
            $table->string('schema_business_google_map')->nullable();

            $table->string('sell_title')->nullable();
            $table->string('sell_description')->nullable();
            $table->string('sell_keywords')->nullable();
            $table->string('sell_image')->nullable();

            $table->string('agents_title')->nullable();
            $table->string('agents_description')->nullable();
            $table->string('agents_keywords')->nullable();
            $table->string('agents_image')->nullable();

            $table->string('listing_state_title')->nullable();
            $table->string('listing_state_description')->nullable();
            $table->string('listing_state_keywords')->nullable();
            $table->string('listing_state_image')->nullable();

            $table->string('listing_city_title')->nullable();
            $table->string('listing_city_description')->nullable();
            $table->string('listing_city_keywords')->nullable();
            $table->string('listing_city_image')->nullable();

            $table->string('property_title')->nullable();
            $table->string('property_description')->nullable();
            $table->string('property_keywords')->nullable();

            $table->string('contact_us_title')->nullable();
            $table->string('contact_us_description')->nullable();
            $table->string('contact_us_keywords')->nullable();
            $table->string('contact_us_image')->nullable();

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
        Schema::dropIfExists('seos');
    }
}
