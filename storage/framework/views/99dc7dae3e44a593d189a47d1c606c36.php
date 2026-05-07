<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan SPPD <?php echo e($sppd['nomor_sppd'] ?? ''); ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #273142;
        }

        .page {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 16px;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .header {
            padding: 28px 32px;
            border-bottom: 1px solid #e9eef5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .header h1 {
            margin: 8px 0 0;
            font-size: 20px;
        }

        .badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            background: #e8f3ff;
            color: #1976d2;
            font-weight: 700;
        }

        .body {
            padding: 32px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px 40px;
            margin-bottom: 32px;
        }

        .label {
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .value {
            font-size: 16px;
            color: #526176;
            line-height: 1.5;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .no-print {
            display: flex;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: #1f7ae0;
            color: #fff;
        }

        .btn-light {
            background: #fff;
            color: #273142;
            border-color: #d8e0eb;
        }

        .section-title {
            margin: 0 0 16px;
            padding-top: 24px;
            border-top: 1px dashed #d8e0eb;
            font-size: 22px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 16px;
        }

        th, td {
            border: 1px solid #e5ebf3;
            padding: 16px;
            text-align: left;
        }

        thead th, tfoot th {
            background: #eef4fb;
            color: #273142;
        }

        .text-end {
            text-align: right;
        }

        @media (max-width: 768px) {
            .header,
            .body {
                padding: 20px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #ffffff;
            }

            .page {
                margin: 0;
                padding: 0;
                max-width: 100%;
            }

            .card {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
<?php
    $start = !empty($sppd['tanggal_berangkat']) ? \Carbon\Carbon::parse($sppd['tanggal_berangkat']) : null;
    $end = !empty($sppd['tanggal_pulang']) ? \Carbon\Carbon::parse($sppd['tanggal_pulang']) : null;
    $days = ($start && $end) ? $start->diffInDays($end) + 1 : null;
    $total = collect($expense)->sum('jumlah');
    $pdfFilename = 'ringkasan-sppd-' . \Illuminate\Support\Str::slug((string) ($sppd['nomor_sppd'] ?? 'dokumen')) . '.pdf';
?>

<div class="page">
    <div class="card" id="public-sppd-card">
        <div class="header">
            <div>
                <img src="/images/ias.png" alt="IAS" style="height:64px;">
                <h1>Ringkasan SPPD: <?php echo e($sppd['nomor_sppd'] ?? '-'); ?></h1>
            </div>
            <span class="badge"><?php echo e($sppd['status'] ?? 'DRAFT'); ?></span>
        </div>

        <div class="body">
            <div class="actions no-print">
                <button type="button" class="btn btn-primary" id="download-summary-pdf">Download PDF</button>
                <a href="javascript:window.print()" class="btn btn-light">Print</a>
            </div>

            <div class="grid">
                <div>
                    <div class="label">Diberikan Kepada</div>
                    <div class="value"><?php echo e($sppd['user']['name'] ?? '-'); ?></div>
                </div>
                <div>
                    <div class="label">Nomor SPPD</div>
                    <div class="value"><?php echo e($sppd['nomor_sppd'] ?? '-'); ?></div>
                </div>
                <div>
                    <div class="label">NIK</div>
                    <div class="value"><?php echo e($sppd['user']['employee']['employee_number'] ?? '-'); ?></div>
                </div>
                <div>
                    <div class="label">Divisi & Jabatan</div>
                    <div class="value"><?php echo e($sppd['user']['employee']['division']['name'] ?? '-'); ?> - <?php echo e($sppd['user']['employee']['position']['name'] ?? '-'); ?></div>
                </div>
                <div>
                    <div class="label">Tujuan Perjalanan</div>
                    <div class="value"><?php echo e($sppd['tujuan'] ?? ($tujuan[0]['province']['name'] ?? '-')); ?></div>
                </div>
                <div>
                    <div class="label">Periode Perjalanan</div>
                    <div class="value">
                        <?php echo e($start ? $start->translatedFormat('d F Y') : '-'); ?>

                        -
                        <?php echo e($end ? $end->translatedFormat('d F Y') : '-'); ?>

                        <?php if($days): ?>
                            (<?php echo e($days); ?> hari)
                        <?php endif; ?>
                    </div>
                </div>
                <div style="grid-column: 1 / -1;">
                    <div class="label">Keperluan Perjalanan</div>
                    <div class="value"><?php echo e($sppd['keperluan'] ?? '-'); ?></div>
                </div>
            </div>

            <h2 class="section-title">Rincian Pengeluaran</h2>

            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th style="width:72px;">#</th>
                            <th>Pengeluaran</th>
                            <th>Kategori</th>
                            <th class="text-end">Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $expense; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($ex['kategori'] ?? '-'); ?></td>
                                <td><?php echo e($ex['deskripsi'] ?? '-'); ?></td>
                                <td class="text-end">Rp<?php echo e(number_format((float) ($ex['jumlah'] ?? 0), 0, ',', '.')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" style="text-align:center; color:#6b7a90;">Belum ada rincian pengeluaran.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total Keseluruhan</th>
                            <th class="text-end">Rp<?php echo e(number_format((float) $total, 0, ',', '.')); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const downloadButton = document.getElementById('download-summary-pdf');
        const card = document.getElementById('public-sppd-card');
        const filename = <?php echo json_encode($pdfFilename, 15, 512) ?>;

        if (!downloadButton || !card || typeof html2pdf === 'undefined') {
            return;
        }

        downloadButton.addEventListener('click', function () {
            const originalText = downloadButton.textContent;
            const actions = document.querySelectorAll('.no-print');
            downloadButton.textContent = 'Menyiapkan PDF...';
            downloadButton.disabled = true;
            actions.forEach(function (element) {
                element.style.display = 'none';
            });

            const options = {
                margin: [10, 10, 10, 10],
                filename: filename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#f4f7fb'
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
            };

            html2pdf()
                .set(options)
                .from(card)
                .save()
                .finally(function () {
                    actions.forEach(function (element) {
                        element.style.display = '';
                    });
                    downloadButton.textContent = originalText;
                    downloadButton.disabled = false;
                });
        });
    });
</script>
</body>
</html>
<?php /**PATH /Users/muhamadsobirin/Documents/front-ias/resources/views/pages/sppd/public-summary.blade.php ENDPATH**/ ?>