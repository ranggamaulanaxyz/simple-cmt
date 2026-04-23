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
        <td colspan="15" rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">FOTO DOKUMENTASI PEMASANGAN REMOTE CONTROL GARDU HUBUNG DAN GARDU DISTRIBUSI</td>
    </tr>
    @for($i = 0; $i < 4; $i++)
        <tr>@for($j = 0; $j < 16; $j++)
        <td></td> @endfor
        </tr>
    @endfor
    @foreach($report->images->chunk(2) as $chunk)
        @for($i = 0; $i < 16; $i++)
            <tr>@for($j = 0; $j < 16; $j++) <td></td> @endfor</tr>
        @endfor
        <tr>
            @for($j = 0; $j < 16; $j++) <td></td> @endfor
        </tr>
    @endforeach
</table>