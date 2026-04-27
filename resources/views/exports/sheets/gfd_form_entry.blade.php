<table>
    <!-- Row 1-3: Header Info -->
    <tr>
        <td colspan="2"></td>
        <td colspan="15" style="font-weight: bold; font-size: 8pt;">PT.PLN (PERSERO)</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="15" style="font-weight: bold; font-size: 8pt;">UNIT INDUK DISTRIBUSI BANTEN</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="15" style="font-weight: bold; font-size: 8pt;">UP3 TELUK NAGA</td>
    </tr>

    <!-- Row 4: Spacer/Title -->
    <tr>
        <td colspan="17" style="text-align: center; font-weight: bold; font-size: 16pt; height: 30px;">
            FORM PEMASANGAN GROUND FAULT DETECTOR (GFD)
        </td>
    </tr>

    <!-- Row 5: Spacer -->
    <tr><td colspan="17"></td></tr>

    <!-- Row 6: UP3 -->
    <tr>
        <td></td>
        <td colspan="4">UP3</td>
        <td>:</td>
        <td colspan="11">TELUK NAGA</td>
    </tr>

    <!-- Row 7: Spacer -->
    <tr><td></td></tr>

    <!-- Row 8: TANGGAL -->
    <tr>
        <td></td>
        <td colspan="4">TANGGAL</td>
        <td>:</td>
        <td colspan="11">{{ $report->date ? $report->date->translatedFormat('d F Y') : '-' }}</td>
    </tr>

    <!-- Row 9: JENIS PEKERJAAN -->
    <tr>
        <td></td>
        <td colspan="4">JENIS PEKERJAAN</td>
        <td>:</td>
        <td colspan="11">{{ strtoupper($report->task_type ?? '-') }}</td>
    </tr>

    <!-- Row 10: NOMOR GARDU & ARAH GARDU -->
    <tr>
        <td></td>
        <td colspan="4">NOMOR GARDU</td>
        <td>:</td>
        <td colspan="3">{{ $report->gardu ?? '-' }}</td>
        <td></td>
        <td colspan="3">ARAH GARDU     :</td>
        <td colspan="4">{{ $report->arah_gardu ?? '-' }}</td>
    </tr>

    <!-- Row 11: PENYULANG -->
    <tr>
        <td></td>
        <td colspan="4">PENYULANG</td>
        <td>:</td>
        <td>{{ $report->penyulang ?? '-' }}</td>
    </tr>

    <!-- Row 12: GARDU INDUK -->
    <tr>
        <td></td>
        <td colspan="4">GARDU INDUK</td>
        <td>:</td>
        <td>{{ $report->gardu_induk ?? '-' }}</td>
    </tr>

    <!-- Row 13: ALAMAT GARDU -->
    <tr>
        <td></td>
        <td colspan="4" style="vertical-align: middle;">ALAMAT GARDU</td>
        <td style="vertical-align: middle;">:</td>
        <td colspan="11" style="vertical-align: middle; height: 40px; font-weight: bold">{{ $report->address ?? '-' }}</td>
    </tr>

    <!-- Row 14: KORDINAT -->
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>KORDINAT :</td>
        <td></td>
        <td colspan="3">{{ $report->latitude ?? '' }}</td>
        <td>,</td>
        <td colspan="3">{{ $report->longitude ?? '' }}</td>
    </tr>

    <!-- Row 15: GFD HEADERS -->
    <tr>
        <td></td>
        <td colspan="6" style="font-weight: bold; text-align: center; border: 1px solid #000;">GFD TERPASANG / LAMA</td>
        <td colspan="10" style="font-weight: bold; text-align: center; border: 1px solid #000;">GFD TERPASANG BARU</td>
    </tr>

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
        <td></td>
        <td colspan="2">{{ $row['label'] }}</td>
        <td style="text-align: center;">:</td>
        <td colspan="{{ empty($row['unit']) ? 3 : 2 }}" style="text-align: {{ empty($row['unit']) ? 'center' : 'right' }}; {{ $row['label'] === 'KONDISI' ? 'font-size: 9pt; font-weight: bold;' : '' }}">{{ $report->{$row['old']} ?? '-' }}</td>
        @if(!empty($row['unit']))
            <td style="text-align: left;">{{ $row['unit'] }}</td>
        @endif

        <td colspan="6">{{ $row['label'] }}</td>
        <td style="text-align: center;">:</td>
        <td colspan="{{ empty($row['unit']) ? 3 : 2 }}" style="text-align: {{ empty($row['unit']) ? 'center' : 'right' }}; {{ $row['label'] === 'KONDISI' ? 'font-size: 9pt; font-weight: bold;' : '' }}">{{ $report->{$row['new']} ?? '-' }}</td>
        @if(!empty($row['unit']))
            <td style="text-align: left;">{{ $row['unit'] }}</td>
        @endif
    </tr>
    @endforeach

    <!-- Row 23: URAIAN PEMERIKSAAN -->
    <tr>
        <td></td>
        <td colspan="16" style="font-weight: bold; text-decoration: underline; text-align: center;">URAIAN PEMERIKSAAN</td>
    </tr>

    <!-- Row 24: Spacer -->
    <tr><td colspan="17"></td></tr>

    @foreach($report->gfdInspections->sortBy('item.sequence') as $index => $inspection)
    <tr>
        <td></td>
        <td style="font-weight: bold;">{{ $index + 1 }}.</td>
        <td colspan="4" style="font-weight: bold;">{{ $inspection->item->name }}</td>
        <td style="font-weight: bold; text-align: center; border: 1px solid #000000;">{{ $inspection->ada ? '√' : '-' }}</td>
        <td></td>
        <td style="font-weight: bold; border: 1px solid #000000;">ADA</td>
        <td></td>
        <td style="font-weight: bold; text-align: center; border: 1px solid #000000;">{{ $inspection->tidak_ada ? '√' : '-' }}</td>
        <td></td>
        <td style="font-weight: bold; border: 1px solid #000000;">TIDAK ADA</td>
        <td></td>
        <td style="font-weight: bold; text-align: center; border: 1px solid #000000;">{{ $inspection->rusak ? '√' : '-' }}</td>
        <td></td>
        <td style="font-weight: bold; border: 1px solid #000000;">RUSAK</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    @endforeach
    <tr>
        <td></td>
        <td colspan="2" style="font-weight: bold">KETERANGAN:</td>
        <td colspan="9">{{ $report->notes ?? '-' }}</td>
        <td style="font-weight: bold">TYPE GARDU</td>
        <td style="font-weight: bold">:</td>
        <td></td>
        <td></td>
        <td style="font-weight: bold; border: 1px solid #000000">{{ $report->type_gardu ?? '-' }}</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="16" style="text-align: center; font-weight: bold;">{{ strtoupper($report->task_type ?? 'PASANG BARU') }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="16"></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="16"></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="16"></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: center; font-weight: bold">PELAKSANA</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="5" style="text-align: center; font-weight: bold">PENGAWAS</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: right">(</td>
        <td style="text-align: center">JAJANG CAHYANA</td>
        <td></td>
        <td>)</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right">(</td>
        <td colspan="5" style="text-align: center">Akshon</td>
        <td>)</td>
    </tr>

</table>