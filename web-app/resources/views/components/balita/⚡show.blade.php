<?php

use Livewire\Component;
use App\Models\Balita;

new class extends Component {
    public Balita $balita;

    public function mount(Balita $balita): void
    {
        $this->balita = $balita->load('posyandu');
    }
};
?>

<div :title="'Detail Balita - ' . $balita->name">
    <div class="flex flex-col gap-6 p-6 max-w-3xl">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">{{ $balita->name }}</flux:heading>
                <flux:text class="mt-1">Detail informasi balita.</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button icon="pencil" variant="filled" :href="route('balita.form', $balita->id)" wire:navigate>
                    Edit
                </flux:button>
                <flux:button icon="arrow-left" :href="route('balita.index')" wire:navigate>
                    Kembali
                </flux:button>
            </div>
        </div>

        {{-- Info Balita --}}
        <flux:card>
            <flux:heading class="mb-4">Informasi Balita</flux:heading>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:text class="text-xs uppercase tracking-wide opacity-60">Nama Lengkap</flux:text>
                    <flux:text class="font-medium">{{ $balita->name }}</flux:text>
                </div>
                <div>
                    <flux:text class="text-xs uppercase tracking-wide opacity-60">NIK / No. KMS</flux:text>
                    <flux:text class="font-medium">{{ $balita->nik }}</flux:text>
                </div>
                <div>
                    <flux:text class="text-xs uppercase tracking-wide opacity-60">Tanggal Lahir</flux:text>
                    <flux:text class="font-medium">
                        {{ \Carbon\Carbon::parse($balita->birth_date)->format('d M Y') }}
                    </flux:text>
                </div>
                <div>
                    <flux:text class="text-xs uppercase tracking-wide opacity-60">Jenis Kelamin</flux:text>
                    <flux:badge :color="$balita->gender === 'male' ? 'blue' : 'pink'">
                        {{ $balita->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                    </flux:badge>
                </div>
                <div>
                    <flux:text class="text-xs uppercase tracking-wide opacity-60">Nama Orang Tua</flux:text>
                    <flux:text class="font-medium">{{ $balita->parent_name }}</flux:text>
                </div>
                <div>
                    <flux:text class="text-xs uppercase tracking-wide opacity-60">No. Telepon</flux:text>
                    <flux:text class="font-medium">{{ $balita->phone }}</flux:text>
                </div>
                <div class="col-span-2">
                    <flux:text class="text-xs uppercase tracking-wide opacity-60">Alamat</flux:text>
                    <flux:text class="font-medium">{{ $balita->address }}</flux:text>
                </div>
                <div>
                    <flux:text class="text-xs uppercase tracking-wide opacity-60">Posyandu</flux:text>
                    <flux:text class="font-medium">{{ $balita->posyandu->name ?? '-' }}</flux:text>
                </div>
            </div>
        </flux:card>

        {{-- Riwayat Prediksi --}}
        <flux:card>
            <div class="flex items-center justify-between mb-4">
                <flux:heading>Riwayat Prediksi</flux:heading>
                <flux:button icon="plus" size="sm" variant="primary"
                    :href="route('prediksi.form', ['balita_id' => $balita->id])" wire:navigate>
                    Prediksi Baru
                </flux:button>
            </div>

            <flux:text class="py-4">
                Riwayat prediksi belum dapat ditampilkan karena tabel <code>predictions</code> saat ini belum
                menyimpan relasi ke <code>balitas</code>. Halaman detail balita tetap bisa diakses, tetapi fitur
                riwayat per balita perlu penyelarasan migration dan model terlebih dahulu.
            </flux:text>
        </flux:card>

    </div>
</div>
