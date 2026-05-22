@extends('layouts.admin')
@section('title', 'Kirim Peringatan Email')
@section('page-title', 'Kirim Peringatan Email')
@section('page-sub', 'Kirim notifikasi email ke mahasiswa berisiko secara manual')

@push('styles')
<style>
/* ── Summary Cards ───────────────────────────── */
.sum-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px;}
.sum-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;}
.sum-card-bar{height:3px;}
.sum-card-body{padding:14px 16px;}
.sum-card-val{font-size:30px;font-weight:800;line-height:1;letter-spacing:-1px;}
.sum-card-lbl{font-size:12px;color:var(--text-2);margin-top:4px;font-weight:500;}
.sum-card-pct{font-size:11px;font-weight:700;margin-top:6px;display:inline-flex;align-items:center;gap:3px;padding:2px 8px;border-radius:99px;}

/* ── Filter Bar ──────────────────────────────── */
.filter-bar{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:14px 20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px;}
.filter-label{font-size:12px;font-weight:700;color:var(--text-2);white-space:nowrap;}
.filter-select{padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--white);outline:none;cursor:pointer;transition:border-color .15s;}
.filter-select:focus{border-color:var(--blue);}
.jenis-pills{display:flex;gap:6px;flex-wrap:wrap;}
.jenis-pill{padding:7px 14px;border-radius:99px;font-size:12px;font-weight:600;border:1.5px solid var(--border);color:var(--text-2);background:var(--white);cursor:pointer;text-decoration:none;transition:all .15s;white-space:nowrap;}
.jenis-pill:hover{border-color:var(--blue);color:var(--blue);}
.jenis-pill.active-semua{background:#EF4444;color:#fff;border-color:#EF4444;}
.jenis-pill.active-nilai{background:#F59E0B;color:#fff;border-color:#F59E0B;}
.jenis-pill.active-absensi{background:#8B5CF6;color:#fff;border-color:#8B5CF6;}

/* ── Tabel ───────────────────────────────────── */
.risk-table{width:100%;border-collapse:collapse;}
.risk-table thead th{font-size:11px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.6px;padding:10px 14px;border-bottom:1.5px solid var(--border);background:#FAFBFF;white-space:nowrap;}
.risk-table tbody tr{border-bottom:1px solid #F8FAFC;transition:background .1s;}
.risk-table tbody tr:last-child{border-bottom:none;}
.risk-table tbody tr:hover{background:#FAFBFF;}
.risk-table tbody td{padding:12px 14px;vertical-align:middle;font-size:13px;}

/* ── Badge kategori ──────────────────────────── */
.badge-de{display:inline-flex;align-items:center;gap:3px;background:#FEF9C3;color:#854D0E;border-radius:99px;padding:3px 9px;font-size:11px;font-weight:700;white-space:nowrap;}
.badge-alpha{display:inline-flex;align-items:center;gap:3px;background:#EDE9FE;color:#5B21B6;border-radius:99px;padding:3px 9px;font-size:11px;font-weight:700;white-space:nowrap;}
.badge-both{display:inline-flex;align-items:center;gap:3px;background:#FEE2E2;color:#991B1B;border-radius:99px;padding:3px 9px;font-size:11px;font-weight:700;white-space:nowrap;}

/* ── Avatar ──────────────────────────────────── */
.avatar{width:34px;height:34px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;}

/* ── Tombol kirim ────────────────────────────── */
.btn-kirim-satu{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;background:var(--blue);color:#fff;border:none;cursor:pointer;transition:all .15s;}
.btn-kirim-satu:hover{background:#1D4ED8;transform:translateY(-1px);}
.btn-kirim-satu:disabled{background:#94A3B8;cursor:not-allowed;transform:none;}
.btn-kirim-massal{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-size:13px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;background:#EF4444;color:#fff;border:none;cursor:pointer;transition:all .15s;}
.btn-kirim-massal:hover{background:#DC2626;transform:translateY(-1px);box-shadow:0 4px 12px rgba(239,68,68,.3);}
.btn-kirim-massal:disabled{background:#94A3B8;cursor:not-allowed;transform:none;}

/* ── Status kirim ────────────────────────────── */
.status-sent{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:99px;font-size:11px;font-weight:700;background:#DCFCE7;color:#15803D;}
.status-failed{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:99px;font-size:11px;font-weight:700;background:#FEE2E2;color:#991B1B;}
.status-sending{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:99px;font-size:11px;font-weight:700;background:#EFF6FF;color:#1D4ED8;}

/* ── Toast notifikasi ────────────────────────── */
.toast-wrap{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;}
.toast-item{background:#0F172A;color:#fff;padding:12px 18px;border-radius:12px;font-size:13px;font-weight:500;box-shadow:0 8px 24px rgba(0,0,0,.2);display:flex;align-items:center;gap:8px;animation:toastIn .3s cubic-bezier(.16,1,.3,1) both;}
.toast-item.success{border-left:4px solid #22C55E;}
.toast-item.error{border-left:4px solid #EF4444;}
@keyframes toastIn{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}

/* ── Checkbox ────────────────────────────────── */
.cb-mhs{width:16px;height:16px;cursor:pointer;accent-color:var(--blue);}

/* ── Progress bar massal ─────────────────────── */
.progress-wrap{display:none;margin-bottom:16px;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:14px 20px;}
.progress-bar-outer{width:100%;height:8px;background:#F1F5F9;border-radius:4px;overflow:hidden;margin-top:8px;}
.progress-bar-inner{height:100%;background:var(--blue);border-radius:4px;transition:width .3s;}

@media(max-width:768px){
    .sum-cards{grid-template-columns:repeat(2,1fr);}
    .hide-mobile{display:none!important;}
}
</style>
@endpush

@section('content')

{{-- Banner --}}
@include('components.page-banner', [
    'gradient'    => 'linear-gradient(135deg, #1E3A8A 0%, #2563EB 55%, #60A5FA 100%)',
    'icon'        => 'bi-envelope-exclamation-fill',
    'title'       => 'Kirim Peringatan Email',
    'sub'         => 'Kirim notifikasi email akademik ke mahasiswa berisiko secara manual',
    'chips'       => [
        ['icon' => 'bi-exclamation-triangle-fill', 'label' => $summary['total_berisiko'] . ' Mahasiswa Berisiko'],
        ['icon' => 'bi-x-circle-fill',             'label' => $summary['berisiko_nilai'] . ' Nilai D/E'],
        ['icon' => 'bi-clock-history',             'label' => $summary['berisiko_absensi'] . ' Alpha ≥18 Jam'],
    ],
    'badge_num'   => $summary['total_berisiko'],
    'badge_label' => "Total\nBerisiko",
])

{{-- Summary Cards --}}
<div class="sum-cards">
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#EF4444,#FCA5A5);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#EF4444;">{{ $summary['total_berisiko'] }}</div>
            <div class="sum-card-lbl">Total Berisiko</div>
            <div class="sum-card-pct" style="background:#FEE2E2;color:#991B1B;">Perlu dihubungi</div>
        </div>
    </div>
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#F59E0B,#FCD34D);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#F59E0B;">{{ $summary['berisiko_nilai'] }}</div>
            <div class="sum-card-lbl">Berisiko Nilai D/E</div>
            <div class="sum-card-pct" style="background:#FEF9C3;color:#854D0E;">Nilai buruk</div>
        </div>
    </div>
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#8B5CF6,#A78BFA);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#8B5CF6;">{{ $summary['berisiko_absensi'] }}</div>
            <div class="sum-card-lbl">Alpha ≥18 Jam</div>
            <div class="sum-card-pct" style="background:#EDE9FE;color:#5B21B6;">Absensi tinggi</div>
        </div>
    </div>
    <div class="sum-card">
        <div class="sum-card-bar" style="background:linear-gradient(90deg,#DC2626,#991B1B);"></div>
        <div class="sum-card-body">
            <div class="sum-card-val" style="color:#DC2626;">{{ $summary['berisiko_keduanya'] }}</div>
            <div class="sum-card-lbl">Berisiko Keduanya</div>
            <div class="sum-card-pct" style="background:#FEE2E2;color:#991B1B;">Prioritas utama</div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.kirim-peringatan.index') }}" id="filterForm">
<div class="filter-bar">
    <span class="filter-label">Filter Kelas:</span>
    <select name="kelas_id" class="filter-select" onchange="document.getElementById('filterForm').submit()">
        <option value="">Semua Kelas</option>
        @foreach($kelasList as $kelas)
        <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
            {{ $kelas->nama }}
        </option>
        @endforeach
    </select>

    <span class="filter-label" style="margin-left:8px;">Kategori:</span>
    <div class="jenis-pills">
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'semua']) }}"
           class="jenis-pill {{ $filterJenis === 'semua' ? 'active-semua' : '' }}">
            Semua ({{ $summary['total_berisiko'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'nilai']) }}"
           class="jenis-pill {{ $filterJenis === 'nilai' ? 'active-nilai' : '' }}">
            Nilai D/E ({{ $summary['berisiko_nilai'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'absensi']) }}"
           class="jenis-pill {{ $filterJenis === 'absensi' ? 'active-absensi' : '' }}">
            Alpha ≥18 Jam ({{ $summary['berisiko_absensi'] }})
        </a>
    </div>
</div>
</form>

{{-- Progress bar massal --}}
<div class="progress-wrap" id="progressWrap">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div style="font-size:13px;font-weight:700;color:var(--text-1);" id="progressLabel">Mengirim email...</div>
        <div style="font-size:12px;color:var(--text-2);" id="progressCount">0 / 0</div>
    </div>
    <div class="progress-bar-outer">
        <div class="progress-bar-inner" id="progressBar" style="width:0%;"></div>
    </div>
</div>

{{-- Tabel --}}
<div class="card-white tbl-card-v2">
    <div class="tbl-head-v2">
        <div>
            <div class="tbl-title-v2">Daftar Mahasiswa Berisiko</div>
            <div class="tbl-sub-v2">
                {{ $mahasiswaBerisiko->count() }} mahasiswa berisiko
                @if($kelasId) · Kelas: {{ $kelasList->find($kelasId)->nama ?? '' }} @endif
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="cariMhs" placeholder="Cari nama / NIM...">
            </div>
            @if($mahasiswaBerisiko->count() > 0)
            <button class="btn-kirim-massal" id="btnKirimMassal" onclick="kirimMassal()">
                <i class="bi bi-envelope-fill"></i>
                Kirim Semua (<span id="totalTerpilih">{{ $mahasiswaBerisiko->count() }}</span>)
            </button>
            @endif
        </div>
    </div>

    {{-- Checkbox pilih semua --}}
    @if($mahasiswaBerisiko->count() > 0)
    <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#F8FAFC;border-radius:8px;margin-bottom:12px;">
        <input type="checkbox" id="cbAll" class="cb-mhs" onchange="toggleAll(this)">
        <label for="cbAll" style="font-size:12.5px;font-weight:600;color:var(--text-2);cursor:pointer;">
            Pilih semua mahasiswa
        </label>
        <span style="font-size:11.5px;color:var(--text-3);" id="labelTerpilih">
            {{ $mahasiswaBerisiko->count() }} terpilih
        </span>
    </div>
    @endif

    <div style="overflow-x:auto;">
        <table class="risk-table" id="riskTable">
            <thead>
                <tr>
                    <th style="width:40px;"><input type="checkbox" id="cbAllHead" class="cb-mhs" onchange="toggleAll(this)"></th>
                    <th>#</th>
                    <th>Mahasiswa</th>
                    <th class="hide-mobile">Kelas</th>
                    <th class="hide-mobile">Dosen PA</th>
                    <th style="text-align:center;">IPK</th>
                    <th style="text-align:center;">Nilai D/E</th>
                    <th style="text-align:center;">Alpha</th>
                    <th style="text-align:center;">Kategori</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="riskBody">
                @forelse($mahasiswaBerisiko as $i => $mhs)
                @php
                    $colors     = ['#2563EB','#EF4444','#8B5CF6','#F59E0B','#0891B2','#DB2777','#16A34A'];
                    $aColor     = $colors[$i % count($colors)];
                    $isKeduanya = count($mhs['kategori']) >= 2;
                    $isNilai    = in_array('nilai', $mhs['kategori']);
                    $isAbsensi  = in_array('absensi', $mhs['kategori']);
                    $alphaWidth = min(($mhs['total_alpha'] / 36) * 100, 100);
                @endphp
                <tr data-nama="{{ strtolower($mhs['nama']) }}" data-nim="{{ $mhs['nim'] }}"
                    data-id="{{ $mhs['id'] }}"
                    style="{{ $isKeduanya ? 'background:rgba(239,68,68,.025);' : '' }}"
                    id="row-{{ $mhs['id'] }}">

                    <td>
                        <input type="checkbox" class="cb-mhs cb-row" value="{{ $mhs['id'] }}"
                               checked onchange="updateCount()">
                    </td>

                    <td style="font-size:12px;color:var(--text-3);">{{ $i + 1 }}</td>

                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="avatar" style="background:{{ $aColor }};">
                                {{ strtoupper(substr($mhs['nama'], 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:13.5px;color:var(--text-1);">{{ $mhs['nama'] }}</div>
                                <div style="font-size:11px;color:var(--text-3);font-family:monospace;">{{ $mhs['nim'] }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="hide-mobile" style="font-size:12.5px;color:var(--text-2);">{{ $mhs['kelas'] }}</td>
                    <td class="hide-mobile" style="font-size:12.5px;color:var(--text-2);">{{ $mhs['dosen_pa'] }}</td>

                    <td style="text-align:center;font-weight:700;font-size:14px;color:{{ $mhs['ipk'] < 2.5 ? '#EF4444' : 'var(--text-1)' }};">
                        {{ $mhs['ipk'] }}
                    </td>

                    <td style="text-align:center;">
                        @if($mhs['jumlah_de'] > 0)
                        <span style="font-weight:800;font-size:16px;color:#F59E0B;">{{ $mhs['jumlah_de'] }}</span>
                        <div style="font-size:10.5px;color:var(--text-3);">matkul</div>
                        @else
                        <span style="color:var(--text-3);font-size:12px;">—</span>
                        @endif
                    </td>

                    <td style="text-align:center;">
                        <span style="font-weight:800;font-size:16px;color:{{ $mhs['total_alpha'] >= 18 ? '#8B5CF6' : 'var(--text-2)' }};">
                            {{ $mhs['total_alpha'] }}j
                        </span>
                    </td>

                    <td style="text-align:center;">
                        @if($isKeduanya)
                        <span class="badge-both"><i class="bi bi-exclamation-triangle-fill" style="font-size:10px;"></i> Nilai + Alpha</span>
                        @elseif($isNilai)
                        <span class="badge-de"><i class="bi bi-x-circle-fill" style="font-size:10px;"></i> Nilai D/E</span>
                        @elseif($isAbsensi)
                        <span class="badge-alpha"><i class="bi bi-clock-history" style="font-size:10px;"></i> Alpha ≥18j</span>
                        @endif
                    </td>

                    <td style="text-align:center;">
                        <div id="status-{{ $mhs['id'] }}">
                            <button class="btn-kirim-satu" onclick="kirimSatu({{ $mhs['id'] }}, this)">
                                <i class="bi bi-envelope-fill"></i> Kirim
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:60px 20px;color:var(--text-3);">
                        <i class="bi bi-shield-check-fill" style="font-size:40px;display:block;margin-bottom:10px;color:#22C55E;opacity:.5;"></i>
                        <p style="font-size:15px;font-weight:700;color:#166534;">Tidak ada mahasiswa berisiko!</p>
                        <p style="font-size:13px;">Semua mahasiswa memiliki performa akademik yang baik.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Toast container --}}
<div class="toast-wrap" id="toastWrap"></div>

@endsection

@push('scripts')
<script>
var CSRF = '{{ csrf_token() }}';
var URL_SATU    = '{{ route("admin.kirim-peringatan.satu") }}';
var URL_MASSAL  = '{{ route("admin.kirim-peringatan.massal") }}';

// ── Search ─────────────────────────────────────
document.getElementById('cariMhs').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#riskBody tr[data-nama]').forEach(function(r) {
        var nama = (r.dataset.nama || '').toLowerCase();
        var nim  = (r.dataset.nim  || '').toLowerCase();
        r.style.display = (nama.includes(q) || nim.includes(q)) ? '' : 'none';
    });
});

// ── Toggle semua checkbox ──────────────────────
function toggleAll(el) {
    var checked = el.checked;
    document.querySelectorAll('.cb-row').forEach(cb => cb.checked = checked);
    document.getElementById('cbAll').checked    = checked;
    document.getElementById('cbAllHead').checked = checked;
    updateCount();
}

function updateCount() {
    var total = document.querySelectorAll('.cb-row:checked').length;
    document.getElementById('totalTerpilih').textContent = total;
    document.getElementById('labelTerpilih').textContent = total + ' terpilih';
}

// ── Toast notifikasi ───────────────────────────
function showToast(msg, type) {
    var wrap = document.getElementById('toastWrap');
    var el   = document.createElement('div');
    el.className = 'toast-item ' + (type === 'success' ? 'success' : 'error');
    el.innerHTML = '<i class="bi bi-' + (type === 'success' ? 'check-circle-fill' : 'x-circle-fill') + '"></i> ' + msg;
    wrap.appendChild(el);
    setTimeout(() => el.remove(), 4000);
}

// ── Kirim satu ─────────────────────────────────
function kirimSatu(id, btn) {
    var statusEl = document.getElementById('status-' + id);
    statusEl.innerHTML = '<span class="status-sending"><i class="bi bi-hourglass-split"></i> Mengirim...</span>';
    btn.disabled = true;

    fetch(URL_SATU, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ mahasiswa_id: id })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            statusEl.innerHTML = '<span class="status-sent"><i class="bi bi-check-circle-fill"></i> Terkirim</span>';
            showToast(data.message, 'success');
        } else {
            statusEl.innerHTML = '<span class="status-failed"><i class="bi bi-x-circle-fill"></i> Gagal</span>';
            showToast(data.message, 'error');
        }
    })
    .catch(() => {
        statusEl.innerHTML = '<span class="status-failed"><i class="bi bi-x-circle-fill"></i> Gagal</span>';
        showToast('Terjadi kesalahan saat mengirim email.', 'error');
    });
}

// ── Kirim massal ───────────────────────────────
function kirimMassal() {
    var ids = [];
    document.querySelectorAll('.cb-row:checked').forEach(cb => ids.push(parseInt(cb.value)));

    if (ids.length === 0) {
        showToast('Pilih minimal satu mahasiswa terlebih dahulu.', 'error');
        return;
    }

    if (!confirm('Kirim email peringatan ke ' + ids.length + ' mahasiswa?')) return;

    var btnMassal = document.getElementById('btnKirimMassal');
    btnMassal.disabled = true;
    btnMassal.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';

    var progressWrap = document.getElementById('progressWrap');
    progressWrap.style.display = 'block';
    document.getElementById('progressLabel').textContent = 'Mengirim ' + ids.length + ' email...';
    document.getElementById('progressCount').textContent = '0 / ' + ids.length;
    document.getElementById('progressBar').style.width = '0%';

    // Kirim satu per satu dengan progress
    var sent = 0;
    var failed = 0;

    function kirimBerikutnya(index) {
        if (index >= ids.length) {
            // Selesai
            document.getElementById('progressBar').style.width = '100%';
            document.getElementById('progressLabel').textContent = '✅ Selesai!';
            btnMassal.disabled = false;
            btnMassal.innerHTML = '<i class="bi bi-envelope-fill"></i> Kirim Semua (<span id="totalTerpilih">' + ids.length + '</span>)';
            showToast(sent + ' email berhasil dikirim' + (failed > 0 ? ', ' + failed + ' gagal.' : '.'), sent > 0 ? 'success' : 'error');
            return;
        }

        var id = ids[index];
        var statusEl = document.getElementById('status-' + id);
        if (statusEl) statusEl.innerHTML = '<span class="status-sending"><i class="bi bi-hourglass-split"></i> Mengirim...</span>';

        fetch(URL_SATU, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ mahasiswa_id: id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                sent++;
                if (statusEl) statusEl.innerHTML = '<span class="status-sent"><i class="bi bi-check-circle-fill"></i> Terkirim</span>';
            } else {
                failed++;
                if (statusEl) statusEl.innerHTML = '<span class="status-failed"><i class="bi bi-x-circle-fill"></i> Gagal</span>';
            }
        })
        .catch(() => {
            failed++;
            if (statusEl) statusEl.innerHTML = '<span class="status-failed"><i class="bi bi-x-circle-fill"></i> Gagal</span>';
        })
        .finally(() => {
            var done = index + 1;
            var pct  = Math.round((done / ids.length) * 100);
            document.getElementById('progressBar').style.width = pct + '%';
            document.getElementById('progressCount').textContent = done + ' / ' + ids.length;
            kirimBerikutnya(index + 1);
        });
    }

    kirimBerikutnya(0);
}

// Inisialisasi count
updateCount();
</script>
@endpush
