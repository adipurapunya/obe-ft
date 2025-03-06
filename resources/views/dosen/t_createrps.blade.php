<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        th, td {
            border: 1px solid black;
            padding: 2px;
            word-spacing: 0;
            text-align: left;
            line-height: 1.2;
        }

        .page-break {
            page-break-after: always;
        }

        .page-break {
            page-break-before: always;
        }

        h2, h3, h4 {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    {{-- <h4>RPS Mata Kuliah: {{ $mkscp[0]->nama_mk }}</h4> --}}

    <table cellspacing="0" cellpadding="1" border="2">
        <tr height="30">
            <td colspan="1">
                <center><img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/media/logo.png'))) }}" style="width: 100px; height:100px;"></center>
                {{-- <center><img src="{{ public_path('media/logo.png') }}" style="width: 100px; height:100px;" /></center> --}}
            </td>
            <td colspan="5">
                <center>
                    <h2>UNIVERSITAS SULTAN AGENG TIRTAYASA</h2>
                    <h2>FAKULTAS TEKNIK</h2>
                    @foreach($mkscp->unique('nama_prodi') as $mk)
                        <h3>{{$mk->nama_prodi}}</h3>
                    @endforeach
                </center>
            </td>
            <td colspan="1">
                No.xx <br>
                Revisi ke: xx
            </td>
        </tr>
        <tr height="15">
            <td colspan="7">
                <center><h4>PEMBELAJARAN SEMESTER</h4></center>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">MATA KULIAH (MK)</td>
            <td style="text-align: center;">KODE</td>
            <td style="text-align: center;">RUMPUN MK</td>
            <td colspan="2" style="text-align: center;">BOBOT SKS</td>
            <td style="text-align: center;">SEMESTER</td>
            <td style="text-align: center;">TGL. PENYUSUNAN</td>
        </tr>
        @foreach($mkscp->unique('nama_mk') as $mk )
        <tr style="text-align: center">
            <td style="text-align: center;">{{ $mk->nama_mk}}</td>
            <td style="text-align: center;">{{ $mk->kode_mk}}</td>
            <td style="text-align: center;">{{ $mk->rumpun_mk}}</td>
            <td style="text-align: center;">T = {{ $mk->sks_teo}}</td>
            <td style="text-align: center;">P = {{ $mk->sks_prak}}</td>
            <td style="text-align: center;">{{ $mk->nama_smtr}}</td>
            <td style="text-align: center;">{{ Carbon\Carbon::parse($mk->tgl_susun)->format('d-m-Y')}}</td>
        </tr>
        @endforeach
        <tr>
            <td>OTORISASI/PENGESAHAN</td>

            <td colspan="2">Dosen Pengembang RPS<br><br><br>
                {{ $dosenPengembang->nama_dosen }}
            </td>

            @foreach($kajur->unique('nama_dosen') as $kj)
            <td colspan="2">Koordinator Keahlian<br><br><br>
                {{$kj->nama_dosen}}
            </td>
            @endforeach
            <td colspan="2">Kaprodi<br><br><br>
                {{ $ketuaJurusan->nama_ketua_jurusan}}
            </td>
        </tr>
        <tr>
            <td>Capaian Pembelajaran</td>
            <td colspan="6">CPL Prodi yang dibebankan kepada MK</td>
        </tr>
        {{-- @foreach($mkscp->unique('kode_cpl') as $cp)
        <tr>
            <td></td>
            <td colspan="2">{{ $cp->kode_cpl}}</td>
            <td colspan="4"></td>
        </tr>
        @endforeach --}}
                @foreach($mkscp->groupBy('kode_cpl') as $kode_cpl => $subcplGroup)
                    @php
                        // Ambil deskripsi CPL untuk kode CPL ini
                        $deskripsiCpl = $subcplGroup->first()->deskrip_cpl ?? '';
                    @endphp
                    <tr>
                        <td></td>
                        <td colspan="2">{{ $kode_cpl }}</td>
                        <td colspan="4">
                            <!-- Tampilkan deskripsi CPL sebelum daftar sub-CPL -->
                            {{ $deskripsiCpl }}<br><br>
                            @foreach($subcplGroup->unique(function ($item) {
                                return $item->kode_subcpl . $item->desk_subcpl;
                            }) as $subcpl)
                                {{ $subcpl->kode_subcpl }} - {{ $subcpl->desk_subcpl }}<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach


            <tr>
                <td></td>
                <td colspan="6">CPMK Pembelajaran Mata Kuliah (CPMK)</td>
            </tr>

            @foreach($mkscp->unique('kode_cpmk') as $cpmk)
            <tr>
                <td></td>
                <td colspan="2">{{ $cpmk->kode_cpmk}}</td>
                <td colspan="4">{{ $cpmk->desk_cpmk}}</td>
            </tr>
            @endforeach

            <tr>
                <td></td>
                <td colspan="6">Sub CPMK</td>
            </tr>

            @foreach($mkscp->unique('kode_scpmk') as $subcp)
            <tr>
                <td></td>
                <td colspan="2">{{ $subcp->kode_scpmk }}</td>
                <td colspan="4">{{ $subcp->desk_scpmk}}</td>
            </tr>
            @endforeach

            @foreach($rps->unique('desk_singkat') as $rp)
            <tr>
                <td>Deskripsi Singkat</td>
                <td colspan="6">{!! $rp->desk_singkat !!}</td>
            </tr>
            @endforeach

            @foreach($rps->unique('kajian') as $rp)
            <tr>
                <td>Materi Pembelajaran (Kajian)</td>
                <td colspan="6">{!! $rp->kajian !!}</td>
            </tr>
            @endforeach

            @foreach($rps->unique('pustaka') as $rp)
            <tr>
                <td>Pustaka</td>
                <td colspan="6">{!! $rp->pustaka !!}</td>
            </tr>
            @endforeach

            <tr>
                <td>Dosen Pengampu</td>
                <td colspan="6">{{ $dosenPengembang->nama_dosen }}</td>
            </tr>

            @foreach($rps->unique('mk_syarat') as $rp)
            <tr>
                <td>Mata Kuliah Syarat</td>
                <td colspan="6">{!! $rp->mk_syarat !!}</td>
            </tr>
            @endforeach
    </table><br>

    <table class="page-break">
        <tr style="text-align: center">
            <td style="text-align: center;" rowspan="2">Pekan ke</td>
            <td style="text-align: center;" rowspan="2">Kemampuan Akhir tiap tahapan belajar (Sub-CPMK)</td>
            <td style="text-align: center;" colspan="2">Penilaian</td>
            <td style="text-align: center;" colspan="2">Bentuk Pembelajaran;
                Metode Pembelajaran;
                Penugasan Mahasiswa;
                [Estimasi Waktu]
            </td>
            <td style="text-align: center;" rowspan="2">Materi Pembelajaran [Pustaka]</td>
            <td style="text-align: center;" rowspan="2">Bobot Penilaian (%)</td>
        </tr>
        <tr style="text-align: center">
            <td style="text-align: center;">Indikator</td>
            <td style="text-align: center;">Kriteria dan Teknik</td>
            <td style="text-align: center;">Luring</td>
            <td style="text-align: center;">Daring</td>
        </tr>

        <tr>
            <td style="text-align: center;">(1)</td>
            <td style="text-align: center;">(2)</td>
            <td style="text-align: center;">(3)</td>
            <td style="text-align: center;">(4)</td>
            <td style="text-align: center;">(5)</td>
            <td style="text-align: center;">(6)</td>
            <td style="text-align: center;">(7)</td>
            <td style="text-align: center;">(8)</td>
        </tr>

        @foreach($rps->unique('pekan_ke') as $rp)
        <tr>
            <td><center>{{ $rp->pekan_ke}}</center></td>
            <td>{{ $rp->kode_scpmk}}</td>
            <td>{!! $rp->indikator !!}</td>
            <td>{!! $rp->kritek !!}</td>
            <td>{!! $rp->luring !!}</td>
            <td>{!! $rp->daring !!}</td>
            <td>{!! $rp->mat_pustaka !!}</td>
            <td><center>{!! $rp->bobot_nil !!}</center></td>
        </tr>
        @endforeach
    </table><br>
    <table style="border: none; border-collapse: collapse; width: 100%;">
        @foreach($rps->unique('keterangan') as $rp)
        <tr style="border: none;">
            <td colspan="8" style="border: none;">{!! $rp->keterangan !!}</td>
        </tr>
        @endforeach

        @foreach($rps->unique('lampiran') as $rp)
        <tr style="border: none;">
            <td colspan="8" style="border: none;">{!! $rp->lampiran !!}</td>
        </tr>
        @endforeach
    </table>

</body>
</html>
