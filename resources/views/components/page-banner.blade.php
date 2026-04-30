@php
    $gradient    = $gradient    ?? 'linear-gradient(135deg,#1E3A8A,#2563EB,#3B82F6)';
    $icon        = $icon        ?? 'bi-grid-1x2-fill';
    $title       = $title       ?? 'Halaman';
    $sub         = $sub         ?? '';
    $chips       = $chips       ?? [];
    $badge_num   = $badge_num   ?? null;
    $badge_label = $badge_label ?? '';
    $badge2_num  = $badge2_num  ?? null;
    $badge2_label= $badge2_label?? '';
@endphp
 
<div class="page-banner-wrap" style="background:{{ $gradient }};">
    {{-- Dekorasi --}}
    <div class="banner-deco banner-deco-1"></div>
    <div class="banner-deco banner-deco-2"></div>
    <div class="banner-deco banner-deco-3"></div>
 
    {{-- Kiri --}}
    <div class="banner-left">
        <div class="banner-title-row">
            <div class="banner-icon-box">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div class="banner-title">{{ $title }}</div>
        </div>
 
        @if($sub)
        <div class="banner-sub">{{ $sub }}</div>
        @endif
 
        {{-- Chips — hanya desktop --}}
        @if(count($chips) > 0)
        <div class="banner-chips">
            @foreach($chips as $chip)
            <span class="banner-chip">
                <i class="bi {{ $chip['icon'] }}"></i>
                {{ $chip['label'] }}
            </span>
            @endforeach
        </div>
        @endif
    </div>
 
    {{-- Kanan: badges — hanya desktop --}}
    @if($badge_num !== null || $badge2_num !== null)
    <div class="banner-badges">
        @if($badge_num !== null)
        <div class="banner-badge">
            <div class="banner-badge-num">{{ $badge_num }}</div>
            <div class="banner-badge-label">{{ $badge_label }}</div>
        </div>
        @endif
        @if($badge2_num !== null)
        <div class="banner-badge">
            <div class="banner-badge-num">{{ $badge2_num }}</div>
            <div class="banner-badge-label">{{ $badge2_label }}</div>
        </div>
        @endif
    </div>
    @endif
</div>
 
@once
@push('styles')
<style>
/* ── Banner wrap ─────────────────────────────────── */
.page-banner-wrap {
    border-radius: 14px;
    padding: 22px 26px;
    margin-bottom: 22px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    animation: bannerIn .35s ease both;
}
 
/* ── Dekorasi lingkaran ─────────────────────────── */
.banner-deco {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.banner-deco-1 {
    width: 200px; height: 200px;
    top: -60px; right: -60px;
    background: rgba(255,255,255,.06);
}
.banner-deco-2 {
    width: 140px; height: 140px;
    bottom: -60px; right: 140px;
    background: rgba(255,255,255,.04);
}
.banner-deco-3 {
    width: 70px; height: 70px;
    top: 10px; right: 220px;
    background: rgba(255,255,255,.03);
}
 
/* ── Kiri ───────────────────────────────────────── */
.banner-left {
    position: relative;
    z-index: 1;
    min-width: 0;
    flex: 1;
}
 
.banner-title-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
}
 
.banner-icon-box {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: rgba(255,255,255,.18);
    backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
    color: #fff;
}
 
.banner-title {
    font-size: 18px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -.3px;
    line-height: 1.2;
}
 
.banner-sub {
    font-size: 12.5px;
    color: rgba(255,255,255,.75);
    margin-bottom: 12px;
    line-height: 1.4;
}
 
/* ── Chips ──────────────────────────────────────── */
.banner-chips {
    display: flex;
    gap: 7px;
    flex-wrap: wrap;
}
.banner-chip {
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.22);
    border-radius: 20px;
    padding: 4px 11px;
    font-size: 11.5px;
    font-weight: 600;
    color: #fff;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    backdrop-filter: blur(4px);
    white-space: nowrap;
}
 
/* ── Badges (kanan) ─────────────────────────────── */
.banner-badges {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.banner-badge {
    background: rgba(255,255,255,.16);
    border: 1px solid rgba(255,255,255,.26);
    border-radius: 12px;
    padding: 14px 18px;
    text-align: center;
    backdrop-filter: blur(4px);
    min-width: 80px;
}
.banner-badge-num {
    font-size: 26px;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    letter-spacing: -1px;
}
.banner-badge-label {
    font-size: 10.5px;
    color: rgba(255,255,255,.72);
    margin-top: 3px;
    line-height: 1.3;
    white-space: pre-line;
}
 
/* ── Animasi ────────────────────────────────────── */
@keyframes bannerIn {
    from { opacity:0; transform: translateY(-8px); }
    to   { opacity:1; transform: translateY(0); }
}
 
/* ── RESPONSIVE ─────────────────────────────────── */
@media (max-width: 768px) {
    .page-banner-wrap {
        padding: 16px 18px;
        border-radius: 12px;
        flex-direction: row;          /* tetap row */
        align-items: flex-start;
        gap: 12px;
    }
 
    /* Sembunyikan chips & badges di mobile */
    .banner-chips  { display: none; }
    .banner-badges { display: none; }
 
    /* Sembunyikan dekorasi besar */
    .banner-deco-1 { width:100px; height:100px; top:-30px; right:-30px; }
    .banner-deco-2, .banner-deco-3 { display: none; }
 
    .banner-icon-box { width:32px; height:32px; font-size:14px; }
    .banner-title    { font-size: 15px; }
    .banner-sub      { font-size: 11.5px; margin-bottom: 0; }
}
 
@media (max-width: 400px) {
    .banner-sub { display: none; }
    .page-banner-wrap { padding: 14px 16px; }
}
</style>
@endpush
@endonce