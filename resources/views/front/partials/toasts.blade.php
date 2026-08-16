@php
    $frontFlash = array_filter([
        'success' => session('success'),
        'error' => session('error'),
        'status' => session('status'),
    ]);
@endphp

@if (count($frontFlash))
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:1080" id="front-toast-container">
        @foreach ($frontFlash as $type => $message)
            <div
                class="toast align-items-center text-bg-{{ $type === 'error' ? 'danger' : 'success' }} border-0"
                role="status"
                aria-live="polite"
                aria-atomic="true"
                data-bs-autohide="true"
                data-bs-delay="4000"
            >
                <div class="d-flex">
                    <div class="toast-body">{{ $message }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Закрыть"></button>
                </div>
            </div>
        @endforeach
    </div>
@endif

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:1080">
    <div
        id="cart-toast"
        class="toast align-items-center text-bg-success border-0"
        role="status"
        aria-live="polite"
        aria-atomic="true"
        data-bs-autohide="true"
        data-bs-delay="3000"
    >
        <div class="d-flex">
            <div class="toast-body" id="cart-toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Закрыть"></button>
        </div>
    </div>
</div>
