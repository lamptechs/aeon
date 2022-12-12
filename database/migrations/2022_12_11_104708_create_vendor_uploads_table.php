<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_uploads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id');
            $table->string('file_name');
            $table->string('file_url');

            $table->string('remarks')->nullable();
            $table->enum('status',["Active","Inactive","Pending","Cencle","Delete"])->nullable();
            $table->string('created_by')->nullable();
            // $table->date('create_date')->nullable();
            $table->string('updated_by')->nullable();
            // $table->date('modified_date')->nullable();
            $table->timestamps();
            $table->string('deleted_by')->nullable();
            $table->date('deleted_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_uploads');
    }
}
