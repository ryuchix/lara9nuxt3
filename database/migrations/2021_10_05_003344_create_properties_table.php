<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->text('ListingKey')->nullable();
            $table->text('Media')->nullable();
            $table->text('UnparsedAddress')->nullable();
            $table->integer('BedroomsTotal')->nullable();
            $table->float('BathroomsTotalDecimal')->nullable();
            $table->integer('ListPrice')->nullable();
            $table->text('PropertyType')->nullable();
            $table->text('ListingId')->nullable();
            $table->text('StateOrProvince')->nullable();
            $table->text('City')->nullable();
            $table->integer('PostalCode')->nullable();
            $table->text('ModificationTimestamp')->nullable();
            $table->integer('ListAgentMlsId')->nullable();
            $table->text('MajorChangeTimestamp')->nullable();
            $table->text('Latitude')->nullable();
            $table->text('Longitude')->nullable();
            $table->integer('ListAgentStateLicense')->nullable();
            $table->text('StreetName')->nullable();
            $table->date('ExpirationDate')->nullable();
            $table->text('SyndicationRemarks')->nullable();
            $table->text('ListAOR')->nullable();
            $table->text('PublicRemarks')->nullable();
            $table->text('AvailabilityDate')->nullable();
            $table->text('CommunityFeatures')->nullable();
            $table->text('MIAMIRE_SubdivisionInformation')->nullable();
            $table->text('Furnished')->nullable();
            $table->text('StructureType')->nullable();
            $table->text('AssociationAmenities')->nullable();
            $table->text('HighSchool')->nullable();
            $table->text('MiddleOrJuniorSchool')->nullable();
            $table->text('ElementarySchool')->nullable();
            $table->text('AssociationFee')->nullable();
            $table->text('CountyOrParish')->nullable();
            $table->text('ParkingFeatures')->nullable();
            $table->text('ConstructionMaterials')->nullable();
            $table->text('LotFeatures')->nullable();
            $table->text('Roof')->nullable();
            $table->text('PatioAndPorchFeatures')->nullable();
            $table->text('FireplaceYN')->nullable();
            $table->text('Flooring')->nullable();
            $table->text('Basement')->nullable();
            $table->text('Heating')->nullable();
            $table->text('InteriorFeatures')->nullable();
            $table->text('Appliances')->nullable();
            $table->text('SecurityFeatures')->nullable();
            $table->text('PropertySubType')->nullable();
            $table->text('Utilities')->nullable();
            $table->text('ArchitecturalStyle')->nullable();
            $table->text('YearBuiltDetails')->nullable();
            $table->text('MIAMIRE_PoolYN')->nullable();
            $table->text('MIAMIRE_RATIO_CurrentPrice_By_SQFT')->nullable();
            $table->text('YearBuilt')->nullable();
            $table->text('LivingArea')->nullable();
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
        Schema::dropIfExists('properties');
    }
}
