<table>
    <tr>
        <td rowspan="4" style="text-align: center; vertical-align: middle; border-top: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000;"></td>
        <td colspan="4" style="border-top: 1px solid #000;">PT PLN (PERSERO)</td>
        <td colspan="2" rowspan="4" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">FORMULIR</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle; font-size: 9pt;">NO DOKUMEN</td>
        <td style="border: 1px solid #000; font-size: 9pt;">Hal :</td>
        <td rowspan="4" style="text-align: center; vertical-align: middle; border: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="4">UP2D JAWA BARAT</td>
        <td colspan="2" rowspan="3" style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000; font-size: 9pt;">Tgl :</td>
    </tr>
    <tr>
        <td colspan="4">JL. Dr Ir Soekarno No.3 Bandung</td>
        <td style="border: 1px solid #000; font-size: 9pt;">Rev :</td>
    </tr>
    <tr>
        <td colspan="4" style="border-bottom: 1px solid #000;">TLP : (022)4230747 , FAX (022) 4239079</td>
        <td style="border: 1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="11" rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">DENAH DAN SLD REMOTE CONTROL GARDU DISTRIBUSI</td>
    </tr>
    <tr>
        @for($i = 0; $i < 11; $i++) <td></td> @endfor
    </tr>
    <tr>
        <td colspan="11" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000; height: 30px; background-color: #D9D9D9;">DENAH GARDU</td>
    </tr>
    @for($i = 0; $i < 18; $i++)
    <tr>
        <td colspan="11" style="border-left: 1px solid #000; border-right: 1px solid #000; {{ $i === 17 ? 'border-bottom: 1px solid #000;' : '' }}"></td>
    </tr>
    @endfor
    <tr>
        <td colspan="11" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000; height: 30px; background-color: #D9D9D9;">SINGLE LINE DIAGRAM</td>
    </tr>
    @for($i = 0; $i < 18; $i++)
    <tr>
        <td colspan="11" style="border-left: 1px solid #000; border-right: 1px solid #000; {{ $i === 17 ? 'border-bottom: 1px solid #000;' : '' }}"></td>
    </tr>
    @endfor
    <tr>
        <td colspan="4" rowspan="6" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: top; border: 1px solid #000;">DIREKSI PEKERJAAN</td>
        <td colspan="4" rowspan="6" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: top; border: 1px solid #000;">PENGAWAS PEKERJAAN</td>
        <td colspan="3" rowspan="6" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: top; border: 1px solid #000;">PT. WAHANA CAHAYA SUKSES</td>
    </tr>
    @for($i = 0; $i < 5; $i++)
    <tr>
        <td></td>
    </tr>
    @endfor
    <tr>
        <td colspan="4" rowspan="2" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: middle; border: 1px solid #000;">(VERY INDRA PRATAMA)</td>
        <td colspan="4" rowspan="2" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: middle; border: 1px solid #000;">(......................................................)</td>
        <td colspan="3" rowspan="2" style="font-family: 'Franklin Gothic Book'; font-size: 12pt; text-align: center; vertical-align: middle; border: 1px solid #000;">(......................................................)</td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>