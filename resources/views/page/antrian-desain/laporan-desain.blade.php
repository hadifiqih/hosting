<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-SPK (Surat Perintah Kerja)</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            background-color: black;
            color: #fff;
            padding: 10px;
            /* Background rounded */
            border-radius: 10px;
        }

        .title {
            font-size: 30px;
            font-weight: bold;
            margin: 0;
        }

        .info {
            margin-top: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        .job-details {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 10px;
        }

        .job-details h2 {
            font-size: 18px;
            margin-bottom: 10px;
            margin-top: 3px;
        }

        .job-details p {
            margin: 5px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: grey;
            font-style: italic;
        }

        .serial-number {
            margin-top: 6px;
            font-size: 15px;
            font-weight: bold;
            color: #ffffff;
        }

        .text-danger {
            color: #dc3545;
        }

        /* Align text left <tr></tr> */
        .text-left {
            text-align: left;
        }

        .table {
            margin-top: 10px;
        }

        .table td {
            padding: 5px 10px;
        }

        .table-warning {
            background-color: #fff3cd;
        }

        .table-danger {
            background-color: #f8d7da;
        }

        .text-success {
            color: #28a745;
        }

        .text-center {
            text-align: center;
        }

        .table-responsive {
            width: 100%;
        }

        .ml-2 {
            margin-left: 20px;
        }

        .bg-success {
            background-color: #28a745;
            color: #fff;
            border-radius: 20px;
        }

        .bg-danger {
            background-color: #dc3545;
            color: #fff;
            border-radius: 20px;
        }

        .bg-outline-success {
            background-color: #cefad0;
            color: #343a40;
            border-radius: 20px;
        }

        .bg-outline-danger {
            background-color: #f8d7da;
            color: #343a40;
            border-radius: 20px;
        }

        .table-header {
            width: 15%;
            text-align: left;
        }

        .px-3 {
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .signature {
            text-align: right;
            margin-top: 50px;
            margin-right: 50px;
        }

        .signature p {
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Surat Perintah Kerja (e-SPK)</div>
        <div class="serial-number">Nomor SPK: 20230912002</div>
    </div>

    <table class="table table-responsive text-center">
        <tr>
            <td width="50%"><strong class="text-success">Mulai</strong></td>
            <td width="50%"><strong class="text-danger">Deadline</strong></td>
        </tr>
        <tr>
            <td class="bg-outline-success px-3">2023-12-12 16:10</td>
            <td class="bg-outline-danger px-3">2023-12-12 17:00</td>
        </tr>
    </table>

    <div class="job-details">
        <h2>Spesifikasi Pekerjaan</h2>
        <hr>
        <table class="table text-left">
            <tbody>
                <tr>
                    <th class="table-header">Jenis Pekerjaan</th>
                    <td>: Cetak Brosur</td>
                </tr>
                <tr>
                    <th class="table-header">Jumlah</th>
                    <td>: 1000 buah</td>
                </tr>
                <tr>
                    <th class="table-header">Deskripsi</th>
                    <td>: Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea nisi quod assumenda dolorem nihil enim sequi hic, sed, explicabo, quidem officia quos nesciunt. Quisquam error fuga fugit consequuntur tempore aliquam.</td>
                </tr>
                <tr>
                    <th class="table-header">Lokasi Workshop</th>
                    <td>: Malang</td>
                </tr>
                <tr>
                    <th class="table-header">Operator</th>
                    <td>: Angga Ardian, M. Hadi Fiqih Pratama</td>
                </tr>
                <tr>
                    <th class="table-header">Finishing</th>
                    <td>: Indra Ravidsyah</td>
                </tr>
                <tr>
                    <th class="table-header">Pengawas / QC</th>
                    <td>: Abdul Ghofar</td>
                </tr>
                <tr>
                    <th class="table-header">Perkiraan Waktu</th>
                    <td>: 1 jam</td>
                </tr>
            </tbody>

        </table>
    </div>

    <div class="footer">
        *Surat Perintah Kerja ini dibuat secara otomatis oleh sistem e-SPK, jika ada kesalahan / pertanyaan silahkan hubungi Admin Workshop / Sales.
    </div>
</body>
</html>
