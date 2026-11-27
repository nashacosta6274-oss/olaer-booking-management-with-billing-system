<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remembers that a facility was checked in at some point. A transfer replaces the
     * status with "Transferred", so without this flag a transferred facility that was
     * already checked in cannot be told apart from one that never arrived.
     */
    public function up(): void
    {
        if (Schema::hasColumn('tbl_booking_details', 'was_checked_in')) {
            return;
        }

        Schema::table('tbl_booking_details', function (Blueprint $table): void {
            $table->boolean('was_checked_in')->default(false);
        });

        DB::table('tbl_booking_details')
            ->whereIn('status', ['Checked-in', 'Checked-out'])
            ->update(['was_checked_in' => true]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('tbl_booking_details', 'was_checked_in')) {
            Schema::table('tbl_booking_details', function (Blueprint $table): void {
                $table->dropColumn('was_checked_in');
            });
        }
    }
};
