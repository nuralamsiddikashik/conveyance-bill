<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'login_requests', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'user_id' )
                ->constrained()
                ->cascadeOnDelete();
            $table->string( 'status', 20 )->default( 'pending' );
            $table->foreignId( 'approved_by' )
                ->nullable()
                ->constrained( 'users' )
                ->nullOnDelete();
            $table->foreignId( 'rejected_by' )
                ->nullable()
                ->constrained( 'users' )
                ->nullOnDelete();
            $table->timestamp( 'approved_at' )->nullable();
            $table->timestamp( 'rejected_at' )->nullable();
            $table->string( 'request_ip', 45 )->nullable();
            $table->text( 'request_user_agent' )->nullable();
            $table->timestamps();

            $table->index( ['status', 'created_at'] );
            $table->index( ['user_id', 'status'] );
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'login_requests' );
    }
};
