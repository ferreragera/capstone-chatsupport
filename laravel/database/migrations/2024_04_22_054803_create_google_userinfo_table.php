<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleUserinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_userinfo', function (Blueprint $table) {
            $table->id();
            $table->text('gid');
            $table->string('email');
            $table->string('givenName');
            $table->string('familyName');
            $table->string('name');
            $table->text('picture');
            $table->boolean('verifiedEmail');
            $table->string('hd')->nullable();
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
        Schema::dropIfExists('google_userinfo');
    }
}