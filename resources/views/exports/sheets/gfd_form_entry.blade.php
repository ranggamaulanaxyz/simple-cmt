<table>
    <!-- Header Row 1-3 Reserved for Logo and PLN info -->
    <tr>
        <td colspan="2"></td>
        <td colspan="14" style="font-weight: bold;">PT.PLN (PERSERO)</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="14">UNIT INDUK DISTRIBUSI BANTEN</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="14">UP3 TELUK NAGA</td>
    </tr>

    <!-- Title -->
    <tr>
        <td colspan="16" style="text-align: center; font-weight: bold; font-size: 14px; height: 30px; vertical-align: middle;">
            FORM PEMASANGAN GROUND FAULT DETECTOR (GFD)
        </td>
    </tr>

    <!-- General Info Section -->
    <tr>
        <td colspan="2" style="border: 1px solid #000;">UP3</td>
        <td style="border: 1px solid #000; text-align: center;">:</td>
        <td colspan="13" style="border: 1px solid #000;">TELUK NAGA</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">TANGGAL</td>
        <td style="border: 1px solid #000; text-align: center;">:</td>
        <td colspan="13" style="border: 1px solid #000;">{{ $report->date ? $report->date->translatedFormat('d F Y') : '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">JENIS PEKERJAAN</td>
        <td style="border: 1px solid #000; text-align: center;">:</td>
        <td colspan="13" style="border: 1px solid #000;">{{ strtoupper($report->task_type ?? '-') }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">NOMOR GARDU</td>
        <td style="border: 1px solid #000; text-align: center;">:</td>
        <td colspan="4" style="border: 1px solid #000;">{{ $report->gardu ?? '-' }}</td>
        <td colspan="2" style="border: 1px solid #000;">ARAH GARDU</td>
        <td style="border: 1px solid #000; text-align: center;">:</td>
        <td colspan="6" style="border: 1px solid #000;">{{ $report->arah_gardu ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">PENYULANG</td>
        <td style="border: 1px solid #000; text-align: center;">:</td>
        <td colspan="13" style="border: 1px solid #000;">{{ $report->penyulang ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">GARDU INDUK</td>
        <td style="border: 1px solid #000; text-align: center;">:</td>
        <td colspan="13" style="border: 1px solid #000;">{{ $report->gardu_induk ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000; height: 40px; vertical-align: top;">ALAMAT GARDU</td>
        <td style="border: 1px solid #000; text-align: center; vertical-align: top;">:</td>
        <td colspan="6" style="border: 1px solid #000; vertical-align: top; wrap-text: true;">{{ $report->address ?? '-' }}</td>
        <td colspan="2" style="border: 1px solid #000; vertical-align: top;">KORDINAT</td>
        <td style="border: 1px solid #000; text-align: center; vertical-align: top;">:</td>
        <td colspan="4" style="border: 1px solid #000; text-align: center; vertical-align: top;">{{ $report->latitude ?? '-' }} , {{ $report->longitude ?? '-' }}</td>
    </tr>

    <!-- GFD Header Comparison -->
    <tr>
        <td colspan="8" style="background-color: #f3f3f3; border: 1px solid #000; text-align: center; font-weight: bold;">GFD TERPASANG / LAMA</td>
        <td colspan="8" style="background-color: #f3f3f3; border: 1px solid #000; text-align: center; font-weight: bold;">GFD TERPASANG BARU</td>
    </tr>

    <!-- GFD ParamsRows -->
    @php
        $params = [
            ['label' => 'MERK GFD', 'old' => 'old_gfd', 'new' => 'new_gfd', 'unit' => ''],
            ['label' => 'TYPE / NO.SERI', 'old' => 'old_gfd_type_serial_number', 'new' => 'new_gfd_type_serial_number', 'unit' => ''],
            ['label' => 'SETTING KEPEKAAN ARUS', 'old' => 'old_gfd_setting_kepekaan_arus', 'new' => 'new_gfd_setting_kepekaan_arus', 'unit' => 'A'],
            ['label' => 'SETTING KEPEKAAN WAKTU', 'old' => 'old_gfd_setting_kepekaan_waktu', 'new' => 'new_gfd_setting_kepekaan_waktu', 'unit' => 'ms'],
            ['label' => 'SETTING WAKTU ( FLASHING )', 'old' => 'old_gfd_setting_waktu', 'new' => 'new_gfd_setting_waktu', 'unit' => 'h'],
            ['label' => 'INJEK ARUS', 'old' => 'old_gfd_injek_arus', 'new' => 'new_gfd_injek_arus', 'unit' => 'A'],
            ['label' => 'KONDISI', 'old' => 'old_gfd_condition', 'new' => 'new_gfd_condition', 'unit' => ''],
        ];
    @endphp

    @foreach($params as $row)
    <tr>
        <td colspan="4" style="border: 1px solid #000;">{{ $row['label'] }}</td>
        <td style="border: 1px solid #000; text-align: center;">:</td>
        <td colspan="2" style="border: 1px solid #000;">{{ $report->{$row['old']} ?? '-' }}</td>
        <td style="border: 1px solid #000;">{{ $row['unit'] }}</td>
        
        <td colspan="4" style="border: 1px solid #000;">{{ $row['label'] }}</td>
        <td style="border: 1px solid #000; text-align: center;">:</td>
        <td colspan="2" style="border: 1px solid #000;">{{ $report->{$row['new']} ?? '-' }}</td>
        <td style="border: 1px solid #000;">{{ $row['unit'] }}</td>
    </tr>
    @endforeach

    <!-- Uraian Pemeriksaan Section -->
    <tr>
        <td colspan="16" style="text-align: center; font-weight: bold; background-color: #f3f3f3; border: 1px solid #000;">URAIAN PEMERIKSAAN</td>
    </tr>

    @foreach($report->gfdInspections as $index => $insp)
    @php
        $itemName = $insp->item->name;
        if (str_contains($itemName, 'BATTERY')) { $itemName .= ' 3,60 VDC'; }
        if (str_contains($itemName, 'WIRING CT KE GFD')) { $itemName .= ' 60 Ω'; }
    @endphp
    <tr>
        <td colspan="7" style="border: 1px solid #000;">{{ $index + 1 }}. {{ $itemName }}</td>
        
        <!-- ADA / BENAR -->
        @php
            $isWiring = str_contains($insp->item->name, 'WIRING');
            $labelAda = $isWiring ? 'BENAR' : 'ADA';
        @endphp
        <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $insp->ada ? 'v' : '' }}</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $labelAda }}</td>
        
        <!-- TIDAK ADA / SALAH -->
        @php
            $labelTidakAda = $isWiring ? 'SALAH' : 'TIDAK ADA';
        @endphp
        <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $insp->tidak_ada ? 'v' : '' }}</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $labelTidakAda }}</td>
        
        <!-- RUSAK / EMPTY -->
        @if(!$isWiring)
        <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $insp->rusak || $insp->salah ? 'v' : '' }}</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">RUSAK</td>
        @else
        <td colspan="3" style="border: 1px solid #000;"></td>
        @endif
    </tr>
    @endforeach

    <tr>
        <td colspan="10" style="border: 1px solid #000;">KETERANGAN : {{ $report->notes ?? '-' }}</td>
        <td colspan="3" style="border: 1px solid #000;">TYPE GARDU :</td>
        <td colspan="3" style="border: 1px solid #000; text-align: center; font-weight: bold;">BETON</td>
    </tr>

    <!-- Signatures Section -->
    <tr><td colspan="16"></td></tr>
    <tr><td colspan="16" style="height: 20px; border-top: 2px solid #000;"></td></tr>
    <tr>
        <td colspan="8" style="text-align: center; font-weight: bold;">PELAKSANA</td>
        <td colspan="8" style="text-align: center; font-weight: bold;">PENGAWAS</td>
    </tr>
    <tr>
        <td colspan="8" style="text-align: center;">PT WAHANA CAHAYA SUKSES</td>
        <td colspan="8"></td>
    </tr>
    <tr><td colspan="16" style="height: 50px;"></td></tr>
    <tr>
        <td colspan="8" style="text-align: center;">( JAJANG CAHYANA )</td>
        <td colspan="8" style="text-align: center;">( Akhson )</td>
    </tr>
</table>
