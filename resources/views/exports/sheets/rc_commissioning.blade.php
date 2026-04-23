<table>
    <tr>
        <td rowspan="4" style="text-align: center; vertical-align: middle;"></td>
        <td>PT PLN (PERSERO)</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="3" rowspan="4" style="text-align: center; vertical-align: middle;">FORMULIR</td>
        <td colspan="4">NO DOKUMEN</td>
        <td colspan="2">Hal :</td>
        <td rowspan="6" style="text-align: center; vertical-align: middle;"></td>
    </tr>
    <tr>
        <td>UP2D JAWA BARAT</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="4" rowspan="3"></td>
        <td colspan="2">tgl :</td>
    </tr>
    <tr>
        <td>JL. Dr Ir Soekarno No.3 Bandung</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2">Rev :</td>
    </tr>
    <tr>
        <td>TLP : (022)4230747 , FAX (022) 4239079</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="15" style="text-align: center;">FORM COMMISSIONING REMOTE CONTROL GARDU DISTRIBUSI</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>LOKASI</td>
        <td colspan="4">{{ $report->lokasi ?? '-' }}</td>
        <td>ARAH</td>
        <td colspan="8">{{ $report->arah ?? '-' }}</td>
        <td>TANGGAL</td>
        <td>{{ $report->date_commissioning ? $report->date_commissioning->translatedFormat('d F Y') : '-' }}</td>
    </tr>
    <tr>
        <td>RTU</td>
        <td colspan="4">{{ $report->rtu ?? '-' }}</td>
        <td>MODEM</td>
        <td colspan="10">{{ $report->modem ?? '-' }}</td>
    </tr>
    <tr>
        @for($i = 0; $i < 16; $i++)
        <td></td> @endfor
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center; vertical-align: middle;">DATA BASE</td>
        <td rowspan="3" style="text-align: center; vertical-align: middle;">TYPE SIGNAL</td>
        <td rowspan="3" style="text-align: center; vertical-align: middle;">POINT</td>
        <td rowspan="2" style="text-align: center; vertical-align: middle;">TERMINAL</td>
        <td colspan="2" rowspan="2" style="text-align: center; vertical-align: middle;">SIGNALING</td>
        <td colspan="8" style="text-align: center;">CONTROL</td>
        <td rowspan="3" style="text-align: center; vertical-align: middle;">Arah RC</td>
        <td rowspan="2" style="text-align: center;">NOTE</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center;">DCC to</td>
        <td colspan="3" style="text-align: center;">DCC to</td>
        <td colspan="2" style="text-align: center;">DCC to</td>
    </tr>
    <tr>
        <td>KUBIKEL</td>
        <td style="text-align: center;">GH</td>
        <td style="text-align: center;">DCC</td>
        <td colspan="3" style="text-align: center;">RTU</td>
        <td colspan="3" style="text-align: center;">RELE/PLAT</td>
        <td colspan="2" style="text-align: center;">LBS</td>
        <td style="text-align: center; font-size: 10px;">√ = OK , x = NOK</td>
    </tr>
    @php
        $grouped = $report->rcCommissionings->groupBy('rc_direction_id');
    @endphp
    @foreach($grouped as $directionId => $items)
        @foreach($items as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $item->database_field }}</td>
                <td style="text-align: center;">{{ $item->point->typeSignal->name ?? '-' }}</td>
                <td style="text-align: center;">{{ $item->point->name ?? '-' }}</td>
                <td style="text-align: center;">{{ $item->terminal_kubiker }}</td>
                <td style="text-align: center;">{{ $item->signaling_gh ? '√' : '' }}</td>
                <td style="text-align: center;">{{ $item->signaling_dcc ? '√' : '' }}</td>
                <td style="text-align: center;">{{ $item->control_dcc_rtu ? '√' : '' }}</td>
                <td></td>
                <td></td>
                <td style="text-align: center;">{{ $item->control_dcc_rele_plat ? '√' : '' }}</td>
                <td></td>
                <td></td>
                <td style="text-align: center;">{{ $item->control_dcc_lbs ? '√' : '' }}</td>
                <td></td>
                @if($index === 0)
                    <td rowspan="{{ count($items) }}" style="text-align: center; vertical-align: middle;">
                        {{ $item->direction->arah_remote_control ?? '-' }}
                        <br>
                        <span style="color: #666; font-size: 10px;">{{ $item->direction->penyulang ?? '-' }}</span>
                    </td>
                @endif
                <td>{{ $item->note ? '√' : 'x' }}</td>
            </tr>
        @endforeach
    @endforeach
    <tr>
        @for($i = 0; $i < 16; $i++)
        <td></td> @endfor
    </tr>
    <tr>
        <td colspan="2">Catatan</td>
        <td colspan="14">{{ $report->commissioning_notes ?? '-' }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="14"></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="14"></td>
    </tr>
    <tr>
        <td colspan="4">Dispatcher</td>
        <td colspan="8">Pengawas Lapangan</td>
        <td colspan="4">PT. WAHANA CAHAYA SUKSES</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="4"> ……………………………………..</td>
        <td colspan="8"> ……………………………………..</td>
        <td colspan="4"> ……………………………………..</td>
    </tr>
</table>