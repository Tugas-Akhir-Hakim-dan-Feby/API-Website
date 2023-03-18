@extends('template')

@section('content')
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="alert alert-{{ $status_code == 200 ? 'success' : 'danger' }}">
                        {{ $message }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = "{{ url('auth/login') }}"
        }, 1000);
    </script>
@endsection
