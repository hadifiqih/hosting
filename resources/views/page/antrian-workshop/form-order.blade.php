<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead td, .table-bordered thead th {
            border-bottom-width: 2px;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th, .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
            padding: 8px;
            vertical-align: middle;
        }

        .text-end {
            text-align: end;
        }

        .spesifikasi {
            white-space: pre-line;
        }
    </style>
    <title>Form Order - PDF</title>
</head>
<body>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="2" style="text-align: center;">
                    <h5>
                        <strong style="text-align: center">No. Tiket : {{ $antrian->ticket_order }}</strong>
                    </h5>
                </th>
            </tr>
            <tr>
                <th colspan="2" style="text-align: center;">
                    <h5 style="margin: 10px 0 ;"><strong>
                        FORM ORDER
                        @if($antrian->order->is_priority == 1)
                            <span class="badge bg-danger">PRIORITAS</span>
                        @endif
                    </strong></h5>
                </th>
            </tr>
            <tr>
                <th colspan="2" style="text-align: center;">
                    <strong>Tanggal : {{ $antrian->created_at->format('d-m-Y') }}</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Nama Pelanggan:</strong></td>
                <td>{{ $antrian->customer->nama }}
                    @if($antrian->customer->frekuensi_order == '0')
                        <span class="badge bg-success">New Leads</span>
                    @elseif($antrian->customer->frekuensi_order == '1')
                        <span class="badge bg-warning">Pelanggan Baru</span>
                    @elseif($antrian->customer->frekuensi_order >= '2')
                        <span class="badge bg-danger">Repeat Order</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>No. Telepon:</strong></td>
                <td>{{ $antrian->customer->telepon }}</td>
            </tr>
            <tr>
                <td><strong>Sumber Pelanggan:</strong></td>
                <td>{{ $antrian->customer->infoPelanggan }}</td>
            </tr>
            <tr>
                <td><strong>Instansi:</strong></td>
                <td>{{ $antrian->customer->instansi }}</td>
            </tr>
            <tr>
                <td><strong>Alamat:</strong></td>
                <td>{{ $antrian->customer->alamat }}</td>
            </tr>
            <tr>
                <td><strong>Nama Project:</strong></td>
                <td>{{ $antrian->order->title }}</td>
            </tr>
            <tr>
                <td><strong>Pekerjaan:</strong></td>
                <td>{{ $antrian->job->job_name }}</td>
            </tr>
            <tr>
                <td><strong>Spesifikasi:</strong></td>
                <td class="spesifikasi">{{ $antrian->note }}</td>
            </tr>
            <tr>
                <td><strong>Qty:</strong></td>
                <td>{{ $antrian->qty }}</td>
            </tr>
            <tr>
                <td><strong>Omset:</strong></td>
                <td>Rp {{ number_format($antrian->omset, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Jumlah Pembayaran:</strong></td>
                <td>Rp {{ number_format($antrian->payment->payment_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Sisa Pembayaran:</strong></td>
                <td>Rp {{ number_format($antrian->payment->remaining_payment, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Biaya Pengiriman:</strong></td>
                <td>Rp {{ number_format($antrian->payment->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Biaya Pemasangan:</strong></td>
                <td>Rp {{ number_format($antrian->payment->installation_cost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Alamat Pengiriman / Pemasangan:</strong></td>
                <td class="spesifikasi">{{ $antrian->alamat_pengiriman }}</td>
            </tr>
            <tr>
                <td><strong>Biaya Packing:</strong></td>
                <td>Rp {{ number_format($antrian->packing_cost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Metode Pembayaran:</strong></td>
                <td>{{ $antrian->payment->payment_method }}</td>
            </tr>
            <tr>
                <td><strong>Status Pembayaran:</strong></td>
                <td>{{ $antrian->payment->payment_status }}</td>
            </tr>
            <tr>
                <td><strong>Bukti Pembayaran:</strong></td>
                <td>
                    @if($antrian->payment->payment_proof == null)
                        <span class="badge bg-danger">Belum Upload Bukti Pembayaran</span>
                    @else
                        <a href="{{ asset('storage/bukti-pembayaran/'. $antrian->payment->payment_proof) }}" target="_blank">{{ $antrian->payment->payment_proof }}</a>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <p class="text-muted text-center fst-italic">*Form ini dibuat otomatis oleh sistem. Untuk pertanyaan dapat menghubungi Sales/Admin Workshop</p>
</body>
</html>
