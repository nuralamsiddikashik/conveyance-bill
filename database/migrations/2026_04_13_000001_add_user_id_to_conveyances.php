<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table( 'conveyances', function ( Blueprint $table ) {
            $table->foreignId( 'user_id' )
                ->nullable()
                ->after( 'id' )
                ->constrained()
                ->nullOnDelete();
            $table->index( ['user_id', 'date'] );
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table( 'conveyances', function ( Blueprint $table ) {
            $table->dropIndex( ['user_id', 'date'] );
            $table->dropConstrainedForeignId( 'user_id' );
        } );
    }
};
