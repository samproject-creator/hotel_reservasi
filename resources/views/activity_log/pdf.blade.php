<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Audit Trail - PDF Export</title>
    <style>
        @page { margin: 1cm; }
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #0f172a;
            font-size: 10px;
            line-height: 1.3;
        }
        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px dashed #4c1d95;
            padding-bottom: 10px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            color: #4c1d95;
        }
        .meta {
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #4c1d95;
            color: #ffffff;
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
        }
        .data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .action-tag {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td>
                <div class="title">SYSTEM AUDIT TRAIL LOG REPORT</div>
                <div style="color: #64748b; margin-top: 2px;">Data Keamanan & Histori Mutasi Operasional Hotel</div>
            </td>
            <td class="meta">
                <strong>Total Batasan Cetak:</strong> {{ count($logs) }} Baris Max<br>
                <strong>Waktu Ekspor:</strong> {{ now()->format('Y-m-d H:i:s') }}
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 18%;">Waktu Sistem</th>
                <th style="width: 17%;">Operator User</th>
                <th style="width: 12%;">Modul</th>
                <th style="width: 12%;">Aksi</th>
                <th style="width: 41%;">Deskripsi Log</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td style="color: #64748b;">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td style="font-weight: bold;">{{ $log->user->name ?? 'System Automated' }}</td>
                <td>{{ strtoupper($log->module) }}</td>
                <td>
                    <span class="action-tag">
                        {{ strtoupper($log->action) }}
                    </span>
                </td>
                <td>{{ $log->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>