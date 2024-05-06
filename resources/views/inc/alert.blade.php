@push('after_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endpush

@push('before_scripts')
    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
@endpush

{{-- Success message --}}
@if (session('status'))
    @push('before_scripts')
        <script>
            Swal.fire({
                title: "Success!",
                type: "success",
                text: '{{ session('status') }}',
                width: 600,
                padding: "3em",
                color: "#716add",
                backdrop: `
    rgba(0,0,123,0.4)
    url("/image/animasi-uang.gif")
    left top
    no-repeat
  `
            });

        </script>
    @endpush
@endif
{{-- Success message --}}

{{-- Error message --}}
@if ($errors->any())
    @push('after_styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.css') }}">
    @endpush

    @push('after_scripts')
        <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>

        <script>
            @foreach ($errors->all() as $item)
                toastr.error('{{ $item }}', 'Oops', {
                    "timeOut": 0
                });
            @endforeach
        </script>
    @endpush
@endif
{{-- Error messages --}}
