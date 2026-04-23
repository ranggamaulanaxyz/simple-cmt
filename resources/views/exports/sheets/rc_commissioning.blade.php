<table>
    <tr>
        <td rowspan="4" style="text-align: center; vertical-align: middle; border-top: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000;"></td>
        <td style="border-top: 1px solid #000;">PT PLN (PERSERO)</td>
        <td style="border-top: 1px solid #000;"></td>
        <td style="border-top: 1px solid #000;"></td>
        <td style="border-top: 1px solid #000;"></td>
        <td style="border-top: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="3" rowspan="4" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">FORMULIR</td>
        <td colspan="4" style="border: 1px solid #000; text-align: center; vertical-align: middle; font-size: 9pt;">NO DOKUMEN</td>
        <td colspan="2" style="border: 1px solid #000; font-size: 9pt;">Hal :</td>
        <td rowspan="6" style="text-align: center; vertical-align: middle; border: 1px solid #000;"></td>
    </tr>
    <tr>
        <td>UP2D JAWA BARAT</td>
        <td></td>
        <td></td>
        <td></td>
        <td style="border-right: 1px solid #000;"></td>
        <td colspan="4" rowspan="3" style="border: 1px solid #000;"></td>
        <td colspan="2" style="border: 1px solid #000; font-size: 9pt;">tgl :</td>
    </tr>
    <tr>
        <td>JL. Dr Ir Soekarno No.3 Bandung</td>
        <td></td>
        <td></td>
        <td></td>
        <td style="border-right: 1px solid #000;"></td>
        <td colspan="2" style="border: 1px solid #000; font-size: 9pt;">Rev :</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #000;">TLP : (022)4230747 , FAX (022) 4239079</td>
        <td style="border-bottom: 1px solid #000;"></td>
        <td style="border-bottom: 1px solid #000;"></td>
        <td style="border-bottom: 1px solid #000;"></td>
        <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="2" style="border: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="15" rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">FORM COMMISSIONING REMOTE CONTROL GARDU DISTRIBUSI</td>
    </tr>
    <tr style="display: none;">
        @for($i = 0; $i < 15; $i++) <td></td> @endfor
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold; font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">LOKASI</td>
        <td colspan="3" style="font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $report->lokasi ?? '-' }}</td>
        <td colspan="4" style="font-weight: bold; font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">ARAH</td>
        <td colspan="5" style="font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $report->arah ?? '-' }}</td>
        <td style="font-weight: bold; font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">TANGGAL</td>
        <td style="font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $report->date_commissioning ? $report->date_commissioning->translatedFormat('d F Y') : '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold; font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">RTU</td>
        <td colspan="3" style="font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $report->rtu ?? '-' }}</td>
        <td colspan="4" style="font-weight: bold; font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">MODEM</td>
        <td colspan="5" style="font-size: 10pt; background-color: #BDD7EE; text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $report->modem ?? '-' }}</td>
        <td style="background-color: #BDD7EE; border: 1px solid #000;"></td>
        <td style="background-color: #BDD7EE; border: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="14" style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">DATA BASE</td>
        <td rowspan="3" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">TYPE SIGNAL</td>
        <td rowspan="3" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">POINT</td>
        <td style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">TERMINAL</td>
        <td colspan="2" rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">SIGNALING</td>
        <td colspan="8" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">CONTROL</td>
        <td rowspan="3" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">Arah RC</td>
        <td rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">NOTE</td>
    </tr>
    <tr>
        <td style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">KUBIKEL</td>
        <td colspan="3" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">DCC to</td>
        <td colspan="3" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">DCC to</td>
        <td colspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">DCC to</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000;"></td>
        <td style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">GH</td>
        <td style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">DCC</td>
        <td colspan="3" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">RTU</td>
        <td colspan="3" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">RELE/PLAT</td>
        <td colspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 10pt; border: 1px solid #000;">LBS</td>
        <td style="text-align: center; font-size: 10px; vertical-align: middle; border: 1px solid #000; font-weight: bold;">√ = OK , x = NOK</td>
    </tr>
    @php
        $grouped = $report->rcCommissionings->groupBy('rc_direction_id');
    @endphp
    @foreach($grouped->values() as $gIndex => $items)
        @php
            $bgColor = ($gIndex % 2 === 0) ? '#FFCC99' : '#ffffff';
        @endphp
        @foreach($items as $index => $item)
            <tr>
                <td style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->database_field }}</td>
                <td style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->point->typeSignal->name ?? '-' }}</td>
                <td style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->point->name ?? '-' }}</td>
                <td style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->terminal_kubiker }}</td>
                <td style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->signaling_gh ? '√' : '' }}</td>
                <td style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->signaling_dcc ? '√' : '' }}</td>
                <td colspan="3" style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->control_dcc_rtu ? '√' : '' }}</td>
                <td colspan="3" style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->control_dcc_rele_plat ? '√' : '' }}</td>
                <td colspan="2" style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->control_dcc_lbs ? '√' : '' }}</td>
                @if($index === 0)
                    <td rowspan="{{ count($items) }}" style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">
                        {{ $item->direction->arah_remote_control ?? '-' }}
                        <br>
                        <span style="color: #666; font-size: 10px;">{{ $item->direction->penyulang ?? '-' }}</span>
                    </td>
                @endif
                <td style="text-align: center; vertical-align: middle; font-size: 9pt; border: 1px solid #000; background-color: {{ $bgColor }};">{{ $item->note ? '√' : 'x' }}</td>
            </tr>
        @endforeach
    @endforeach

    <tr>
        <td colspan="2" style="text-align: center; border-top: 1px solid #000; border-left: 1px solid #000;">Catatan</td>
        <td colspan="14" style="border-top: 1px solid #000; border-right: 1px solid #000;">{{ $report->commissioning_notes ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border-left: 1px solid #000;"></td>
        <td colspan="14" style="border-right: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="2" style="border-left: 1px solid #000; border-bottom: 1px solid #000;"></td>
        <td colspan="14" style="border-right: 1px solid #000; border-bottom: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">Dispatcher</td>
        <td colspan="8" style="text-align: center; border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">Pengawas Lapangan</td>
        <td colspan="4" style="text-align: center; border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">PT. WAHANA CAHAYA SUKSES</td>
    </tr>
    <tr>
        <td colspan="4" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="8" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="4" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="4" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="8" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="4" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="4" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="8" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="4" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="4" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="8" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
        <td colspan="4" style="border-left: 1px solid #000; border-right: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;"> ……………………………………..</td>
        <td colspan="8" style="text-align: center; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;"> ……………………………………..</td>
        <td colspan="4" style="text-align: center; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;"> ……………………………………..</td>
    </tr>
</table>