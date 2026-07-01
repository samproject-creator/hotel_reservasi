<form action="{{ route('checkout.proses', $booking->id) }}" method="POST" class="space-y-4">
    @csrf
    
    <div class="p-3 bg-purple-950/20 border border-purple-900 rounded text-xs">
        <span class="text-slate-400">Sisa Tagihan:</span>
        <span class="text-yellow-500 font-mono font-bold block text-sm">
            Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
        </span>
    </div>

    <div class="text-xs">
        <label class="block text-slate-400 font-medium mb-1">Jumlah Uang yang Diterima (Rp)</label>
        <input type="number" name="total_bayar" min="{{ $sisaTagihan }}" value="{{ $sisaTagihan }}" required
               class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600 font-mono">
        <small class="text-slate-500 mt-1 block">* Minimal sebesar sisa tagihan</small>
    </div>

    <div class="text-xs">
        <label class="block text-slate-400 font-medium mb-1">Metode Pembayaran</label>
        <select name="metode_pembayaran" class="w-full px-3 py-2 bg-slate-950 border border-purple-950 rounded text-slate-200 focus:outline-none focus:border-purple-600">
            <option value="cash">Tunai / Cash</option>
            <option value="transfer">Transfer Bank</option>
            <option value="kartu_kredit">Kartu Kredit</option>
            <option value="debit">Debit</option>
        </select>
    </div>

    <button type="submit" class="w-full py-2.5 bg-purple-950 hover:bg-purple-900 text-purple-200 font-semibold border border-purple-800 rounded text-xs transition-colors">
        Confirm & Selesaikan Check-out
    </button>
</form>