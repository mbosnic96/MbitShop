<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->decimal('discount', 5, 2)->nullable()->default(0); // Možete koristiti discount kao procenat (npr. 10 za 10%)
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('discount');
    });
}

};
