<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RcTypeSignal;
use App\Models\RcPoint;
use App\Models\GfdInspectionItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create default users
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => 'password',
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@example.com'],
            [
                'name' => 'Pimpinan',
                'password' => 'password',
                'role' => 'pimpinan',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pegawai@example.com'],
            [
                'name' => 'Pegawai Teknisi',
                'password' => 'password',
                'role' => 'pegawai',
                'is_active' => true,
            ]
        );

        // 1. Seed RC Type Signals & Points
        $tsd = RcTypeSignal::updateOrCreate(['name' => 'TSD'], ['sequence' => 0]);
        RcPoint::updateOrCreate(['type_signal_id' => $tsd->id, 'name' => 'TS.OPEN'], ['sequence' => 0]);
        RcPoint::updateOrCreate(['type_signal_id' => $tsd->id, 'name' => 'TS.CLOSE'], ['sequence' => 1]);
        RcPoint::updateOrCreate(['type_signal_id' => $tsd->id, 'name' => 'TS.INVALID'], ['sequence' => 2]);

        $tss = RcTypeSignal::updateOrCreate(['name' => 'TSS'], ['sequence' => 1]);
        RcPoint::updateOrCreate(['type_signal_id' => $tss->id, 'name' => 'FDI'], ['sequence' => 0]);
        RcPoint::updateOrCreate(['type_signal_id' => $tss->id, 'name' => 'PSF'], ['sequence' => 1]);

        $tcd = RcTypeSignal::updateOrCreate(['name' => 'TCD'], ['sequence' => 2]);
        RcPoint::updateOrCreate(['type_signal_id' => $tcd->id, 'name' => 'TC.OPEN'], ['sequence' => 0]);
        RcPoint::updateOrCreate(['type_signal_id' => $tcd->id, 'name' => 'TC.CLOSE'], ['sequence' => 1]);

        // 2. Seed GFD Inspection Items
        $gfdItems = [
            'ELEKTRONIK GFD',
            'CT GFD',
            'INSTALASI POWER SUPLAY TR',
            'LAMPU INDIKATOR',
            'BATTERY',
            'WIRING POWER SUPPLY TR KE GFD',
            'WIRING CT KE GFD',
            'WIRING LAMPU INDIKATOR KE GFD',
        ];

        foreach ($gfdItems as $index => $name) {
            GfdInspectionItem::updateOrCreate(
                ['name' => $name],
                ['sequence' => $index]
            );
        }
    }
}
