<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add address column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->text('address')->nullable()->after('password');
        });

        // Add user_id foreign key to allorders table
        Schema::table('allorders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove user_id foreign key from allorders table
        Schema::table('allorders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        // Remove address column from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
