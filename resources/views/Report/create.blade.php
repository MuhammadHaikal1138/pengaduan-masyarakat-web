@extends('layout.layout')

@section('content')
<div class="container py-5">
    <div class="row min-vh-100 align-items-center">
        <!-- Form Section -->
        <div class="col-md-6">
            <h1 class="mb-4 text-primary">Form Pengaduan Masyarakat</h1>
            <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
                @csrf

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Laporan*</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                </div>

                <!-- Tipe -->
                <div class="mb-3">
                    <label for="type" class="form-label">Tipe Laporan*</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="" disabled selected>Pilih tipe laporan</option>
                        <option value="KEJAHATAN" {{ old('type') == 'KEJAHATAN' ? 'selected' : '' }}>Kejahatan</option>
                        <option value="PEMBANGUNAN" {{ old('type') == 'PEMBANGUNAN' ? 'selected' : '' }}>Pembangunan</option>
                        <option value="SOSIAL" {{ old('type') == 'SOSIAL' ? 'selected' : '' }}>Sosial</option>
                    </select>
                </div>

                <!-- Lokasi -->
                <div class="mb-3">
                    <label for="province" class="form-label">Provinsi*</label>
                    <select class="form-select" id="province" required>
                        <option value="">Pilih Provinsi</option>
                    </select>
                    <input type="hidden" name="province" value="{{ old('province') }}">
                </div>

                <div class="mb-3">
                    <label for="regency" class="form-label">Kota/Kabupaten*</label>
                    <select class="form-select" id="regency" required>
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                    <input type="hidden" name="regency" value="{{ old('regency') }}">
                </div>

                <div class="mb-3">
                    <label for="subdistrict" class="form-label">Kecamatan*</label>
                    <select class="form-select" id="subdistrict" required>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    <input type="hidden" name="subdistrict" value="{{ old('subdistrict') }}">
                </div>

                <div class="mb-3">
                    <label for="village" class="form-label">Kelurahan*</label>
                    <select class="form-select" id="village" required>
                        <option value="">Pilih Kelurahan</option>
                    </select>
                    <input type="hidden" name="village" value="{{ old('village') }}">
                </div>

                <!-- Pernyataan -->
                <div class="form-check mb-3">
                    <input type="hidden" name="statement" value="0">
                    <input type="checkbox" class="form-check-input" id="statement" name="statement" value="1" {{ old('statement') ? 'checked' : '' }} required>
                    <label class="form-check-label" for="statement">Laporan yang disampaikan sesuai dengan kebenaran.</label>
                </div>

                <!-- Gambar -->
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Pendukung</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>

        <!-- Image Section -->
        <div class="col-md-6 d-none d-md-block">
            <img src="https://storage.googleapis.com/a1aa/image/DU0AFwOJtDrmJZAnoAlAtSOauavOQZ6VQ4wPIdag6BICpLeJA.jpg" class="img-fluid rounded shadow-sm" alt="Aerial view of a city with roads and buildings">
        </div>
    </div>
</div>
@endsection

@push('js')
    
<script>
    $(document).ready(function () {
        // Fetch provinces
        $.ajax({
            method: "GET",
            url: "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json",
            dataType: "json",
            success: function (data) {
                data.forEach(function (provinsi) {
                    $('#province').append(`<option value="${provinsi.id}" data-name="${provinsi.name}">${provinsi.name}</option>`);
                });
            }
        });

        function resetDropdowns(selectors) {
            selectors.forEach(selector => $(selector).html('<option value="">Pilih</option>'));
        }

        $('#province').change(function () {
            const selectedOption = $(this).find(':selected');
            const idProv = selectedOption.val();
            const nameProv = selectedOption.data('name');
            resetDropdowns(['#regency', '#subdistrict', '#village']);
            $('[name="province"]').val(nameProv);
            if (idProv) loadRegencies(idProv);
        });

        function loadRegencies(idProv) {
            $.ajax({
                method: "GET",
                url: `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${idProv}.json`,
                dataType: "json",
                success: function (data) {
                    data.forEach(function (kota) {
                        $('#regency').append(`<option value="${kota.id}" data-name="${kota.name}">${kota.name}</option>`);
                    });
                }
            });
        }

        $('#regency').change(function () {
            const selectedOption = $(this).find(':selected');
            const idKota = selectedOption.val();
            const nameKota = selectedOption.data('name');
            resetDropdowns(['#subdistrict', '#village']);
            $('[name="regency"]').val(nameKota);
            if (idKota) loadDistricts(idKota);
        });

        function loadDistricts(idKota) {
            $.ajax({
                method: "GET",
                url: `https://www.emsifa.com/api-wilayah-indonesia/api/districts/${idKota}.json`,
                dataType: "json",
                success: function (data) {
                    data.forEach(function (kecamatan) {
                        $('#subdistrict').append(`<option value="${kecamatan.id}" data-name="${kecamatan.name}">${kecamatan.name}</option>`);
                    });
                }
            });
        }

        $('#subdistrict').change(function () {
            const selectedOption = $(this).find(':selected');
            const idKec = selectedOption.val();
            const nameKec = selectedOption.data('name');
            resetDropdowns(['#village']);
            $('[name="subdistrict"]').val(nameKec);
            if (idKec) loadVillages(idKec);
        });

        function loadVillages(idKec) {
            $.ajax({
                method: "GET",
                url: `https://www.emsifa.com/api-wilayah-indonesia/api/villages/${idKec}.json`,
                dataType: "json",
                success: function (data) {
                    data.forEach(function (kelurahan) {
                        $('#village').append(`<option value="${kelurahan.id}" data-name="${kelurahan.name}">${kelurahan.name}</option>`);
                    });
                }
            });
        }

        $('#village').change(function () {
            const selectedOption = $(this).find(':selected');
            const nameKel = selectedOption.data('name');
            $('[name="village"]').val(nameKel);
        });
    });
</script>
@endpush
