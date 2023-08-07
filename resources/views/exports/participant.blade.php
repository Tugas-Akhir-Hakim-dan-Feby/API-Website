<table>
    <thead>
        <tr>
            <th>nik</th>
            <th>email</th>
            <th>nama</th>
            <th>skema_uji_kompetensi</th>
            <th>penilaian</th>
            <th>sertifikat</th>
            <th>catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($participants as $participant)
            <tr>
                <td>{{ $participant->user->welderMember->resident_id_card ?? '-' }}</td>
                <td>{{ $participant->user->email }}</td>
                <td>{{ $participant->user->name }}</td>
                <td>{{ $participant->examPacket->competenceSchema->skill_name }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
