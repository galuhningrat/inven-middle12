    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_role');
                $table->string('module'); // contoh: 'kurikulum', 'jadwal', 'ruangan'
                $table->boolean('can_create')->default(false);
                $table->boolean('can_read')->default(false);
                $table->boolean('can_update')->default(false);
                $table->boolean('can_delete')->default(false);
                $table->timestamps();

                $table->foreign('id_role')->references('id')->on('roles')->onDelete('cascade');
                $table->unique(['id_role', 'module']); // Satu role hanya punya 1 permission per module
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('permissions');
        }
    };
