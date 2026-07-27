<!-- ... existing code ... -->

<!-- Sisa item pembayaran -->
<div style="margin-left: 160px;">
    @if(isset($pembayaran_list) && is_array($pembayaran_list))
        @for($i = 1; $i < count($pembayaran_list); $i++)
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                @if(isset($pembayaran_list[$i]))
                    <span style="border-bottom: 2px solid black; flex: 1; min-height: 25px; padding: 2px 8px; font-size: 16px; margin-right: 20px;">{{ $i + 1 }}. {{ $pembayaran_list[$i]['nama_tagihan'] ?? 'Pembayaran' }}</span>
                    <span style="font-size: 16px; font-weight: bold;">Rp {{ number_format($pembayaran_list[$i]['jumlah_bayar'] ?? 0, 0, ',', '.') }}</span>
                @else
                    <span style="border-bottom: 2px solid black; flex: 1; min-height: 25px; padding: 2px 8px; font-size: 16px; margin-right: 20px;">{{ $i + 1 }}.</span>
                    <span style="font-size: 16px; font-weight: bold;">Rp</span>
                @endif
            </div>
        @endfor
    @endif

    <!-- Tampilkan item kosong jika kurang dari 5 -->
    @php
        $itemCount = isset($pembayaran_list) && is_array($pembayaran_list) ? count($pembayaran_list) : 0;
    @endphp

    @for($i = $itemCount; $i < 5; $i++)
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="border-bottom: 2px solid black; flex: 1; min-height: 25px; padding: 2px 8px; font-size: 16px; margin-right: 20px;">{{ $i + 1 }}.</span>
            <span style="font-size: 16px; font-weight: bold;">Rp</span>
        </div>
    @endfor
</div>

<!-- ... existing code ... -->