<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
           'home_hero_heading',
            'home_search_placeholder',
            'home_first_secton_heading',
            'home_first_secton_body',
            'home_content_heading',
            'home_content_body',
            'home_featured_city_heading',
            'home_last_section_heading',
            'home_last_section_body',

            'sell_first_section_heading',
            'sell_first_section_body',
            'sell_button_text',
            'sell_blurb1_heading',
            'sell_blurb1_body',
            'sell_blurb2_heading',
            'sell_blurb2_body',
            'sell_blurb3_heading',
            'sell_blurb3_body',
            'sell_blurb4_heading',
            'sell_blurb4_body',

            'agents_heading',
            'agents_body',
            'agents_form_heading',
            'agents_body_heading',

            'listing_state_section_heading',
            'listing_state_section_body',
            'listing_state_heading',
            'listing_state_about_heading',
            'listing_state_about_body',
            'listing_state_footer_heading',
            'listing_state_footer_body',

            'listing_city_section_heading',
            'listing_city_section_body',
            'listing_city_heading',
            'listing_city_about_heading',
            'listing_city_about_body',
            'listing_city_footer_heading',
            'listing_city_footer_body',

            'property_footer_body',

            'contact_us_heading',
            'contact_us_email',
            'contact_us_body',
            'contact_us_address',

            'footer_copyright',


            'home_first_secton_body_markdown', 
            'home_content_body_markdown',
            'home_last_section_body_markdown',
            'sell_first_section_body_markdown',
            'sell_blurb1_body_markdown',
            'sell_blurb2_body_markdown',
            'sell_blurb3_body_markdown',
            'sell_blurb4_body_markdown',
            'agents_body_markdown',
            'listing_state_section_body_markdown',
            'listing_state_about_body_markdown',
            'listing_state_footer_body_markdown',
            'listing_city_section_body_markdown',
            'listing_city_about_body_markdown',
            'listing_city_footer_body_markdown',
            'property_footer_body_markdown',
            'contact_us_body_markdown',
            'contact_us_address_markdown',
            'footer_copyright_markdown',


    ];
}
