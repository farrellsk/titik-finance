@extends('layouts.app', [
    'title' => 'Aktivitas',
    'breadcrumbs' => ['Aktivitas', 'List'],
])

@section('content')
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover-animation nowrap scroll-horizontal-vertical" id="crudTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Aktivitas</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($t_log as $act)
                                    <tr>
                                        <td>{{ $act->id }}</td>
                                        <td>{{ $act->user->name }}</td>
                                        <td>{{ $act->notes }}</td>
                                        <td>{{ $act->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('after_styles')
        <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    @endpush

    @push('after_scripts')
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#crudTable').DataTable({
                    "order" : [[0, "desc"]]
                });
            });
        </script>
    @endpush
