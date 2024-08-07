<table style="border: 1px">
    <thead>
        <tr>
            <th style="border: 1px; text-align: start; vertical-align: center" colspan="{{ array_sum(array_map('count', $distinctTanggalSesi)) + 14 }}">Bulan: {{ucfirst($bulan)}}</th>
        </tr>
        <tr>
            <th style="border: 1px; text-align: start; vertical-align: center" colspan="{{ array_sum(array_map('count', $distinctTanggalSesi)) + 14 }}">Kelas: {{implode(',', $kelas)}}</th>
        </tr>
        <tr>
            <th style="border: 1px; text-align: start; vertical-align: center" colspan="{{ array_sum(array_map('count', $distinctTanggalSesi)) + 14 }}">Santri: {{ucfirst($jenis_kelamin)}}</th>
        </tr>
        <tr>
            <th style="border: 1px; text-align: center; vertical-align: center" rowspan="2">No</th>
            <th style="border: 1px; text-align: center; vertical-align: center" rowspan="2">Nama</th>
            <th style="border: 1px; text-align: center; vertical-align: center" rowspan="2">Kelas</th>
        @foreach($distinctTanggalSesi as $date => $sessions)
                <th style="border: 1px; text-align: center; vertical-align: center" colspan="{{ count($sessions) }}">{{  Carbon\Carbon::parse($date)->format('d') }}</th>
            @endforeach
            <th style="border: 1px; text-align: center; vertical-align: center" colspan="5">Jumlah</th>
            <th style="border: 1px; text-align: center; vertical-align: center" rowspan="2">Total Pertemuan</th>
            <th style="border: 1px; text-align: center; vertical-align: center" colspan="5">Persen</th>
        </tr>
        <tr>
            @foreach($distinctTanggalSesi as $date => $sessions)
                @foreach($sessions as $session)
                    <th style="border: 1px; text-align: center; vertical-align: center">
                        @switch($session->value)
                            @case('subuh')
                                S
                                @break
                            @case('pagi 1')
                                P1
                                @break
                            @case('pagi 2')
                                P2
                                @break
                            @case('siang')
                                S
                                @break
                            @case('malam')
                                M
                                @break
                            @default
                                <span>ERROR</span>
                        @endswitch
                    </th>
                @endforeach
            @endforeach
            <th style="border: 1px; text-align: center; vertical-align: center">H</th>
            <th style="border: 1px; text-align: center; vertical-align: center">T</th>
            <th style="border: 1px; text-align: center; vertical-align: center">I</th>
            <th style="border: 1px; text-align: center; vertical-align: center">S</th>
            <th style="border: 1px; text-align: center; vertical-align: center">A</th>
            <th style="border: 1px; text-align: center; vertical-align: center">H</th>
            <th style="border: 1px; text-align: center; vertical-align: center">T</th>
            <th style="border: 1px; text-align: center; vertical-align: center">I</th>
            <th style="border: 1px; text-align: center; vertical-align: center">S</th>
            <th style="border: 1px; text-align: center; vertical-align: center">A</th>
        </tr>
    </thead>
    <tbody>
    @foreach($attendanceData as $student)
        <tr>
            <td style="border: 1px; text-align: start; vertical-align: center">{{ $student['no'] }}</td>
            <td style="border: 1px; text-align: start; vertical-align: center">{{ $student['nama'] }}</td>
            <td style="border: 1px; text-align: start; vertical-align: center">{{ $student['kelas'] }}</td>
        @foreach($distinctTanggalSesi as $date => $sessions)
                @foreach($sessions as $session)
                    @if(isset($student['tanggal'][$date]['sesi'][$session->value]))
                        @switch($student['tanggal'][$date]['sesi'][$session->value])
                            @case('hadir')
                                <td style="border: 1px; vertical-align: center; text-align: center; background-color: rgb(34 197 94)">
                                    H
                                </td>
                                @break
                            @case('telat')
                                <td style="border: 1px; vertical-align: center; text-align: center;  background-color: rgb(101 163 13)">
                                    T
                                </td>
                                @break
                            @case('izin')
                                <td style="border: 1px; vertical-align: center; text-align: center; background-color: rgb(234 179 8)">
                                    I
                                </td>
                                @break
                            @case('sakit')
                                <td style="border: 1px; vertical-align: center; text-align: center; background-color: rgb(59 130 246)">
                                    S
                                </td>
                                @break
                            @case('alpa')
                                <td style="border: 1px; vertical-align: center; text-align: center;  background-color: rgb(239 68 68)">
                                    A
                                </td>
                                @break
                            @default
                                <td style="border: 1px; vertical-align: center; text-align: center">
                                    &nbsp;
                                </td>
                        @endswitch
                    @else
                        <td style="border: 1px; text-align: center">
                            &nbsp;
                        </td>
                    @endif
                @endforeach
            @endforeach
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['jumlah']['hadir'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['jumlah']['telat'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['jumlah']['izin'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['jumlah']['sakit'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['jumlah']['alpa'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['total_pertemuan'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['persen']['hadir'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['persen']['telat'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['persen']['izin'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['persen']['sakit'] }}</td>
            <td style="border: 1px; text-align: end; vertical-align: center">{{ $student['persen']['alpa'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
