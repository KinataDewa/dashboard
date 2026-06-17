@extends('layouts.admin')
@section('title', 'Buat Kompensasi')
@section('page-title', 'Buat Kompensasi')
@section('page-sub', 'Input data kompensasi alpha mahasiswa')

@push('styles')
<style>
/* ── Form base ─────────────────────────────────────── */
.form-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:24px;}
.form-group{margin-bottom:18px;}
.form-label{display:block;font-size:12px;font-weight:700;color:var(--text-1);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;}
.form-control-ac{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 14px;font-size:14px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--gray-1,#F9FAFB);outline:none;transition:all .2s;}
.form-control-ac:focus{border-color:var(--blue);background:#fff;box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.field-hint{font-size:11px;color:var(--text-3);margin-top:4px;font-style:italic;}

/* ── Mahasiswa search ──────────────────────────────── */
.mhs-search-wrapper{position:relative;}
.mhs-search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-3);pointer-events:none;font-size:15px;}
.mhs-search-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 14px 10px 38px;font-size:14px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--gray-1,#F9FAFB);outline:none;transition:all .2s;box-sizing:border-box;}
.mhs-search-input:focus{border-color:var(--blue);background:#fff;box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.mhs-results{position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1.5px solid var(--border);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.13);z-index:200;max-height:280px;overflow-y:auto;display:none;}
.mhs-result-item{padding:10px 14px;cursor:pointer;border-bottom:1px solid #F1F5F9;transition:background .15s;}
.mhs-result-item:last-child{border-bottom:none;}
.mhs-result-item:hover{background:#EFF6FF;}
.mhs-result-name{font-size:13.5px;font-weight:600;color:var(--text-1);}
.mhs-result-sub{font-size:12px;color:var(--text-3);margin-top:2px;}
.mhs-result-empty,.mhs-result-loading{padding:14px;text-align:center;font-size:13px;color:var(--text-3);}

/* ── Selected card ─────────────────────────────────── */
.mhs-selected-card{background:#F0FDF4;border:1.5px solid #86EFAC;border-radius:10px;padding:12px 14px;display:flex;justify-content:space-between;align-items:center;gap:10px;}
.mhs-selected-name{font-size:14px;font-weight:700;color:#166534;}
.mhs-selected-sub{font-size:12px;color:#4B7A5E;margin-top:2px;}
.btn-ganti{background:#fff;border:1.5px solid #86EFAC;border-radius:6px;padding:6px 14px;font-size:12px;font-weight:600;color:#166534;cursor:pointer;flex-shrink:0;transition:background .15s;}
.btn-ganti:hover{background:#DCFCE7;}

/* ── Riwayat card ──────────────────────────────────── */
.riwayat-card{background:#F8FAFC;border:1.5px solid #CBD5E1;border-radius:10px;padding:16px;}
.riwayat-title{font-size:12px;font-weight:700;color:var(--text-2);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;display:flex;align-items:center;gap:6px;}
.riwayat-table{width:100%;border-collapse:collapse;font-size:12.5px;}
.riwayat-table th{background:#E2E8F0;padding:7px 10px;text-align:left;font-size:11px;font-weight:700;color:var(--text-2);text-transform:uppercase;letter-spacing:.3px;}
.riwayat-table td{padding:7px 10px;border-bottom:1px solid #E2E8F0;color:var(--text-1);}
.riwayat-table tr:last-child td{border-bottom:none;}
.riwayat-table tr.sp1 td{background:#FEF9C3;}
.riwayat-table tr.sp2 td{background:#FEE2E2;}
.riwayat-table tr.sp3 td{background:#FECACA;}
.riwayat-table tr.row-ps td{background:#7F1D1D;color:#fff;}
.riwayat-table tr.aktif-row td{font-weight:700;}
.sp-badge{display:inline-block;padding:1px 7px;border-radius:10px;font-size:10px;font-weight:700;}
.sp1-badge{background:#FEF3C7;color:#92400E;}
.sp2-badge{background:#FEE2E2;color:#991B1B;}
.sp3-badge{background:#FECACA;color:#7F1D1D;}
.ps-badge{background:#7F1D1D;color:#fff;}
.aman-badge{color:#22C55E;font-size:10px;font-weight:700;}

/* ── Preview card ──────────────────────────────────── */
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

    {{-- ── Form ── --}}
    <div class="col-lg-7">
        <div class="form-card">
            <form method="POST" action="{{ route('admin.kompensasi.store') }}" id="formKompen">
                @csrf

                {{-- ── Pilih Mahasiswa (Search Box) ── --}}
                <div class="form-group">
                    <label class="form-label">Mahasiswa <span style="color:#EF4444;">*</span></label>
                    <input type="hidden" name="mahasiswa_id" id="selectedMhsId"
                           value="{{ old('mahasiswa_id', $mahasiswa?->id) }}">

                    {{-- Tampilan setelah mahasiswa dipilih --}}
                    <div id="selectedMhsDisplay" style="display:none;">
                        <div class="mhs-selected-card">
                            <div>
                                <div class="mhs-selected-name" id="selectedMhsName"></div>
                                <div class="mhs-selected-sub" id="selectedMhsSub"></div>
                            </div>
                            <button type="button" class="btn-ganti" onclick="resetMhsSearch()">
                                <i class="bi bi-pencil-fill"></i> Ganti
                            </button>
                        </div>
                    </div>

                    {{-- Search input --}}
                    <div id="searchMhsWrapper">
                        <div class="mhs-search-wrapper">
                            <i class="bi bi-search mhs-search-icon"></i>
                            <input type="text" id="searchMhsInput" class="mhs-search-input"
                                   placeholder="Cari nama atau NIM mahasiswa..."
                                   autocomplete="off">
                            <div id="searchMhsResults" class="mhs-results"></div>
                        </div>
                    </div>

                    @error('mahasiswa_id')
                    <div style="font-size:12px;color:#EF4444;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ── Riwayat Kehadiran (diisi via AJAX) ── --}}
                <div id="riwayatAlphaCard" style="display:none;" class="form-group">
                    <div class="riwayat-card">
                        <div class="riwayat-title">
                            <i class="bi bi-calendar3" style="color:#6366F1;"></i>
                            Riwayat Kehadiran
                        </div>
                        <div id="riwayatTableWrapper">
                            <div class="mhs-result-loading">
                                <i class="bi bi-hourglass-split"></i> Memuat...
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Semester & Tahun Akademik ── --}}
                <div id="sectionSemester" class="row g-3">
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

                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Tahun Akademik <span style="color:#EF4444;">*</span></label>
                            <input type="text" name="tahun_akademik" id="inputTahunAkademik" class="form-control-ac"
                                   value="{{ old('tahun_akademik', $tahunAkademik) }}"
                                   placeholder="2024/2025" required>
                            <div class="field-hint">Otomatis terisi dari semester aktif mahasiswa, dapat diubah jika diperlukan.</div>
                        </div>
                    </div>
                </div>

                {{-- ── Jam Alpha & Multiplier ── --}}
                <div class="row g-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Total Jam Alpha <span style="color:#EF4444;">*</span></label>
                            <input type="number" name="jam_alpha" id="inputAlpha" class="form-control-ac"
                                   value="{{ old('jam_alpha', $jamAlpha) }}"
                                   min="1" max="200" required placeholder="contoh: 20">
                            @error('jam_alpha')
                            <div style="font-size:12px;color:#EF4444;margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Multiplier <span style="color:#EF4444;">*</span></label>
                            <select name="multiplier" class="form-control-ac" id="inputMultiplier" required>
                                <option value="1" {{ old('multiplier',1)==1 ? 'selected':'' }}>×1 — Semester ini (×2)</option>
                                <option value="2" {{ old('multiplier',1)==2 ? 'selected':'' }}>×2 — 1 semester terlambat (×4)</option>
                                <option value="4" {{ old('multiplier',1)==4 ? 'selected':'' }}>×4 — 2 semester terlambat (×8)</option>
                                <option value="8" {{ old('multiplier',1)==8 ? 'selected':'' }}>×8 — 3 semester terlambat (×16)</option>
                            </select>
                            <div style="font-size:11px;color:var(--text-3);margin-top:4px;">Jam kompen = jam alpha × 2 × multiplier</div>
                        </div>
                    </div>
                </div>

                {{-- ── Catatan Tugas ── --}}
                <div class="form-group">
                    <label class="form-label">Catatan Tugas Kompen</label>
                    <textarea name="catatan_tugas" class="form-control-ac" rows="3"
                              placeholder="Jelaskan tugas kompen yang harus diselesaikan mahasiswa...">{{ old('catatan_tugas') }}</textarea>
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="btn-primary"
                            style="flex:1;padding:12px;justify-content:center;display:flex;align-items:center;gap:6px;font-size:14px;">
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

    {{-- ── Preview Kalkulasi ── --}}
    <div class="col-lg-5">
        <div class="section-label">Preview Kalkulasi</div>
        <div class="form-card">
            <div class="kompen-preview">
                <div class="kompen-preview-lbl">Jam Kompen Wajib</div>
                <div class="kompen-preview-num" id="previewJam">0</div>
                <div class="kompen-preview-lbl">jam</div>
                <div style="margin-top:12px;font-size:12px;opacity:.8;" id="previewFormula">
                    0 jam alpha × 2 × 1 = 0 jam
                </div>
            </div>

            <div class="sp-info" id="previewSp" style="background:#F1F5F9;color:var(--text-2);">
                Masukkan jam alpha untuk melihat status SP
            </div>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                <div style="font-size:12px;font-weight:700;color:var(--text-2);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">Referensi SP</div>
                <div style="display:flex;flex-direction:column;gap:7px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--text-2);">Alpha ≥ 18 jam/semester</span>
                        <span style="background:#FEF3C7;color:#92400E;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">SP 1</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--text-2);">Alpha ≥ 36 jam/semester</span>
                        <span style="background:#FEE2E2;color:#991B1B;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">SP 2</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--text-2);">Alpha ≥ 47 jam/semester</span>
                        <span style="background:#7F1D1D;color:#fff;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">SP 3</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--text-2);">Alpha ≥ 56 jam/semester</span>
                        <span style="background:#450A0A;color:#fff;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">PS</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── Endpoint URLs ─────────────────────────────────────
const SEARCH_URL   = '{{ route("admin.kompensasi.search-mahasiswa") }}';
const RIWAYAT_BASE = '{{ rtrim(url("admin/kompensasi/riwayat-alpha"), "/") }}';

// ── Helpers ───────────────────────────────────────────
function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(String(str ?? '')));
    return d.innerHTML;
}

// ── Preview (jam kompen) ──────────────────────────────
function updatePreview() {
    const alpha      = parseInt(document.getElementById('inputAlpha').value) || 0;
    const multiplier = parseInt(document.getElementById('inputMultiplier').value) || 1;
    const jam        = alpha * 2 * multiplier;

    document.getElementById('previewJam').textContent     = jam;
    document.getElementById('previewFormula').textContent =
        alpha + ' jam alpha × 2 × ' + multiplier + ' = ' + jam + ' jam';

    const spEl = document.getElementById('previewSp');
    if (alpha >= 56) {
        spEl.style.cssText = 'background:#450A0A;color:#fff;border-radius:10px;padding:12px 16px;font-size:13px;font-weight:600;margin-top:12px;';
        spEl.textContent = '⚠ PS — Alpha melebihi batas putus studi!';
    } else if (alpha >= 47) {
        spEl.style.cssText = 'background:#7F1D1D;color:#fff;border-radius:10px;padding:12px 16px;font-size:13px;font-weight:600;margin-top:12px;';
        spEl.textContent = '⚠ SP 3 — Alpha sangat kritis!';
    } else if (alpha >= 36) {
        spEl.style.cssText = 'background:#FEE2E2;color:#991B1B;border-radius:10px;padding:12px 16px;font-size:13px;font-weight:600;margin-top:12px;';
        spEl.textContent = '⚠ SP 2 — Alpha kritis';
    } else if (alpha >= 18) {
        spEl.style.cssText = 'background:#FEF3C7;color:#92400E;border-radius:10px;padding:12px 16px;font-size:13px;font-weight:600;margin-top:12px;';
        spEl.textContent = '⚠ SP 1 — Alpha melebihi batas';
    } else if (alpha > 0) {
        spEl.style.cssText = 'background:#F0FDF4;color:#166534;border-radius:10px;padding:12px 16px;font-size:13px;font-weight:600;margin-top:12px;';
        spEl.textContent = '✓ Alpha masih dalam batas aman';
    } else {
        spEl.style.cssText = 'background:#F1F5F9;color:#6B7280;border-radius:10px;padding:12px 16px;font-size:13px;font-weight:600;margin-top:12px;';
        spEl.textContent = 'Masukkan jam alpha untuk melihat status SP';
    }
}

document.getElementById('inputAlpha').addEventListener('input', updatePreview);
document.getElementById('inputMultiplier').addEventListener('change', updatePreview);

// ── Mahasiswa Search ──────────────────────────────────
let searchTimer = null;
const searchInput  = document.getElementById('searchMhsInput');
const resultsEl    = document.getElementById('searchMhsResults');

searchInput.addEventListener('input', function () {
    clearTimeout(searchTimer);
    const q = this.value.trim();
    if (q.length < 2) { hideResults(); return; }
    resultsEl.innerHTML = '<div class="mhs-result-loading"><i class="bi bi-hourglass-split"></i> Mencari...</div>';
    showResults();
    searchTimer = setTimeout(() => doSearch(q), 300);
});

searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') hideResults();
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('#searchMhsWrapper')) hideResults();
});

function showResults() { resultsEl.style.display = 'block'; }
function hideResults()  { resultsEl.style.display = 'none'; }

function doSearch(q) {
    fetch(SEARCH_URL + '?q=' + encodeURIComponent(q), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.length) {
            resultsEl.innerHTML = '<div class="mhs-result-empty"><i class="bi bi-person-x"></i> Tidak ada mahasiswa ditemukan</div>';
        } else {
            resultsEl.innerHTML = data.map(m => `
                <div class="mhs-result-item"
                     data-id="${escHtml(m.id)}"
                     data-nama="${escHtml(m.nama)}"
                     data-nim="${escHtml(m.nim)}"
                     data-kelas="${escHtml(m.kelas_nama)}"
                     data-semester="${escHtml(m.semester_aktif)}"
                     data-tahun="${escHtml(m.tahun_akademik_aktif)}"
                     onclick="selectFromResult(this)">
                    <div class="mhs-result-name">${escHtml(m.nama)}</div>
                    <div class="mhs-result-sub">NIM: ${escHtml(m.nim)} &bull; ${escHtml(m.kelas_nama)}</div>
                </div>
            `).join('');
        }
        showResults();
    })
    .catch(() => {
        resultsEl.innerHTML = '<div class="mhs-result-empty" style="color:#EF4444;">Gagal memuat data. Coba lagi.</div>';
    });
}

function selectFromResult(el) {
    selectMahasiswa(
        el.dataset.id,
        el.dataset.nama,
        el.dataset.nim,
        el.dataset.kelas,
        el.dataset.semester,
        el.dataset.tahun
    );
    hideResults();
    searchInput.value = '';
}

function selectMahasiswa(id, nama, nim, kelas, semester, tahun) {
    document.getElementById('selectedMhsId').value = id;
    document.getElementById('selectedMhsName').textContent = nama;
    document.getElementById('selectedMhsSub').textContent  = 'NIM: ' + nim + ' · ' + kelas;
    document.getElementById('selectedMhsDisplay').style.display = '';
    document.getElementById('searchMhsWrapper').style.display   = 'none';

    // Auto-fill semester & tahun_akademik
    document.getElementById('inputSemester').value      = semester;
    document.getElementById('inputTahunAkademik').value = tahun;

    loadRiwayatAlpha(id);

    // Scroll ke form semester setelah riwayat muncul
    setTimeout(function () {
        document.getElementById('sectionSemester').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 400);
}

function resetMhsSearch() {
    document.getElementById('selectedMhsId').value = '';
    document.getElementById('selectedMhsDisplay').style.display = 'none';
    document.getElementById('searchMhsWrapper').style.display   = '';
    document.getElementById('riwayatAlphaCard').style.display   = 'none';
    searchInput.value = '';
    searchInput.focus();
}

// ── Riwayat Alpha ─────────────────────────────────────
function loadRiwayatAlpha(mahasiswaId) {
    const card = document.getElementById('riwayatAlphaCard');
    card.style.display = '';
    document.getElementById('riwayatTableWrapper').innerHTML =
        '<div class="mhs-result-loading"><i class="bi bi-hourglass-split"></i> Memuat riwayat kehadiran...</div>';

    fetch(RIWAYAT_BASE + '/' + mahasiswaId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => renderRiwayat(data))
    .catch(() => {
        document.getElementById('riwayatTableWrapper').innerHTML =
            '<div style="padding:12px;color:#EF4444;font-size:13px;">Gagal memuat riwayat kehadiran.</div>';
    });
}

function spClass(alpha) {
    if (alpha >= 56) return 'row-ps';
    if (alpha >= 47) return 'sp3';
    if (alpha >= 36) return 'sp2';
    if (alpha >= 18) return 'sp1';
    return '';
}

function spBadge(alpha) {
    if (alpha >= 56) return '<span class="sp-badge ps-badge">PS</span>';
    if (alpha >= 47) return '<span class="sp-badge sp3-badge">SP 3</span>';
    if (alpha >= 36) return '<span class="sp-badge sp2-badge">SP 2</span>';
    if (alpha >= 18) return '<span class="sp-badge sp1-badge">SP 1</span>';
    return '<span class="aman-badge">Aman</span>';
}

function renderRiwayat(data) {
    const riwayat       = data.riwayat || [];
    const semesterAktif = data.semester_aktif;
    const wrapper       = document.getElementById('riwayatTableWrapper');

    if (!riwayat.length) {
        wrapper.innerHTML = '<div style="padding:12px;text-align:center;color:var(--text-3);font-size:13px;">Belum ada data absensi untuk mahasiswa ini.</div>';
        return;
    }

    const rows = riwayat.map(r => {
        const isAktif = r.semester == semesterAktif;
        const cls     = [spClass(r.jam_alpha), isAktif ? 'aktif-row' : ''].filter(Boolean).join(' ');
        const alphaColor = r.jam_alpha >= 18 ? '#DC2626' : 'inherit';
        const aktifPill  = isAktif
            ? ' <span style="font-size:10px;background:#3B82F6;color:#fff;padding:1px 6px;border-radius:8px;font-weight:600;">Aktif</span>'
            : '';
        return `<tr class="${cls}">
            <td>Smt ${escHtml(r.semester)}${aktifPill}</td>
            <td>${escHtml(r.tahun_akademik)}</td>
            <td style="font-weight:600;color:${alphaColor}">${escHtml(r.jam_alpha)}</td>
            <td>${escHtml(r.jam_izin)}</td>
            <td>${escHtml(r.jam_sakit)}</td>
            <td>${spBadge(r.jam_alpha)}</td>
        </tr>`;
    }).join('');

    wrapper.innerHTML = `
        <div style="overflow-x:auto;">
            <table class="riwayat-table">
                <thead>
                    <tr>
                        <th>Semester</th>
                        <th>Tahun Akademik</th>
                        <th>Alpha</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>
        <div style="font-size:11px;color:var(--text-3);margin-top:8px;display:flex;gap:8px;flex-wrap:wrap;">
            <span class="sp-badge sp1-badge">SP 1</span> ≥18j &nbsp;
            <span class="sp-badge sp2-badge">SP 2</span> ≥36j &nbsp;
            <span class="sp-badge sp3-badge">SP 3</span> ≥47j &nbsp;
            <span class="sp-badge ps-badge">PS</span> ≥56j
        </div>
    `;

    // Auto-fill jam_alpha dari semester aktif (hanya jika belum diisi manual)
    const aktifRow = riwayat.find(r => r.semester == semesterAktif);
    if (aktifRow) {
        const alphaInput = document.getElementById('inputAlpha');
        if (!alphaInput.value || alphaInput.value == '0') {
            alphaInput.value = aktifRow.jam_alpha;
        }
        updatePreview();
    }
}

// ── Init: pre-fill dari URL param atau old() setelah validasi gagal ─────────
@if($mahasiswa)
(function () {
    document.getElementById('selectedMhsName').textContent = {{ json_encode($mahasiswa->nama) }};
    document.getElementById('selectedMhsSub').textContent  =
        'NIM: ' + {{ json_encode($mahasiswa->nim) }} + ' · ' + {{ json_encode($mahasiswa->kelas->nama ?? '-') }};
    document.getElementById('selectedMhsDisplay').style.display = '';
    document.getElementById('searchMhsWrapper').style.display   = 'none';
    loadRiwayatAlpha({{ $mahasiswa->id }});
})();
@endif

updatePreview();
</script>
@endpush
