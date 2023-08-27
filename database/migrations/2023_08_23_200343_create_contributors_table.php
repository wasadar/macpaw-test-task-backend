<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Collection;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contributors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Collection::class)->constrained()->onDelete('cascade');
            $table->string('user_name');
            $table->decimal('amount',10,2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributors');
    }
};
