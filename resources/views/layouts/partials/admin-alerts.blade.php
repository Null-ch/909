@if (session('success') || session('error'))
    <script type="application/json" data-admin-flash>
        {!! json_encode(array_filter([
            'success' => session('success'),
            'error' => session('error'),
        ])) !!}
    </script>
@endif
