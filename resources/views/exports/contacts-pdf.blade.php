<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Contact Messages Export</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #fff;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 11px;
            opacity: .85;
        }

        .header-meta {
            margin-top: 8px;
            font-size: 10px;
            opacity: .75;
        }

        /* Stats */
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 16px;
            border-collapse: separate;
            border-spacing: 8px;
        }

        .stat-box {
            display: table-cell;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 14px;
            text-align: center;
            width: 25%;
        }

        .stat-num {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
        }

        .stat-label {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        thead tr {
            background: #4f46e5;
            color: white;
        }

        thead th {
            padding: 9px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .04em;
            border: 1px solid #4338ca;
        }

        tbody tr:nth-child(even) {
            background: #f8f9ff;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        tbody td {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            line-height: 1.5;
        }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 9px;
        }

        /* Page break */
        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h1>Contact Messages Report</h1>
        <p>Complete list of contact form submissions</p>
        <div class="header-meta">
            Generated on: {{ now()->format('d M Y, h:i A') }}
            &nbsp;·&nbsp;
            Total Records: {{ $contacts->count() }}
            @if ($status)
                &nbsp;·&nbsp; Filter: {{ ucfirst($status) }}
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <table class="stats">
        <tr>
            <td class="stat-box">
                <div class="stat-num">{{ $stats['total'] }}</div>
                <div class="stat-label">Total</div>
            </td>
            <td class="stat-box">
                <div class="stat-num" style="color:#dc2626;">{{ $stats['unread'] }}</div>
                <div class="stat-label">Unread</div>
            </td>
            <td class="stat-box">
                <div class="stat-num" style="color:#d97706;">{{ $stats['read'] }}</div>
                <div class="stat-label">Read</div>
            </td>
            <td class="stat-box">
                <div class="stat-num" style="color:#059669;">{{ $stats['replied'] }}</div>
                <div class="stat-label">Replied</div>
            </td>
        </tr>
    </table>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:14%">Name</th>
                <th style="width:18%">Email</th>
                <th style="width:11%">Phone</th>
                <th style="width:16%">Subject</th>
                <th style="width:22%">Message</th>
                <th style="width:8%">Status</th>
                <th style="width:10%">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $i => $contact)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $contact->name }}</strong></td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->phone ?? '—' }}</td>
                    <td>{{ $contact->subject ?? '—' }}</td>
                    <td>{{ Str::limit($contact->message, 80) }}</td>
                    <td>
                        <span class="badge badge-{{ $contact->status_badge }}">
                            {{ ucfirst($contact->status) }}
                        </span>
                    </td>
                    <td>{{ $contact->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:#9ca3af;padding:20px;">
                        No contacts found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <p>
            {{ config('app.name') }} — Contact Messages Export
            &nbsp;·&nbsp;
            {{ now()->format('d M Y') }}
            &nbsp;·&nbsp;
            Page <span class="pagenum"></span>
        </p>
    </div>

</body>

</html>
