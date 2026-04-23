<table>
    <tr>
        <td colspan="2" rowspan="3" style="font-family: 'Franklin Gothic Book'; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle;">FP.1.01</td>
        <td colspan="8" style="font-family: 'Franklin Gothic Book'; font-size: 14pt; font-weight: bold; text-align: center; vertical-align: middle;">PT. Wahana Cahaya Sukses</td>
    </tr>
    <tr>
        <td colspan="8" style="font-family: 'Franklin Gothic Book'; font-size: 14pt; text-align: center; vertical-align: middle;">KONTRAK RINCI : 0102.SPBJ/DAN.01.03/F02160000/2024</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="10" rowspan="2" style="background-color: #FFCC99; font-family: 'Lucida Sans Unicode'; font-size: 12pt; font-weight: bold; text-align: center; vertical-align: middle;">LAPORAN PEKERJAAN<br />KONTRAK RINCI TAHAP 5 PEMASANGAN REMOTE CONTROL GD GH</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt;">Hari/Tanggal</td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt; text-align: center;">:</td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt;">{{ $report->date?->translatedFormat('l, d F Y') ?? '-' }}</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt;">Nama Gardu</td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt; text-align: center;">:</td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt;">{{ $report->gardu ?? '-' }}</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt;">Alamat</td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt; text-align: center;">:</td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt;">{{ $report->address ?? '-' }}</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt;">Koordinat/GPS</td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt; text-align: center;">:</td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt;">{{ $report->latitude ?? '-' }}, {{ $report->longitude ?? '-' }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="4" style="font-family: 'Times New Roman'; font-size: 12pt;">Arah RC</td>
        <td colspan="5" style="font-family: 'Times New Roman'; font-size: 12pt;">Penyulang</td>
    </tr>
    @foreach($report->rcDirections as $i => $dir)
        <tr>
            <td style="font-family: 'Times New Roman'; font-size: 12pt; text-align: center;">{{ $i + 1 }}</td>
            <td colspan="4" style="font-family: 'Times New Roman'; font-size: 12pt;">{{ $dir->arah_remote_control ?? '-' }}</td>
            <td colspan="5" style="font-family: 'Times New Roman'; font-size: 12pt;">{{ $dir->penyulang ?? '-' }}</td>
        </tr>
    @endforeach
    @for($i = count($report->rcDirections); $i < 16; $i++)
        <tr>
            <td style="font-family: 'Times New Roman'; font-size: 12pt; text-align: center;"></td>
            <td colspan="4" style="font-family: 'Times New Roman'; font-size: 12pt;"></td>
            <td colspan="5" style="font-family: 'Times New Roman'; font-size: 12pt;"></td>
        </tr>
    @endfor
    <tr>
        <td></td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt;">CATATAN</td>
        <td style="font-family: 'Times New Roman'; font-size: 12pt; text-align: center;">:</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="7" rowspan="3" style="font-family: 'Times New Roman'; font-size: 12pt;"></td>
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
        <td colspan="4" rowspan="6" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: top;">DIREKSI PEKERJAAN</td>
        <td colspan="4" rowspan="6" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: top;">PENGAWAS PEKERJAAN</td>
        <td colspan="2" rowspan="6" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: top;">PT. WAHANA CAHAYA SUKSES</td>
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
        <td colspan="4" rowspan="2" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: middle;">(VERY INDRA PRATAMA)</td>
        <td colspan="4" rowspan="2" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: middle;">(......................................................)</td>
        <td colspan="2" rowspan="2" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: middle;">(......................................................)</td>
    </tr>
</table>