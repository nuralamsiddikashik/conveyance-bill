<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'conveyance_delete_requests', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'conveyance_id' )
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId( 'requested_by' )
                ->constrained( 'users' )
                ->cascadeOnDelete();
            $table->foreignId( 'approved_by' )
                ->nullable()
                ->constrained( 'users' )
                ->nullOnDelete();
            $table->foreignId( 'rejected_by' )
                ->nullable()
                ->constrained( 'users' )
                ->nullOnDelete();

            $table->string( 'status', 20 )->default( 'pending' );
            $table->timestamp( 'approved_at' )->nullable();
            $table->timestamp( 'rejected_at' )->nullable();

            $table->date( 'conveyance_date' )->nullable();
            $table->decimal( 'conveyance_total_amount', 12, 2 )->nullable();
            $table->foreignId( 'conveyance_owner_id' )
                ->nullable()
                ->constrained( 'users' )
                ->nullOnDelete();

            $table->string( 'request_ip', 45 )->nullable();
            $table->text( 'request_user_agent' )->nullable();

            $table->timestamps();

            $table->index( ['status', 'created_at'] );
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'conveyance_delete_requests' );
    }
};
