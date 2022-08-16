<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->text('OpenHouseKey')->nullable();
            $table->text('SourceSystemKey')->nullable();
            $table->text('BridgeModificationTimestamp')->nullable();
            $table->text('OriginatingSystemName')->nullable();
            $table->text('OpenHouseStartTime')->nullable();
            $table->text('OpenHouseEndTime')->nullable();
            $table->text('OpenHouseId')->nullable();
            $table->text('VirtualOpenHouseURL')->nullable();
            $table->text('ListingId')->nullable();
            $table->text('OpenHouseDate')->nullable();
            $table->text('ModificationTimestamp')->nullable();
            $table->text('ListingKey')->nullable();
            $table->text('ListingKeyNumeric')->nullable();
            $table->text('OpenHouseRemarks')->nullable();

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
        Schema::dropIfExists('houses');
    }
}
