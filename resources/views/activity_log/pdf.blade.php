<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LuxeHotel - System Audit Trail</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.5;
        }
        /* Header Tema LuxeHotel */
        .header {
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .brand {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            color: #0f172a;
        }
        .brand span {
            color: #d97706; /* Accent Gold */
        }
        .subtitle {
            font-size: 10px;
            color: #64748b;
            margin-top: 5px;
            font-weight: 500;
        }
        .meta-info {
            float: right;
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
        }
        /* Tabel Jurnal */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 10px;
            text-align: left;
        }
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .timestamp {
            font-family: Courier, monospace;
            color: #64748b;
        }
        .operator {
            font-weight: bold;
            color: #0f172a;
        }
        /* Badge Operasi */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-created { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-updated { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-deleted { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        
        .description {
            color: #334155;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="meta-info">
            Generated: {{ now()->format('d M Y H:i:s') }}<br>
            Scope: Internal Security Ledger
        </div>
        <div class="brand">Luxe<span>Hotel</span></div>
        <div class="subtitle">FORENSIC TIMELINE OF CRITICAL SYSTEM OPERATIONS</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Timestamp</th>
                <th style="width: 25%;">Operator</th>
                <th style="width: 15%;">Operation</th>
                <th style="width: 40%;">Audit Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="timestamp">{{ $log->created_at->format('d M Y • H:i') }}</td>
                <td class="operator">{{ $log->user->name ?? 'System Process' }}</td>
                <td>
                    <span class="badge badge-{{ $log->action }}">
                        {{ $log->action }}
                    </span>
                </td>
                <td class="description">"{{ $log->description }}"</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>