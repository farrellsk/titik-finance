@extends('layouts.app', [
    'title' => 'Setting',
    'breadcrumbs' => ['Setting', 'List'],
])

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('setting-add') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $t_setting ? $t_setting->id : "" }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_perusahaan">Nama Perusahaan<span style="color:red"> *</span></label>
                                <input type="text" class="form-control" name="nama_perusahaan" value="{{ $t_setting ? $t_setting->nama_perusahaan : ""  }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alamat">Alamat<span style="color:red"> *</span></label>
                                <input type="text" class="form-control" name="alamat" value="{{ $t_setting ? $t_setting->alamat : "" }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email<span style="color:red"> *</span></label>
                                <input type="text" class="form-control" name="email" value="{{ $t_setting ? $t_setting->email : "" }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_hp">No. Hp<span style="color:red"> *</span></label>
                                <input type="text" class="form-control" name="no_hp" value="{{ $t_setting ? $t_setting->no_hp : ""  }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="logo">Logo Perusahaan</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="logo" name="logo"
                                        accept=".png, .jpg, .jpeg" value="{{$t_setting ? $t_setting->logo : "" }}">
                                    <label class="custom-file-label" for="logo">Choose file</label>
                                </div>
                                <small class="form-text text-muted">Format yang diizinkan: png, jpg, jpeg. Maksimal
                                    2MB| <a href="{{ $t_setting ? asset('images/setting/logo/' . $t_setting->logo ) : ""  }}" target="_blank">foto sebelumnya<i class="feather icon-arrow-up-right"></i></a> </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image_ttd">Gambar Tanda Tangan</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image_ttd" name="image_ttd" accept=".png, .jpg, .jpeg" value="{{ $t_setting ? $t_setting->image_ttd : ""}}">
                                    <label class="custom-file-label" for="image_ttd">Choose file</label>
                                </div>
                                <small class="form-text text-muted">Format yang diizinkan: png, jpg, jpeg. Maksimal 2MB| <a href="{{ $t_setting ? asset('images/setting/image_ttd/' . $t_setting->image_ttd ) : ""  }}" target="_blank">foto sebelumnya<i class="feather icon-arrow-up-right"></i></a> </small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_ttd">Nama Ttd<span style="color:red"> *</span></label>
                                <input type="text" class="form-control" name="nama_ttd" value="{{ $t_setting ? $t_setting->nama_ttd  : ""}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan<span style="color:red"> *</span></label>
                                <input type="text" class="form-control" name="kecamatan" value="{{$t_setting ? $t_setting->kecamatan : ""}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kota">Kota<span style="color:red"> *</span></label>
                                <input type="text" class="form-control" name="kota" value="{{$t_setting ? $t_setting->kota : "" }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
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
        function deleteConfirmation(itemId) {
            var form = document.getElementById('deleteForm' + itemId);

            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure to delete this item?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            });
        }

        document.querySelectorAll('.delete').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();

                var form = item.parentElement.querySelector('form');
                var itemId = form.id.replace('deleteForm', '');

                deleteConfirmation(itemId);
            });
        });

        $(document).ready(function() {
            $('#crudTable').DataTable({
                "order": [
                    [0, "desc"]
                ]
            });
        });

        var urlParams = new URLSearchParams(window.location.search);
        var successParam = urlParams.get('success');

        console.log("Success Param:", successParam);

        if (successParam === 'true') {
            Swal.fire({
                title: 'Success!',
                text: 'The item has been deleted successfully.',
                type: 'success'
            }).then(() => {
                window.history.back();
            });
        }
    </script>
@endpush
