<table>
    <thead>
        <tr>
            <th colspan="4" style="font-size: 16px; font-weight: bold;">LAPORAN PEKERJAAN #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</th>
        </tr>
        <tr>
            <th colspan="4" style="font-size: 12px; color: #666666;">Generated on {{ now()->format('d M Y H:i:s') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr><td></td><td></td><td></td><td></td></tr>
        <tr>
            <td colspan="4" style="background-color: #f3f3f3; font-weight: bold;">INFORMASI UMUM</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">ID Laporan</td>
            <td>#RPT-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
            <td style="font-weight: bold;">Tipe Laporan</td>
            <td>{{ $report->type_label }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tanggal</td>
            <td>{{ $report->date->format('d M Y') }}</td>
            <td style="font-weight: bold;">Pegawai</td>
            <td>{{ $report->user->name }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Gardu / No Gardu</td>
            <td>{{ $report->gardu }}</td>
            <td style="font-weight: bold;">Alamat</td>
            <td>{{ $report->address }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Latitude</td>
            <td>{{ $report->latitude ?? '-' }}</td>
            <td style="font-weight: bold;">Longitude</td>
            <td>{{ $report->longitude ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Status Admin</td>
            <td>{{ $report->status_admin }}</td>
            <td style="font-weight: bold;">Status Pimpinan</td>
            <td>{{ $report->status_pimpinan }}</td>
        </tr>

        @if($report->isRc())
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr>
                <td colspan="4" style="background-color: #f3f3f3; font-weight: bold;">DATA REMOTE CONTROL</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">RTU</td>
                <td>{{ $report->rtu ?? '-' }}</td>
                <td style="font-weight: bold;">Modem</td>
                <td>{{ $report->modem ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Lokasi</td>
                <td>{{ $report->lokasi ?? '-' }}</td>
                <td style="font-weight: bold;">Tgl Commissioning</td>
                <td>{{ $report->date_commissioning?->format('d M Y') ?? '-' }}</td>
            </tr>
            
            @if($report->rcDirections->count())
                <tr><td></td><td></td><td></td><td></td></tr>
                <tr>
                    <td colspan="4" style="background-color: #f3faf3; font-weight: bold;">ARAH REMOTE CONTROL</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">No</td>
                    <td colspan="2" style="font-weight: bold;">Arah</td>
                    <td style="font-weight: bold;">Penyulang</td>
                </tr>
                @foreach($report->rcDirections as $i => $dir)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td colspan="2">{{ $dir->arah_remote_control }}</td>
                    <td>{{ $dir->penyulang }}</td>
                </tr>
                @endforeach
            @endif
            
            @if($report->rcCommissionings->count())
                <tr><td></td><td></td><td></td><td></td></tr>
                <tr>
                    <td colspan="12" style="background-color: #f3faf3; font-weight: bold;">REMOTE CONTROL COMMISSIONING LIST</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">No</td>
                    <td style="font-weight: bold;">Database</td>
                    <td style="font-weight: bold;">Signal</td>
                    <td style="font-weight: bold;">Point</td>
                    <td style="font-weight: bold;">Terminal</td>
                    <td style="font-weight: bold;">GH</td>
                    <td style="font-weight: bold;">DCC</td>
                    <td style="font-weight: bold;">RTU</td>
                    <td style="font-weight: bold;">RELE</td>
                    <td style="font-weight: bold;">LBS</td>
                    <td style="font-weight: bold;">Arah RC</td>
                    <td style="font-weight: bold;">Status</td>
                </tr>
                @foreach($report->rcCommissionings as $i => $comm)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $comm->database_field }}</td>
                    <td>{{ $comm->point?->typeSignal?->name }}</td>
                    <td>{{ $comm->point?->name }}</td>
                    <td>{{ $comm->terminal_kubiker }}</td>
                    <td>{{ $comm->signaling_gh ? '✓' : '-' }}</td>
                    <td>{{ $comm->signaling_dcc ? '✓' : '-' }}</td>
                    <td>{{ $comm->control_dcc_rtu ? '✓' : '-' }}</td>
                    <td>{{ $comm->control_dcc_rele_plat ? '✓' : '-' }}</td>
                    <td>{{ $comm->control_dcc_lbs ? '✓' : '-' }}</td>
                    <td>{{ $comm->direction->arah_remote_control ?? '-' }} ({{ $comm->direction->penyulang ?? '-' }})</td>
                    <td>{{ $comm->note ? 'OK' : 'NOK' }}</td>
                </tr>
                @endforeach
            @endif

            @if($report->commissioning_notes)
                <tr><td></td><td></td><td></td><td></td></tr>
                <tr>
                    <td colspan="4" style="background-color: #f3faf3; font-weight: bold;">CATATAN COMMISSIONING</td>
                </tr>
                <tr>
                    <td colspan="4">{{ $report->commissioning_notes }}</td>
                </tr>
            @endif
        @endif

        @if($report->isGfd())
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr>
                <td colspan="4" style="background-color: #f3f3f3; font-weight: bold;">DATA GROUND FAULT DETECTOR</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Gardu Induk</td>
                <td>{{ $report->gardu_induk }}</td>
                <td style="font-weight: bold;">Penyulang</td>
                <td>{{ $report->penyulang }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Arah Gardu</td>
                <td>{{ $report->arah_gardu }}</td>
                <td style="font-weight: bold;">Jenis Pekerjaan</td>
                <td>{{ $report->task_type }}</td>
            </tr>
            
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr>
                <td colspan="2" style="background-color: #fff9f3; font-weight: bold;">GFD LAMA</td>
                <td colspan="2" style="background-color: #f3f9ff; font-weight: bold;">GFD BARU</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">GFD</td>
                <td>{{ $report->old_gfd }}</td>
                <td style="font-weight: bold;">GFD</td>
                <td>{{ $report->new_gfd }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Type/SN</td>
                <td>{{ $report->old_gfd_type_serial_number }}</td>
                <td style="font-weight: bold;">Type/SN</td>
                <td>{{ $report->new_gfd_type_serial_number }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Arus (A)</td>
                <td>{{ $report->old_gfd_setting_kepekaan_arus }}</td>
                <td style="font-weight: bold;">Arus (A)</td>
                <td>{{ $report->new_gfd_setting_kepekaan_arus }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Waktu (ms)</td>
                <td>{{ $report->old_gfd_setting_kepekaan_waktu }}</td>
                <td style="font-weight: bold;">Waktu (ms)</td>
                <td>{{ $report->new_gfd_setting_kepekaan_waktu }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Flashing (h)</td>
                <td>{{ $report->old_gfd_setting_waktu }}</td>
                <td style="font-weight: bold;">Flashing (h)</td>
                <td>{{ $report->new_gfd_setting_waktu }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Injek (A)</td>
                <td>{{ $report->old_gfd_injek_arus }}</td>
                <td style="font-weight: bold;">Injek (A)</td>
                <td>{{ $report->new_gfd_injek_arus }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Kondisi</td>
                <td>{{ $report->old_gfd_condition }}</td>
                <td style="font-weight: bold;">Kondisi</td>
                <td>{{ $report->new_gfd_condition }}</td>
            </tr>
            
            @if($report->gfdInspections->count())
                <tr><td></td><td></td><td></td><td></td></tr>
                <tr>
                    <td colspan="4" style="background-color: #fff9f3; font-weight: bold;">URAIAN PEMERIKSAAN GFD</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Item Pemeriksaan</td>
                    <td style="font-weight: bold;">Ada</td>
                    <td style="font-weight: bold;">Tidak Ada</td>
                    <td style="font-weight: bold;">Rusak</td>
                </tr>
                @foreach($report->gfdInspections as $insp)
                <tr>
                    <td>{{ $insp->item->name }}</td>
                    <td>{{ $insp->ada ? '✓' : '-' }}</td>
                    <td>{{ $insp->tidak_ada ? '✓' : '-' }}</td>
                    <td>{{ $insp->rusak ? '✓' : '-' }}</td>
                </tr>
                @endforeach
            @endif
        @endif
        
        @if($report->notes)
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr>
                <td colspan="4" style="background-color: #f3f3f3; font-weight: bold;">CATATAN UMUM</td>
            </tr>
            <tr>
                <td colspan="4">{{ $report->notes }}</td>
            </tr>
        @endif

        @if($report->rejection_note)
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr>
                <td colspan="4" style="background-color: #fff1f1; font-weight: bold; color: #dc2626;">CATATAN PENOLAKAN</td>
            </tr>
            <tr>
                <td colspan="4">{{ $report->rejection_note }}</td>
            </tr>
        @endif
    </tbody>
</table>
