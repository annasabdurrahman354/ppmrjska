<!DOCTYPE html>
<html>
<head>
	<title>Jadwal Munaqosah</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 10pt;
		}
        table {
            display: table;
        }
        table tr {
            display: table-cell;
        }
        table tr td {
            display: block;
        }
	</style>
	<center>
		<h5>PLOTINGAN MUNAQOSAH</h5>
	</center>
    <p style="text-align: center;">Diperbarui: {{now()}}</p>

    @foreach($array as $angkatan => $array_materi)
        <br>
        <div style="width:100%; margin-top: 10pt; margin-bottom: 0pt; font-size: 12pt; font-weight: bold;">Kelas {{$angkatan}}</div>
        @foreach($array_materi as $materi)
        <div style="width:100%;">
            <p style="margin-bottom: 0pt;">- Materi Makna: {{implode(',',$materi->materi)." (".$materi->detail.")"}}</p>
            <p style="margin-bottom: 0pt;">- Hafalan: {{($materi->hafalan ? "Tidak ada" : implode(',', $materi->hafalan))}}</p>
            <table style="width:100%; margin-top: 5pt; margin-bottom: 5pt; float: left;" border="1" cellpadding="5">
                <tbody>
                @foreach($materi['jadwalMunaqosah'] as $jadwalMunaqosah)
                <tr>
                    <td>{{$jadwalMunaqosah->waktu}}</td>
                    @foreach($jadwalMunaqosah->plotJadwalMunaqosah as $plot)
                        <td>{{$plot->user->nama}}</td>
                    @endforeach
                </tr>
                @endforeach
                </tbody>
            </table>
        <div>
        @endforeach
    @endforeach
</body>
</html>
