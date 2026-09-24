<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->invoice_number }} - Absolute Store</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-mono:400,700|inter:400,600,700" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Space Mono', monospace, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 24px;
            font-size: 12px;
            line-height: 1.4;
        }
        .action-bar {
            max-width: 380px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-family: 'Inter', sans-serif;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        .btn-primary {
            background-color: #4f46e5;
            color: white;
        }
        .btn-primary:hover {
            background-color: #4338ca;
        }
        .btn-secondary {
            background-color: white;
            color: #334155;
            border-color: #cbd5e1;
        }
        .btn-secondary:hover {
            background-color: #e2e8f0;
        }
        .receipt-container {
            max-width: 380px;
            margin: 0 auto;
            background: white;
            padding: 24px 20px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
            border-radius: 6px;
            position: relative;
        }
        .receipt-header {
            text-align: center;
            padding-bottom: 12px;
            border-bottom: 1px dashed #94a3b8;
        }
        .store-name {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .store-sub {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .dashed-line {
            border-top: 1px dashed #94a3b8;
            margin: 12px 0;
        }
        .double-line {
            border-top: 2px dashed #0f172a;
            margin: 12px 0;
        }
        .row-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }
        .row-label {
            color: #64748b;
            flex-shrink: 0;
        }
        .row-val {
            text-align: right;
            font-weight: 600;
            word-break: break-word;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid #10b981;
            color: #047857;
            background-color: #ecfdf5;
        }
        .status-unpaid {
            border-color: #f59e0b;
            color: #b45309;
            background-color: #fffbeb;
        }
        .sn-box {
            margin: 12px 0;
            padding: 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-align: center;
        }
        .sn-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 2px;
        }
        .sn-value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 1px;
            word-break: break-all;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            font-weight: 700;
            padding-top: 4px;
        }
        .barcode-section {
            text-align: center;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px dashed #94a3b8;
        }
        .barcode-bars {
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 2px;
            margin-bottom: 4px;
        }
        .bar-1 { width: 1px; background: #0f172a; }
        .bar-2 { width: 2px; background: #0f172a; }
        .bar-3 { width: 3px; background: #0f172a; }
        .bar-4 { width: 4px; background: #0f172a; }
        .bar-space { width: 2px; background: transparent; }
        .barcode-text {
            font-size: 10px;
            letter-spacing: 2px;
            color: #475569;
        }
        .receipt-footer {
            text-align: center;
            font-size: 9px;
            color: #64748b;
            margin-top: 14px;
            line-height: 1.5;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .receipt-container {
                max-width: 100% !important;
                width: 78mm;
                padding: 8px 4px;
                box-shadow: none;
                border-radius: 0;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="{{ route('order.invoice', $transaction->invoice_number) }}" class="btn btn-secondary">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Cetak Struk (Thermal / PDF)
        </button>
    </div>

    <div class="receipt-container" id="receipt">
        <!-- Header -->
        <div class="receipt-header">
            <div class="store-name">{{ config('app.name', 'Absolute Store') }}</div>
            <div class="store-sub">Official Digital Goods & PPOB Store</div>
            <div class="store-sub">{{ url('/') }}</div>
        </div>

        <!-- Info Transaksi -->
        <div style="margin-top: 12px;">
            <div class="row-item">
                <span class="row-label">No. Invoice:</span>
                <span class="row-val font-mono">{{ $transaction->invoice_number }}</span>
            </div>
            <div class="row-item">
                <span class="row-label">Waktu:</span>
                <span class="row-val">{{ $transaction->created_at->translatedFormat('d/m/Y H:i') }} WIB</span>
            </div>
            <div class="row-item">
                <span class="row-label">Metode:</span>
                <span class="row-val">{{ $transaction->paymentMethod->name ?? 'Gateway' }}</span>
            </div>
            <div class="row-item" style="margin-top: 6px;">
                <span class="row-label">Status Bayar:</span>
                <span class="row-val">
                    @if($transaction->payment_status === 'paid')
                        <span class="status-badge">LUNAS</span>
                    @elseif($transaction->payment_status === 'unpaid')
                        <span class="status-badge status-unpaid">BELUM LUNAS</span>
                    @else
                        <span class="status-badge">{{ strtoupper($transaction->payment_status) }}</span>
                    @endif
                </span>
            </div>
            <div class="row-item" style="margin-top: 4px;">
                <span class="row-label">Pengiriman:</span>
                <span class="row-val" style="color: {{ $transaction->delivery_status === 'success' ? '#047857' : '#0284c7' }};">
                    {{ strtoupper($transaction->delivery_status) }}
                </span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Detail Akun / Pelanggan -->
        <div>
            <div class="row-item">
                <span class="row-label">Tujuan Akun:</span>
                <span class="row-val">{{ $transaction->target_account }}</span>
            </div>
            @if($transaction->target_zone)
                <div class="row-item">
                    <span class="row-label">Server / Zone:</span>
                    <span class="row-val">({{ $transaction->target_zone }})</span>
                </div>
            @endif
            @if($transaction->whatsapp_number)
                <div class="row-item">
                    <span class="row-label">WhatsApp:</span>
                    <span class="row-val">{{ substr($transaction->whatsapp_number, 0, 4) . '****' . substr($transaction->whatsapp_number, -4) }}</span>
                </div>
            @endif
        </div>

        <div class="dashed-line"></div>

        <!-- Detail Produk -->
        <div>
            <div style="font-weight: 700; font-size: 13px; margin-bottom: 2px;">
                {{ $transaction->productItem->product->name ?? 'Produk Digital' }}
            </div>
            <div style="color: #475569; font-size: 11px; margin-bottom: 8px;">
                {{ $transaction->productItem->name ?? 'Item' }} x 1
            </div>

            <div class="row-item">
                <span class="row-label">Harga Produk</span>
                <span class="row-val">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
            </div>
            <div class="row-item">
                <span class="row-label">Biaya Transaksi</span>
                <span class="row-val">Rp {{ number_format($transaction->fee_amount, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount_amount > 0)
                <div class="row-item" style="color: #e11d48;">
                    <span class="row-label" style="color: #e11d48;">Diskon Promo</span>
                    <span class="row-val">-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

        <div class="double-line"></div>

        <!-- Total -->
        <div class="total-row">
            <span>TOTAL BAYAR</span>
            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>

        <!-- Serial Number Box (jika ada) -->
        @if($transaction->serial_number)
            <div class="sn-box">
                <span class="sn-label">Kode SN / Voucher Token</span>
                <div class="sn-value">{{ $transaction->serial_number }}</div>
            </div>
        @endif

        <!-- Barcode Section -->
        <div class="barcode-section">
            <div class="barcode-bars">
                <div class="bar-2"></div><div class="bar-space"></div>
                <div class="bar-1"></div><div class="bar-space"></div>
                <div class="bar-3"></div><div class="bar-space"></div>
                <div class="bar-1"></div><div class="bar-space"></div>
                <div class="bar-4"></div><div class="bar-space"></div>
                <div class="bar-2"></div><div class="bar-space"></div>
                <div class="bar-1"></div><div class="bar-space"></div>
                <div class="bar-3"></div><div class="bar-space"></div>
                <div class="bar-2"></div><div class="bar-space"></div>
                <div class="bar-4"></div><div class="bar-space"></div>
                <div class="bar-1"></div><div class="bar-space"></div>
                <div class="bar-2"></div><div class="bar-space"></div>
                <div class="bar-3"></div><div class="bar-space"></div>
                <div class="bar-1"></div><div class="bar-space"></div>
                <div class="bar-2"></div>
            </div>
            <div class="barcode-text">*{{ $transaction->invoice_number }}*</div>
        </div>

        <!-- Footer Notice -->
        <div class="receipt-footer">
            <div>Terima kasih atas transaksi Anda di {{ config('app.name', 'Absolute Store') }}!</div>
            <div>Struk ini adalah bukti pembayaran digital yang sah dan terverifikasi secara sistem.</div>
            <div style="margin-top: 4px; font-weight: 600;">Layanan Bantuan CS 24/7 WhatsApp</div>
        </div>
    </div>

</body>
</html>
