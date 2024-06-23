@extends('layouts.app', ['linknasabah' => 'active'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Nasabah</div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addNasabahModal">
                        Tambah
                    </button>
                    @if (isset($nasabahs) && count($nasabahs) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No Anggota</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Kelamin</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nasabahs as $nasabah)
                            <tr>
                                <td>{{ $nasabah['no_anggota'] }}</td>
                                <td>{{ $nasabah['name'] }}</td>
                                <td>{{ $nasabah['email'] }}</td>
                                <td>{{ $nasabah['alamat'] }}</td>
                                <td>{{ $nasabah['kelamin'] }}</td>
                                <td>{{ isset($nasabah['status']) ? $nasabah['status'] : 'Aktif' }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editNasabahModal" data-id="{{ $nasabah['id'] }}" data-name="{{ $nasabah['name'] }}" data-email="{{ $nasabah['email'] }}" data-alamat="{{ $nasabah['alamat'] }}" data-kelamin="{{ $nasabah['kelamin'] }}" data-status="{{ $nasabah['status'] }}">Edit</button>
                                    <a href="#" class="btn btn-danger">Hapus</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addNasabahModal" tabindex="-1" aria-labelledby="addNasabahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNasabahModalLabel">Tambah Nasabah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('nasabah.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Form fields here -->
                    <div class="form-group">
                        <label for="name">Nama Nasabah</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kelamin">Jenis Kelamin</label><br>
                        <input type="radio" id="kelamin1" name="kelamin" value="Laki-Laki"> Laki-Laki<br>
                        <input type="radio" id="kelamin2" name="kelamin" value="Perempuan"> Perempuan
                    </div>
                    <div class="form-group">
                        <label for="agama">Agama</label>
                        <select class="form-control" id="agama" name="agama" required>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="identitas">Identitas</label>
                        <input type="text" class="form-control" id="identitas" name="identitas" required>
                    </div>
                    <div class="form-group">
                        <label for="no_anggota">No Anggota</label>
                        <input type="text" class="form-control" id="no_anggota" name="no_anggota" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editNasabahModal" tabindex="-1" aria-labelledby="editNasabahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNasabahModalLabel">Edit Nasabah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('nasabah.update', ['id' => '']) }}" method="POST" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">
                    <div class="form-group">
                        <label for="editName">Nama Nasabah</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editKelamin">Jenis Kelamin</label><br>
                        <input type="radio" id="editKelamin1" name="kelamin" value="Laki-Laki"> Laki-Laki<br>
                        <input type="radio" id="editKelamin2" name="kelamin" value="Perempuan"> Perempuan
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select class="form-control" id="editStatus" name="status" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editAgama">Agama</label>
                        <select class="form-control" id="editAgama" name="agama" required>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editIdentitas">Identitas</label>
                        <input type="text" class="form-control" id="editIdentitas" name="identitas" required>
                    </div>
                    <div class="form-group">
                        <label for="editNoAnggota">No Anggota</label>
                        <input type="text" class="form-control" id="editNoAnggota" name="no_anggota" required>
                    </div>
                    <div class="form-group">
                        <label for="editAlamat">Alamat</label>
                        <textarea class="form-control" id="editAlamat" name="alamat" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#editNasabahModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var name = button.data('name')
        var email = button.data('email')
        var alamat = button.data('alamat')
        var kelamin = button.data('kelamin')
        var status = button.data('status')

        var modal = $(this)
        modal.find('.modal-body #editId').val(id)
        modal.find('.modal-body #editName').val(name)
        modal.find('.modal-body #editEmail').val(email)
        modal.find('.modal-body #editAlamat').val(alamat)
        modal.find('.modal-body #editKelamin').val(kelamin)
        modal.find('.modal-body #editStatus').val(status)
    })

    $('#editForm').on('submit', function (event) {
        var id = $('#editId').val()
        var action = $(this).attr('action')
        action = action.replace('id', id)
        $(this).attr('action', action)
    })
</script>
@endpush
