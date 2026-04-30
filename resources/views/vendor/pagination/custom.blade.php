@if ($paginator->hasPages())
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-top:16px;padding-top:14px;border-top:1px solid var(--border);">
    <div style="font-size:12.5px;color:var(--text-2);">
        Menampilkan <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
        dari <strong>{{ $paginator->total() }}</strong> data
    </div>
    <div style="display:flex;gap:4px;align-items:center;">
        {{-- Prev --}}
        @if($paginator->onFirstPage())
        <span style="border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:13px;color:var(--text-3);background:var(--bg);opacity:.5;">
            ‹ Prev
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}"
           style="border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:13px;color:var(--text-2);background:var(--white);text-decoration:none;transition:all .15s;"
           onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-2)'">
            ‹ Prev
        </a>
        @endif
 
        {{-- Pages --}}
        @foreach($elements as $element)
            @if(is_string($element))
            <span style="padding:6px 4px;font-size:13px;color:var(--text-3);">…</span>
            @endif
            @if(is_array($element))
                @foreach($element as $page => $url)
                @if($page == $paginator->currentPage())
                <span style="border:1.5px solid var(--blue);border-radius:8px;padding:6px 12px;font-size:13px;font-weight:700;color:#fff;background:var(--blue);">
                    {{ $page }}
                </span>
                @else
                <a href="{{ $url }}"
                   style="border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:13px;color:var(--text-2);background:var(--white);text-decoration:none;transition:all .15s;"
                   onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                   onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-2)'">
                    {{ $page }}
                </a>
                @endif
                @endforeach
            @endif
        @endforeach
 
        {{-- Next --}}
        @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           style="border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:13px;color:var(--text-2);background:var(--white);text-decoration:none;transition:all .15s;"
           onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-2)'">
            Next ›
        </a>
        @else
        <span style="border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:13px;color:var(--text-3);background:var(--bg);opacity:.5;">
            Next ›
        </span>
        @endif
    </div>
</div>
@endif
