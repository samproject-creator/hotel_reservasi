<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h3>LUXEHOTEL SYSTEM AUDIT TRAIL</h3>
    
    @if($dari && $sampai)
        <p>Periode: {{ $dari->format('d/m/Y') }} s/d {{ $sampai->format('d/m/Y') }}</p>
    @else
        <p>Periode: Semua Riwayat Data Logs</p>
    @endif
    
    <p>Tanggal Unduh: {{ now()->format('d/m/Y H:i') }} WIB</p>

    <table border="1">
        <thead>
            <tr style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center;">
                <th>Timestamp</th>
                <th>Modul</th>
                <th>Operator</th>
                <th>Operation</th>
                <th>Audit Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td style="text-align: center; color: #475569;">
                    {{ $log->created_at->format('d/m/Y H:i') }}
                </td>
                <td style="text-align: center; font-weight: bold; color: #475569;">
                    {{ strtoupper($log->module) }}
                </td>
                <td style="font-weight: bold; color: #0f172a;">
                    {{ $log->user->name ?? 'System Process' }}
                </td>
                <td style="text-align: center; font-weight: bold; 
                    @if($log->action === 'created') color: #16a34a; 
                    @elseif($log->action === 'updated') color: #2563eb; 
                    @else color: #dc2626; @endif">
                    {{ strtoupper($log->action) }}
                </td>
                <td style="color: #334155; font-style: italic;">
                    "{{ $log->description }}"
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>