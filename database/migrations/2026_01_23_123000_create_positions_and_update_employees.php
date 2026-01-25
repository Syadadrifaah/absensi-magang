<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // create positions table
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('level')->nullable();
            $table->timestamps();
        });

        // if employees has 'position' column, migrate data
        if (Schema::hasTable('employees')) {
            if (Schema::hasColumn('employees', 'position')) {
                // gather distinct position names
                $positions = DB::table('employees')->select('position')->whereNotNull('position')->distinct()->pluck('position')->toArray();

                // insert into positions
                $map = [];
                foreach ($positions as $pos) {
                    $id = DB::table('positions')->insertGetId([
                        'name' => $pos,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $map[$pos] = $id;
                }

                // add position_id column
                Schema::table('employees', function (Blueprint $table) {
                    $table->unsignedBigInteger('position_id')->nullable()->after('kategori_id');
                });

                // update employees set position_id
                foreach ($map as $pos => $id) {
                    DB::table('employees')->where('position', $pos)->update(['position_id' => $id]);
                }

                // drop old position column
                Schema::table('employees', function (Blueprint $table) {
                    if (Schema::hasColumn('employees', 'position')) {
                        $table->dropColumn('position');
                    }
                });

                // add foreign key
                Schema::table('employees', function (Blueprint $table) {
                    $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
                });
            } else {
                // just add position_id if missing
                Schema::table('employees', function (Blueprint $table) {
                    if (!Schema::hasColumn('employees', 'position_id')) {
                        $table->unsignedBigInteger('position_id')->nullable()->after('kategori_id');
                        $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
                    }
                });
            }
        }
    }

    public function down()
    {
        // remove foreign key and column
        if (Schema::hasTable('employees') && Schema::hasColumn('employees', 'position_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropForeign(['position_id']);
                $table->dropColumn('position_id');
            });
        }

        Schema::dropIfExists('positions');
    }
};
