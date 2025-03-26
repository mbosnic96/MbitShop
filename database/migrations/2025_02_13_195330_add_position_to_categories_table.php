<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{  
  
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('categories', function (Blueprint $table) {
               
                $table->integer('position')->default(0); // Position for ordering
            });
        }
    
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('categories', function (Blueprint $table) {
                // Drop the parent_id column
                $table->dropColumn('position');
            });
        }

};
