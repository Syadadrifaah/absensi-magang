<table width="100%">
    <tr>
        <td width="15%" align="center">
            {{-- LOGO --}}
            <img src="{{ public_path('logo/bpom.png') }}" width="90">
        </td>
        <td align="center">
            <div style="font-size:16px; font-weight:bold;">
                BALAI PENGAWAS OBAT DAN MAKANAN DI KENDARI
            </div>
            <div style="font-size:12px;">
                Kompleks Bumi Praja Anduonohu Kota Kendari Sulawesi Tenggara 93232
            </div>
            <div style="font-size:12px;">
                Telp : (0401) 3195855 ; Fax : (0401) 3195513
            </div>
            <div style="font-size:12px;">
                Email : bpom_kendari@pom.go.id ; Website : www.pom.go.id
            </div>
        </td>
    </tr>
</table>

<hr>

<br>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background-color:#f2f2f2; font-weight:bold;" align="center">
            <th>No</th>
            <th>Nama</th>
            <th>NIP</th>
            <th>Jabatan</th>
            <th>Departemen</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pegawai as $no => $p)
        <tr>
            <td align="center">{{ $no + 1 }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->nip ?? '-' }}</td>
            <td>{{ $p->position ?? '-' }}</td>
            <td>{{ $p->department ?? '-' }}</td>
            <td>{{ $p->email }}</td>
            <td>{{ $p->phone ?? '-' }}</td>
            <td>{{ $p->status_pegawai ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
