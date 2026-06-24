<?php

use Livewire\Component;
use App\Models\Children;

new class extends Component {
    public Children $balita;

    public function mount(Children $balita): void
    {
        $this->balita = $balita->load([
            'posyandu',
            'predictions' => function ($query) {
                $query->latest('examined_at')->latest('id');
            },
            'predictions.recorder',
            'predictions.intervention'
        ]);
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

            @if($balita->predictions->isEmpty())
                <flux:text class="py-4 block text-center text-ink-subtle">
                    Belum ada riwayat pengukuran dan prediksi untuk balita ini.
                </flux:text>
            @else
                <div class="overflow-x-auto mt-4">
                    <table class="w-full text-left text-body-sm min-w-[600px]">
                        <thead>
                            <tr class="bg-surface-2 text-ink font-semibold border-b border-hairline">
                                <th class="p-3">Tanggal Ukur</th>
                                <th class="p-3">Pengukuran Fisik</th>
                                <th class="p-3">Diagnosis AI (ML)</th>
                                <th class="p-3">Petugas Catat</th>
                                <th class="p-3">Validasi Medis</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($balita->predictions as $prediction)
                                <tr class="border-b border-hairline-soft bg-surface-1 hover:bg-canvas/50 transition-colors">
                                    <td class="p-3 font-mono text-xs text-ink-muted">
                                        {{ $prediction->examined_at->format('d M Y') }}
                                    </td>
                                    <td class="p-3 font-mono text-xs text-ink-muted">
                                        <div class="flex flex-col">
                                            <span>TB: <strong class="text-ink">{{ $prediction->height }} cm</strong></span>
                                            <span>BB: <strong class="text-ink">{{ $prediction->weight }} kg</strong></span>
                                            <span>Usia: <strong>{{ $prediction->age_months }} Bulan</strong></span>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex flex-col gap-1">
                                            @if($prediction->result === 'severely_stunted')
                                                <span class="inline-block w-fit px-2 py-0.5 bg-risk-high-surface text-risk-high rounded text-[10px] font-bold">Sangat Pendek</span>
                                            @elseif($prediction->result === 'stunted')
                                                <span class="inline-block w-fit px-2 py-0.5 bg-risk-high-surface text-risk-high rounded text-[10px] font-bold">Pendek (Stunted)</span>
                                            @elseif($prediction->result === 'stunting_risk')
                                                <span class="inline-block w-fit px-2 py-0.5 bg-risk-medium-surface text-risk-medium rounded text-[10px] font-bold">Risiko Stunting</span>
                                            @else
                                                <span class="inline-block w-fit px-2 py-0.5 bg-risk-low-surface text-risk-low rounded text-[10px] font-bold">Normal</span>
                                            @endif
                                            <span class="text-[10px] text-ink-subtle font-mono">Conf: {{ number_format($prediction->confidence * 100, 2) }}%</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-ink-muted text-xs">
                                        {{ $prediction->recorder->name ?? '-' }}
                                    </td>
                                    <td class="p-3">
                                        @php
                                            $status = $prediction->intervention ? ($prediction->intervention->status === 'pending' ? 'pending' : 'verified') : 'verified';
                                        @endphp
                                        @if($status === 'pending')
                                            <span class="inline-block px-2 py-0.5 bg-risk-medium-surface/70 text-risk-medium border border-risk-medium-border rounded text-[10px] font-bold">⏳ Pending</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 bg-risk-low-surface/70 text-risk-low border border-risk-low-border rounded text-[10px] font-bold">✓ Verified</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-right">
                                        <flux:button size="sm" icon="eye" :href="route('prediksi.show', $prediction->id)" class="cursor-pointer" wire:navigate />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </flux:card>

    </div>
</div>
