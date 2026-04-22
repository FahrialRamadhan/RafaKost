<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->enum('gender', ['laki-laki', 'perempuan'])->nullable()->after('phone');
            $table->string('pekerjaan')->nullable()->after('gender');
            $table->string('photo')->nullable()->after('pekerjaan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'gender', 'pekerjaan', 'photo']);
        });
    }
};
