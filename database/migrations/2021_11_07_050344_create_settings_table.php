<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('home_hero_heading')->nullable();
            $table->text('home_search_placeholder')->nullable();
            $table->text('home_first_secton_heading')->nullable();
            $table->text('home_first_secton_body')->nullable();
            $table->text('home_content_heading')->nullable();
            $table->text('home_content_body')->nullable();
            $table->text('home_featured_city_heading')->nullable();
            $table->text('home_last_section_heading')->nullable();
            $table->text('home_last_section_body')->nullable();

            $table->text('sell_first_section_heading')->nullable();
            $table->text('sell_first_section_body')->nullable();
            $table->text('sell_button_text')->nullable();
            $table->text('sell_blurb1_heading')->nullable();
            $table->text('sell_blurb1_body')->nullable();
            $table->text('sell_blurb2_heading')->nullable();
            $table->text('sell_blurb2_body')->nullable();
            $table->text('sell_blurb3_heading')->nullable();
            $table->text('sell_blurb3_body')->nullable();
            $table->text('sell_blurb4_heading')->nullable();
            $table->text('sell_blurb4_body')->nullable();

            $table->text('agents_heading')->nullable();
            $table->text('agents_body')->nullable();
            $table->text('agents_form_heading')->nullable();
            $table->text('agents_body_heading')->nullable();

            $table->text('listing_state_section_heading')->nullable();
            $table->text('listing_state_section_body')->nullable();
            $table->text('listing_state_heading')->nullable();
            $table->text('listing_state_about_heading')->nullable();
            $table->text('listing_state_about_body')->nullable();
            $table->text('listing_state_footer_heading')->nullable();
            $table->text('listing_state_footer_body')->nullable();

            $table->text('listing_city_section_heading')->nullable();
            $table->text('listing_city_section_body')->nullable();
            $table->text('listing_city_heading')->nullable();
            $table->text('listing_city_about_heading')->nullable();
            $table->text('listing_city_about_body')->nullable();
            $table->text('listing_city_footer_heading')->nullable();
            $table->text('listing_city_footer_body')->nullable();

            $table->text('property_footer_body')->nullable();

            $table->text('contact_us_heading')->nullable();
            $table->text('contact_us_email')->nullable();
            $table->text('contact_us_body')->nullable();
            $table->text('contact_us_address')->nullable();
            
            $table->text('footer_copyright')->nullable();
            
            $table->text('home_first_secton_body_markdown')->nullable(); 
            $table->text('home_content_body_markdown')->nullable();
            $table->text('home_last_section_body_markdown')->nullable();
            $table->text('sell_first_section_body_markdown')->nullable();
            $table->text('sell_blurb1_body_markdown')->nullable();
            $table->text('sell_blurb2_body_markdown')->nullable();
            $table->text('sell_blurb3_body_markdown')->nullable();
            $table->text('sell_blurb4_body_markdown')->nullable();
            $table->text('agents_body_markdown')->nullable();
            $table->text('listing_state_section_body_markdown')->nullable();
            $table->text('listing_state_about_body_markdown')->nullable();
            $table->text('listing_state_footer_body_markdown')->nullable();
            $table->text('listing_city_section_body_markdown')->nullable();
            $table->text('listing_city_about_body_markdown')->nullable();
            $table->text('listing_city_footer_body_markdown')->nullable();
            $table->text('property_footer_body_markdown')->nullable();
            $table->text('contact_us_body_markdown')->nullable();
            $table->text('contact_us_address_markdown')->nullable();
            $table->text('footer_copyright_markdown')->nullable();

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
        Schema::dropIfExists('settings');
    }
}
