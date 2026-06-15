<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Balita;

new #[Title('Data Balita')]
class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        if (!auth()->user()->isBidan()) {
            abort(403, 'Hanya Bidan yang dapat menghapus data.');
        }
        $balita = Balita::findOrFail($id);
        $balita->delete();
        session()->flash('success', 'Data balita berhasil dihapus.');
    }

    public function with(): array
    {
        $query = Balita::query()->with('posyandu');

        if (!auth()->user()->isBidan()) {
            $query->where('user_id', auth()->id());
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('nik', 'like', "%{$this->search}%")
                  ->orWhere('parent_name', 'like', "%{$this->search}%");
            });
        }

        return [
            'balitas' => $query->latest()->paginate(10),
        ];
    }
};
?>

<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Data Balita</flux:heading>
            <flux:text class="mt-1">Kelola data balita yang terdaftar.</flux:text>
        </div>
        @if(!auth()->user()->isOrangTua())
            <flux:button
                icon="plus"
                variant="primary"
                :href="route('balita.form')"
                wire:navigate>
                Tambah Balita
            </flux:button>
        @endif
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            {{ session('success') }}
        </flux:callout>
    @endif

    {{-- Search --}}
    <flux:input
        wire:model.live.debounce.300ms="search"
        icon="magnifying-glass"
        placeholder="Cari nama, NIK, atau nama orang tua..."
        clearable
        class="mb-4" />

    {{-- Tabel --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column>No</flux:table.column>
            <flux:table.column>Nama Balita</flux:table.column>
            <flux:table.column>NIK</flux:table.column>
            <flux:table.column>Jenis Kelamin</flux:table.column>
            <flux:table.column>Tanggal Lahir</flux:table.column>
            <flux:table.column>Nama Orang Tua</flux:table.column>
            <flux:table.column>Posyandu</flux:table.column>
            <flux:table.column>Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($balitas as $index => $balita)
                <flux:table.row :key="$balita->id">
                    <flux:table.cell>{{ $balitas->firstItem() + $index }}</flux:table.cell>
                    <flux:table.cell>{{ $balita->name }}</flux:table.cell>
                    <flux:table.cell>{{ $balita->nik }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$balita->gender === 'male' ? 'blue' : 'pink'">
                            {{ $balita->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ \Carbon\Carbon::parse($balita->birth_date)->format('d M Y') }}
                    </flux:table.cell>
                    <flux:table.cell>{{ $balita->parent_name }}</flux:table.cell>
                    <flux:table.cell>{{ $balita->posyandu->name ?? '-' }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            <flux:button
                                size="sm"
                                icon="eye"
                                :href="route('balita.show', $balita->id)"
                                wire:navigate />
                            @if(!auth()->user()->isOrangTua())
                                <flux:button
                                    size="sm"
                                    icon="pencil"
                                    variant="filled"
                                    :href="route('balita.form', $balita->id)"
                                    wire:navigate />
                            @endif
                            @if(auth()->user()->isBidan())
                                <flux:button
                                    size="sm"
                                    icon="trash"
                                    variant="danger"
                                    wire:click="delete({{ $balita->id }})"
                                    wire:confirm="Yakin ingin menghapus data balita ini?" />
                            @endif
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="8" class="text-center py-8">
                        <flux:text>Tidak ada data balita ditemukan.</flux:text>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $balitas->links() }}
    </div>
</div>
