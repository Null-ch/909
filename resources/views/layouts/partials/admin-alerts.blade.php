@if (session('success'))
    <div class="alert alert-success" style="margin-bottom: var(--gap);">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" style="margin-bottom: var(--gap);">
        {{ session('error') }}
    </div>
@endif
