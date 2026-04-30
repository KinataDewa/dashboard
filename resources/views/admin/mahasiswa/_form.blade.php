@php $isEdit = isset($mahasiswa); @endphp
@php $s = "width:100%;border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:9px 13px;font-size:13.5px;outline:none;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--white);transition:border-color .15s;";
     $l = "display:block;font-size:12px;font-weight:700;color:var(--text-2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;"; @endphp
 
<div class="row g-3">
    <div class="col-md-6">
        <label style="{{ $l }}">NIM *</label>
        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}" required style="{{ $s }}"
               onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">
    </div>
    <div class="col-md-6">
        <label style="{{ $l }}">Nama Lengkap *</label>
        <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama ?? '') }}" required style="{{ $s }}"
               onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">
    </div>
    @if(!$isEdit)
    <div class="col-md-6">
        <label style="{{ $l }}">Email *</label>
        <input type="email" name="email" value="{{ old('email') }}" required style="{{ $s }}"
               onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">
    </div>
    <div class="col-md-6">
        <label style="{{ $l }}">Password *</label>
        <input type="password" name="password" required style="{{ $s }}"
               onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">
    </div>
    @endif
    <div class="col-md-6">
        <label style="{{ $l }}">Kelas *</label>
        <select name="kelas_id" required style="{{ $s }}"
                onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">
            <option value="">-- Pilih Kelas --</option>
            @foreach($kelasList as $kelas)
            <option value="{{ $kelas->id }}" {{ old('kelas_id', $mahasiswa->kelas_id ?? '') == $kelas->id ? 'selected' : '' }}>
                {{ $kelas->nama }} (Sem {{ $kelas->semester }})
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label style="{{ $l }}">Dosen PA *</label>
        <select name="dosen_pa_id" required style="{{ $s }}"
                onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">
            <option value="">-- Pilih Dosen PA --</option>
            @foreach($dosenList as $dosen)
            <option value="{{ $dosen->id }}" {{ old('dosen_pa_id', $mahasiswa->dosen_pa_id ?? '') == $dosen->id ? 'selected' : '' }}>
                {{ $dosen->nama }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label style="{{ $l }}">Angkatan *</label>
        <input type="number" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan ?? date('Y')) }}"
               min="2000" max="{{ date('Y') }}" required style="{{ $s }}"
               onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">
    </div>
    <div class="col-md-6">
        <label style="{{ $l }}">Status</label>
        <select name="status" style="{{ $s }}"
                onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">
            @foreach(['aktif','cuti','lulus','keluar'] as $st)
            <option value="{{ $st }}" {{ old('status', $mahasiswa->status ?? 'aktif') == $st ? 'selected' : '' }}>
                {{ ucfirst($st) }}
            </option>
            @endforeach
        </select>
    </div>
</div>