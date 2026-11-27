<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('member_code', 20)->unique()->after('id');
            $table->string('phone', 20)->unique()->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('role', 20)->default('USER')->index()->after('address');
            $table->unsignedInteger('points_balance')->default(0)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['member_code', 'phone', 'address', 'role', 'points_balance']);
        });
    }
};
