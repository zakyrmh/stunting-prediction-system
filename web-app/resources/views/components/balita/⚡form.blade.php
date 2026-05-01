<?php

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Balita;
use App\Models\Posyandu;

new #[Title('Form Data Balita')]
class extends Component {

    public ?int $balitaId = null;

    public string $name        = '';
    public string $nik         = '';
    public string $birth_date  = '';
    public string $gender      = '';
    public string $parent_name = '';
    public string $phone       = '';
    public string $address     = '';
    public ?int   $posyandu_id = null;

    public function mount(int $balita = null): void
    {
        // Jika petugas, otomatis pakai posyandu miliknya
        if (auth()->user()->isPetugas()) {
            $this->posyandu_id = auth()->user()->posyandu_id;
        }

        if ($balita) {
            $this->balitaId = $balita;
            $data = Balita::findOrFail($balita);

            $this->name        = $data->name;
            $this->nik         = $data->nik;
            $this->birth_date  = $data->birth_date;
            $this->gender      = $data->gender;
            $this->parent_name = $data->parent_name;
            $this->phone       = $data->phone;
            $this->address     = $data->address;
            $this->posyandu_id = $data->posyandu_id;
        }
    }

    public function save(): void
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'nik'         => 'required|string|max:20|unique:balitas,nik,' . ($this->balitaId ?? 'NULL'),
            'birth_date'  => 'required|date',
            'gender'      => 'required|in:male,female',
            'parent_name' => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string',
            'posyandu_id' => 'required|exists:posyandus,id',
        ]);

        Balita::updateOrCreate(
            ['id' => $this->balitaId],
            [
                'user_id'      => auth()->id(),
                'name'         => $this->name,
                'nik'          => $this->nik,
                'birth_date'   => $this->birth_date,
                'gender'       => $this->gender,
                'parent_name'  => $this->parent_name,
                'phone'        => $this->phone,
                'address'      => $this->address,
                'posyandu_id'  => $this->posyandu_id,
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
        ];
    }
};
?>

<div>
    <div class="flex flex-col gap-6 p-6 max-w-2xl">

        {{-- Header --}}
        <div>
            <flux:heading size="xl">
                {{ $balitaId ? 'Edit Data Balita' : 'Tambah Data Balita' }}
            </flux:heading>
            <flux:text class="mt-1">
                {{ $balitaId ? 'Perbarui informasi data balita.' : 'Isi formulir untuk menambahkan data balita baru.' }}
            </flux:text>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="flex flex-col gap-5">

            <flux:input
                wire:model="name"
                label="Nama Lengkap Balita"
                placeholder="Masukkan nama lengkap"
                required />

            <flux:input
                wire:model="nik"
                label="NIK / No. KMS"
                placeholder="Masukkan NIK atau nomor KMS"
                required />

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    wire:model="birth_date"
                    label="Tanggal Lahir"
                    type="date"
                    required />

                <flux:select
                    wire:model="gender"
                    label="Jenis Kelamin"
                    required>
                    <flux:select.option value="">-- Pilih --</flux:select.option>
                    <flux:select.option value="male">Laki-laki</flux:select.option>
                    <flux:select.option value="female">Perempuan</flux:select.option>
                </flux:select>
            </div>

            <flux:input
                wire:model="parent_name"
                label="Nama Orang Tua / Wali"
                placeholder="Masukkan nama orang tua"
                required />

            <flux:input
                wire:model="phone"
                label="Nomor Telepon Orang Tua"
                placeholder="Contoh: 08123456789"
                required />

            <flux:textarea
                wire:model="address"
                label="Alamat"
                placeholder="Masukkan alamat lengkap"
                required />

            @if(auth()->user()->isAdmin())
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
                <flux:button type="submit" variant="primary">
                    {{ $balitaId ? 'Simpan Perubahan' : 'Tambah Balita' }}
                </flux:button>
                <flux:button
                    variant="ghost"
                    :href="route('balita.index')"
                    wire:navigate>
                    Batal
                </flux:button>
            </div>

        </form>
    </div>
</div>
