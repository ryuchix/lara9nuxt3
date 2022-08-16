<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnTypeInSeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seos', function (Blueprint $table) {
            $table->text('website_name')->nullable()->change();
            $table->text('website_description')->nullable()->change();
            $table->text('website_keywords')->nullable()->change();
            $table->text('website_logo')->nullable()->change();
            $table->text('website_image')->nullable()->change();

            $table->text('schema_person_name')->nullable()->change();
            $table->text('schema_person_firstname')->nullable()->change();
            $table->text('schema_person_lastname')->nullable()->change();
            $table->text('schema_person_description')->nullable()->change();
            $table->text('schema_person_image')->nullable()->change();
            $table->text('schema_person_websites')->nullable()->change();

            $table->text('schema_business_name')->nullable()->change();
            $table->text('schema_business_image')->nullable()->change();
            $table->text('schema_business_description')->nullable()->change();
            $table->text('schema_business_street')->nullable()->change();
            $table->text('schema_business_post_office')->nullable()->change();
            $table->text('schema_business_locality')->nullable()->change();
            $table->text('schema_business_region')->nullable()->change();
            $table->text('schema_business_postal')->nullable()->change();
            $table->text('schema_business_country')->nullable()->change();
            $table->text('schema_business_latitude')->nullable()->change();
            $table->text('schema_business_longitude')->nullable()->change();
            $table->text('schema_business_google_map')->nullable()->change();

            $table->text('sell_title')->nullable()->change();
            $table->text('sell_description')->nullable()->change();
            $table->text('sell_keywords')->nullable()->change();
            $table->text('sell_image')->nullable()->change();

            $table->text('agents_title')->nullable()->change();
            $table->text('agents_description')->nullable()->change();
            $table->text('agents_keywords')->nullable()->change();
            $table->text('agents_image')->nullable()->change();

            $table->text('listing_state_title')->nullable()->change();
            $table->text('listing_state_description')->nullable()->change();
            $table->text('listing_state_keywords')->nullable()->change();
            $table->text('listing_state_image')->nullable()->change();

            $table->text('listing_city_title')->nullable()->change();
            $table->text('listing_city_description')->nullable()->change();
            $table->text('listing_city_keywords')->nullable()->change();
            $table->text('listing_city_image')->nullable()->change();

            $table->text('property_title')->nullable()->change();
            $table->text('property_description')->nullable()->change();
            $table->text('property_keywords')->nullable()->change();

            $table->text('contact_us_title')->nullable()->change();
            $table->text('contact_us_description')->nullable()->change();
            $table->text('contact_us_keywords')->nullable()->change();
            $table->text('contact_us_image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seos', function (Blueprint $table) {
            //
        });
    }
}
