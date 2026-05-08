@extends('layouts.admin')
@section('title', 'Buat Kompensasi')
@section('page-title', 'Buat Kompensasi')
@section('page-sub', 'Input data kompensasi alpha mahasiswa')
 
@push('styles')
<style>
.form-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:24px;}
.form-group{margin-bottom:18px;}
.form-label{display:block;font-size:12px;font-weight:700;color:var(--text-1);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;}
.form-control-ac{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 14px;font-size:14px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--gray-1,#F9FAFB);outline:none;transition:all .2s;}
.form-control-ac:focus{border-color:var(--blue);background:#fff;box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.kompen-preview{background:linear-gradient(135deg,#1E3A8A,#2563EB);border-radius:12px;padding:20px 24px;color:#fff;margin-top:8px;}
.kompen-preview-num{font-size:48px;font-weight:800;letter-spacing:-2px;line-height:1;}
.kompen-preview-lbl{font-size:12px;opacity:.7;margin-top:4px;}
.sp-info{border-radius:10px;padding:12px 16px;font-size:13px;font-weight:600;margin-top:12px;}
</style>
@endpush
 
@section('content')
 
<div class="mb-3">
    <a href="{{ route('admin.kompensasi.index') }}"
       style="display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:500;color:var(--text-2);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kompensasi
    </a>
</div>
 
@include('components.page-banner', [
    'gradient' => 'linear-gradient(135deg, #1A0A0A 0%, #7F1D1D 50%, #DC2626 100%)',
    'icon'     => 'bi-clipboard2-plus-fill',
    'title'    => 'Buat Kompensasi Baru',
    'sub'      => 'Input data kompensasi alpha untuk mahasiswa',
])
 
<div class="row g-4">
 
    {{-- Form --}}
    <div class="col-lg-7">
        <div class="form-card">
            <form method="POST" action="{{ route('admin.kompensasi.store') }}" id="formKompen">
                @csrf
 
                {{-- Pilih Mahasiswa --}}
                <div class="form-group">
                    <label class="form-label">Mahasiswa <span style="color:#EF4444;">*</span></label>
                    <select name="mahasiswa_id" class="form-control-ac" id="selectMhs" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswas as $mhs)
                        <option value="{{ $mhs->id }}"
                            data-semester="{{ $mhs->kelas->semester ?? 6 }}"
                            data-tahun="{{ $mhs->kelas->tahun_akademik ?? '2024/2025' }}"
                            {{ old('mahasiswa_id', $mahasiswa?->id) == $mhs->id ? 'selected' : '' }}>
                            {{ $mhs->nama }} — {{ $mhs->nim }} ({{ $mhs->kelas->nama ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                    @error('mahasiswa_id')<div style="font-size:12px;color:#EF4444;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
 
                <div class="row g-3">
                    {{-- Semester --}}
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Semester Alpha <span style="color:#EF4444;">*</span></label>
                            <select name="semester" class="form-control-ac" id="inputSemester" required>
                                @for($s = 1; $s <= 8; $s++)
                                <option value="{{ $s }}" {{ old('semester', $semesterAktif) == $s ? 'selected':'' }}>
                                    Semester {{ $s }}
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>
 
                    {{-- Tahun Akademik --}}
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Tahun Akademik <span style="color:#EF4444;">*</span></label>
                            <input type="text" name="tahun_akademik" class="form-control-ac"
                                   value="{{ old('tahun_akademik', $tahunAkademik) }}"
                                   placeholder="2024/2025" required>
                        </div>
                    </div>
                </div>
 
                <div class="row g-3">
                    {{-- Jam Alpha --}}
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Total Jam Alpha <span style="color:#EF4444;">*</span></label>
                            <input type="number" name="jam_alpha" id="inputAlpha" class="form-control-ac"
                                   value="{{ old('jam_alpha', $jamAlpha) }}"
                                   min="1" max="200" required
                                   placeholder="contoh: 20">
                            @error('jam_alpha')<div style="font-size:12px;color:#EF4444;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                    </div>
 
                    {{-- Multiplier --}}
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Multiplier <span style="color:#EF4444;">*</span></label>
                            <select name="multiplier" class="form-control-ac" id="inputMultiplier" required>
                                <option value="1" {{ old('multiplier',1)==1 ? 'selected':'' }}>×1 — Semester ini (×2)</option>
                                <option value="2" {{ old('multiplier',1)==2 ? 'selected':'' }}>×2 — 1 semester terlambat (×4)</option>
                                <option value="4" {{ old('multiplier',1)==4 ? 'selected':'' }}>×4 — 2 semester terlambat (×8)</option>
                                <option value="8" {{ old('multiplier',1)==8 ? 'selected':'' }}>×8 — 3 semester terlambat (×16)</option>
                            </select>
                            <div style="font-size:11px;color:var(--text-3);margin-top:4px;">
                                Jam kompen = jam alpha × 2 × multiplier
                            </div>
                        </div>
                    </div>
                </div>
 
                {{-- Catatan Tugas --}}
                <div class="form-group">
                    <label class="form-label">Catatan Tugas Kompen</label>
                    <textarea name="catatan_tugas" class="form-control-ac" rows="3"
                              placeholder="Jelaskan tugas kompen yang harus diselesaikan mahasiswa...">{{ old('catatan_tugas') }}</textarea>
                </div>
 
                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="btn-primary" style="flex:1;padding:12px;justify-content:center;display:flex;align-items:center;gap:6px;font-size:14px;">
                        <i class="bi bi-clipboard2-check-fill"></i> Buat Kompensasi
                    </button>
                    <a href="{{ route('admin.kompensasi.index') }}"
                       style="padding:12px 20px;border:1.5px solid var(--border);border-radius:var(--radius);font-size:14px;font-weight:500;color:var(--text-2);text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
 
    {{-- Preview --}}
    <div class="col-lg-5">
        <div class="section-label">Preview Kalkulasi</div>
        <div class="form-card">
            <div class="kompen-preview">
                <div class="kompen-preview-lbl">Jam Kompen Wajib</div>
                <div class="kompen-preview-num" id="previewJam">0</div>
                <div class="kompen-preview-lbl">jam</div>
                <div style="margin-top:12px;font-size:12px;opacity:.8;" id="previewFormula">
                    0 jam alpha × 2 × ×1 = 0 jam
                </div>
            </div>
 
            <div class="sp-info" id="previewSp" style="background:#F1F5F9;color:var(--text-2);">
                Masukkan jam alpha untuk melihat status SP
            </div>
 
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                <div style="font-size:12px;font-weight:700;color:var(--text-2);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">Referensi SP</div>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--text-2);">Alpha ≥ 18 jam/semester</span>
                        <span style="background:#FEF3C7;color:#92400E;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">SP 1</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--text-2);">Alpha ≥ 36 jam/semester</span>
                        <span style="background:#FEE2E2;color:#991B1B;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">SP 2</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--text-2);">Alpha ≥ 72 jam/semester</span>
                        <span style="background:#7F1D1D;color:#fff;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">SP 3</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
</div>
 
@endsection
 
@push('scripts')
<script>
function updatePreview() {
    var alpha      = parseInt(document.getElementById('inputAlpha').value) || 0;
    var multiplier = parseInt(document.getElementById('inputMultiplier').value) || 1;
    var jam        = alpha * 2 * multiplier;
 
    document.getElementById('previewJam').textContent     = jam;
    document.getElementById('previewFormula').textContent =
        alpha + ' jam alpha × 2 × ' + multiplier + ' = ' + jam + ' jam';
 
    // SP
    var spEl = document.getElementById('previewSp');
    if (alpha >= 72) {
        spEl.style.background = '#7F1D1D'; spEl.style.color = '#fff';
        spEl.textContent = '⚠ SP 3 — Alpha sangat kritis!';
    } else if (alpha >= 36) {
        spEl.style.background = '#FEE2E2'; spEl.style.color = '#991B1B';
        spEl.textContent = '⚠ SP 2 — Alpha kritis';
    } else if (alpha >= 18) {
        spEl.style.background = '#FEF3C7'; spEl.style.color = '#92400E';
        spEl.textContent = '⚠ SP 1 — Alpha melebihi batas';
    } else {
        spEl.style.background = '#F1F5F9'; spEl.style.color = '#6B7280';
        spEl.textContent = 'Alpha belum mencapai batas SP';
    }
}
 
document.getElementById('inputAlpha').addEventListener('input', updatePreview);
document.getElementById('inputMultiplier').addEventListener('change', updatePreview);
 
// Auto-fill semester saat pilih mahasiswa
document.getElementById('selectMhs').addEventListener('change', function() {
    var opt = this.options[this.selectedIndex];
    if (opt.dataset.semester) {
        document.getElementById('inputSemester').value = opt.dataset.semester;
    }
});
 
updatePreview();
</script>
@endpush