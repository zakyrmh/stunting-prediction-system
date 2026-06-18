<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Children;
use App\Models\Posyandu;
use App\Models\User;

new #[Title('Form Data Balita')]
class extends Component {

    public ?int $balitaId = null;

    public string $name        = '';
    public string $nik         = '';
    public string $birth_date  = '';
    public string $birth_place = '';
    public string $gender      = '';
    public string $address     = '';
    public ?int   $user_id     = null;
    public ?int   $posyandu_id = null;

    public function mount(int $balita = null): void
    {
        if (auth()->user()->isOrangTua()) {
            abort(403, 'Akses ditolak. Orang Tua tidak diizinkan mengubah data.');
        }

        // Jika kader, otomatis pakai posyandu miliknya
        if (auth()->user()->isKader()) {
            $this->posyandu_id = auth()->user()->posyandu_id;
        }

        if ($balita) {
            $this->balitaId = $balita;
            $data = Children::findOrFail($balita);

            $this->name        = $data->name;
            $this->nik         = $data->nik ?? '';
            $this->birth_date  = $data->birth_date ? $data->birth_date->format('Y-m-d') : '';
            $this->birth_place = $data->birth_place;
            $this->gender      = $data->gender;
            $this->address     = $data->address;
            $this->user_id     = $data->user_id;
            $this->posyandu_id = $data->posyandu_id;
        }
    }

    public function save(): void
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'nik'         => 'nullable|string|max:20|unique:children,nik,' . ($this->balitaId ?? 'NULL'),
            'birth_date'  => 'required|date',
            'birth_place' => 'required|string|max:255',
            'gender'      => 'required|in:male,female',
            'address'     => 'required|string',
            'user_id'     => 'nullable|exists:users,id',
            'posyandu_id' => 'required|exists:posyandus,id',
        ]);

        Children::updateOrCreate(
            ['id' => $this->balitaId],
            [
                'user_id'      => $this->user_id,
                'posyandu_id'  => $this->posyandu_id,
                'name'         => $this->name,
                'nik'          => $this->nik ?: null,
                'birth_date'   => $this->birth_date,
                'birth_place'  => $this->birth_place,
                'gender'       => $this->gender,
                'address'      => $this->address,
            ]
        );

        session()->flash('success', $this->balitaId
            ? 'Data balita berhasil diperbarui.'
            : 'Data balita berhasil ditambahkan.'
        );

        $this->redirect(route('balita.index'), navigate: true);
    }

    public function with(): array
    {
        return [
            'posyandus' => Posyandu::orderBy('name')->get(),
            'parents'   => User::where('role', 'orang_tua')->orderBy('name')->get(),
        ];
    }
};
?>

<div>
    <div class="flex flex-col gap-6 p-6 max-w-2xl bg-canvas text-ink font-sans">

        {{-- Header --}}
        <div>
            <flux:heading size="xl" class="font-bold text-ink">
                {{ $balitaId ? 'Edit Data Balita' : 'Tambah Data Balita' }}
            </flux:heading>
            <flux:text class="mt-1 text-ink-muted">
                {{ $balitaId ? 'Perbarui informasi data balita.' : 'Isi formulir untuk menambahkan data balita baru.' }}
            </flux:text>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="flex flex-col gap-5 bg-surface-1 border border-hairline p-6 rounded-xl shadow-sm">

            <flux:input
                wire:model="name"
                label="Nama Lengkap Balita"
                placeholder="Masukkan nama lengkap"
                required />

            <flux:input
                wire:model="nik"
                label="NIK / No. KMS (Opsional)"
                placeholder="Masukkan NIK atau nomor KMS" />

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    wire:model="birth_date"
                    label="Tanggal Lahir"
                    type="date"
                    required />

                <flux:input
                    wire:model="birth_place"
                    label="Tempat Lahir"
                    placeholder="Masukkan kota lahir"
                    required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:select
                    wire:model="gender"
                    label="Jenis Kelamin"
                    required>
                    <flux:select.option value="">-- Pilih --</flux:select.option>
                    <flux:select.option value="male">Laki-laki</flux:select.option>
                    <flux:select.option value="female">Perempuan</flux:select.option>
                </flux:select>

                <flux:select
                    wire:model="user_id"
                    label="Orang Tua / Wali (Opsional)">
                    <flux:select.option value="">-- Pilih Orang Tua --</flux:select.option>
                    @foreach($parents as $parent)
                        <flux:select.option value="{{ $parent->id }}">
                            {{ $parent->name }} ({{ $parent->email }})
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:textarea
                wire:model="address"
                label="Alamat Lengkap"
                placeholder="Masukkan alamat lengkap rumah"
                required />

            @if(auth()->user()->isBidan())
                <flux:select
                    wire:model="posyandu_id"
                    label="Posyandu"
                    required>
                    <flux:select.option value="">-- Pilih Posyandu --</flux:select.option>
                    @foreach($posyandus as $posyandu)
                        <flux:select.option value="{{ $posyandu->id }}">
                            {{ $posyandu->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            @endif

            <div class="flex items-center gap-3 mt-2">
                <flux:button type="submit" variant="primary" class="cursor-pointer">
                    {{ $balitaId ? 'Simpan Perubahan' : 'Tambah Balita' }}
                </flux:button>
                <flux:button
                    variant="ghost"
                    :href="route('balita.index')"
                    wire:navigate
                    class="cursor-pointer">
                    Batal
                </flux:button>
            </div>

        </form>
    </div>
</div>
