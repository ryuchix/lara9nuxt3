<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->text('MemberAOR')->nullable();
            $table->text('MemberCity')->nullable();
            $table->text('MIAMIRE_MUC')->nullable();
            $table->text('MemberEmail')->nullable();
            $table->text('MemberMlsId')->nullable();
            $table->text('MemberStatus')->nullable();
            $table->text('MemberCountry')->nullable();
            $table->text('MemberAddress1')->nullable();
            $table->text('MemberFullName')->nullable();
            $table->text('MemberAddress2')->nullable();
            $table->text('MemberLastName')->nullable();
            $table->text('MemberNickname')->nullable();
            $table->text('MemberFirstName')->nullable();
            $table->text('MemberHomePhone')->nullable();
            $table->text('SocialMediaType')->nullable();
            $table->text('MemberNameSuffix')->nullable();
            $table->text('MemberMiddleName')->nullable();
            $table->text('MemberPostalCode')->nullable();
            $table->text('MemberDirectPhone')->nullable();
            $table->text('MemberMobilePhone')->nullable();
            $table->text('MemberStateLicense')->nullable();
            $table->text('MemberPreferredPhone')->nullable();
            $table->text('MemberStateOrProvince')->nullable();
            $table->text('ModificationTimestamp')->nullable();
            $table->text('Media')->nullable();
            $table->text('JobTitle')->nullable();
            $table->text('OfficeName')->nullable();
            $table->text('ListAgentStateLicense')->nullable();
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
        Schema::dropIfExists('agents');
    }
}
