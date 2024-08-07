@props(['attendanceData', 'distinctTanggalSesi', 'jenis_kelamin', 'bulan', 'kelas'])
<table class="bg-white dark:bg-gray-800" style="table-layout: auto; border-width: 1px; border-color: rgb(203 213 225); font-size: 0.875rem; line-height: 1.25rem;" cellpadding="5">
    <thead>
        <tr>
            <th class="border border-slate-300 text-start" colspan="{{ array_sum(array_map('count', $distinctTanggalSesi)) + 14 }}">Bulan: {{ucfirst($bulan)}}</th>
        </tr>
        <tr>
            <th class="border border-slate-300 text-start" colspan="{{ array_sum(array_map('count', $distinctTanggalSesi)) + 14 }}">Kelas: {{implode(',', $kelas)}}</th>
        </tr>
        <tr>
            <th class="border border-slate-300 text-start" colspan="{{ array_sum(array_map('count', $distinctTanggalSesi)) + 14 }}">Santri: {{ucfirst($jenis_kelamin)}}</th>
        </tr>
        <tr>
            <th class="border border-slate-300 text-center" rowspan="2">No</th>
            <th class="border border-slate-300 text-center" rowspan="2">Nama</th>
            <th class="border border-slate-300 text-center" rowspan="2">Kelas</th>
        @foreach($distinctTanggalSesi as $date => $sessions)
                <th class="border border-slate-300 text-center" colspan="{{ count($sessions) }}">{{  Carbon\Carbon::parse($date)->format('d') }}</th>
            @endforeach
            <th class="border border-slate-300 text-center" colspan="5">Jumlah</th>
            <th class="border border-slate-300 text-center" rowspan="2">Total<br>Pertemuan</br></th>
            <th class="border border-slate-300 text-center" colspan="5">Persen</th>
        </tr>
        <tr>
            @foreach($distinctTanggalSesi as $date => $sessions)
                @foreach($sessions as $session)
                    <th class="border border-slate-300 text-center">
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
            <th class="border border-slate-300 text-center">H</th>
            <th class="border border-slate-300 text-center">T</th>
            <th class="border border-slate-300 text-center">I</th>
            <th class="border border-slate-300 text-center">S</th>
            <th class="border border-slate-300 text-center">A</th>
            <th class="border border-slate-300 text-center">H</th>
            <th class="border border-slate-300 text-center">T</th>
            <th class="border border-slate-300 text-center">I</th>
            <th class="border border-slate-300 text-center">S</th>
            <th class="border border-slate-300 text-center">A</th>
        </tr>
    </thead>
    <tbody>
    @foreach($attendanceData as $student)
        <tr>
            <td class="border border-slate-300 text-start">{{ $student['no'] }}</td>
            <td class="border border-slate-300 text-start">{{ $student['nama'] }}</td>
            <td class="border border-slate-300 text-start">{{ $student['kelas'] }}</td>
            @foreach($distinctTanggalSesi as $date => $sessions)
                @foreach($sessions as $session)
                    @if(isset($student['tanggal'][$date]['sesi'][$session->value]))
                        @switch($student['tanggal'][$date]['sesi'][$session->value])
                            @case('hadir')
                                <td class="border border-slate-300 text-center" style="background-color: rgb(34 197 94)">
                                    H
                                </td>
                                @break
                            @case('telat')
                                <td class="border border-slate-300 text-center" style="background-color: rgb(101 163 13)">
                                    T
                                </td>
                                @break
                            @case('izin')
                                <td class="border border-slate-300 text-center" style="background-color: rgb(234 179 8)">
                                    I
                                </td>
                                @break
                            @case('sakit')
                                <td class="border border-slate-300 text-center" style="background-color: rgb(59 130 246)">
                                    S
                                </td>
                                @break
                            @case('alpa')
                                <td class="border border-slate-300 text-center" style="background-color: rgb(239 68 68)">
                                    A
                                </td>
                                @break
                            @default
                                <td class="border border-slate-300 text-center">
                                    &nbsp;
                                </td>
                        @endswitch
                    @else
                        <td class="border border-slate-300 text-center">
                            &nbsp;
                        </td>
                    @endif
                @endforeach
            @endforeach
            <td class="border border-slate-300 text-end">{{ $student['jumlah']['hadir'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['jumlah']['telat'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['jumlah']['izin'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['jumlah']['sakit'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['jumlah']['alpa'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['total_pertemuan'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['persen']['hadir'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['persen']['telat'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['persen']['izin'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['persen']['sakit'] }}</td>
            <td class="border border-slate-300 text-end">{{ $student['persen']['alpa'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
