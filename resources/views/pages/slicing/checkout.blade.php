@section('title', 'Checkout')

<x-app-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Konfirmasi Pembayaran')
        </div>

        <div class="row g-3">
            <div class="col-lg-8 d-grid gap-3">
                <div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-grid">
                                    <p class="text-secondary">Alamat Pengiriman</p>
                                    <h6>Alamat</h6>
                                </div>

                                <button class="btn text-primary border-0 p-0">Ubah</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div for="selected_vendor" class="d-flex gap-2">
                            <x-checkbox id="selected_vendor" name="selected_vendor" />
                            <label class="form-check-label" for="selected_vendor">
                                <strong>Nama Vendor</strong>
                            </label>
                        </div>
                    </div>
                    <div class="card-body d-grid gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span
                                    class="badge rounded-pill text-secondary-emphasis bg-secondary-subtle border-secondary-subtle border">{{ date('l, j F Y') }}</span>
                            </div>
                            <div>
                                <x-secondary-button data-bs-toggle="collapse" data-bs-target="#collapseCatatanPesanan"
                                    aria-expanded="false"
                                    aria-controls="collapseCatatanPesanan">Catatan</x-secondary-button>
                            </div>
                        </div>
                        <div class="collapse" id="collapseCatatanPesanan">
                            <x-label for="catatan" value="{{ __('Catatan Pesanan') }}" />
                            <x-input id="catatan" type="catatan" name="catatan" :value="old('catatan')" />
                        </div>

                        <div>
                            <div class="d-grid d-md-flex justify-content-between align-items-center">
                                <div class="d-grid d-md-flex align-items-center gap-2">
                                    <x-checkbox id="selected_vendor" name="selected_vendor" />
                                    <div class="d-grid d-md-flex gap-3">
                                        <img src="https://laravel.com/img/logotype.min.svg" alt=""
                                            class="w-25 rounded-1">
                                        <div class="d-grid gap-2">
                                            <h3>Nama Menu</h3>
                                            <small class="text-secondary">Deskripsi Menu</small>
                                            <h5>Harga/pcs</h5>
                                            <div class="d-flex align-items-center gap-2">
                                                <span>Porsi</span>
                                                <div>
                                                    <button
                                                        class="btn btn-outline-secondary rounded-pill mx-1 px-3">Small</button>
                                                    <button
                                                        class="btn btn-outline-secondary rounded-pill mx-1 px-3">Medium</button>
                                                    <button
                                                        class="btn btn-outline-secondary rounded-pill mx-1 px-3">Large</button>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span>Kuantitas</span>
                                                <div class="d-flex align-items-center border-secondary rounded border">
                                                    <button class="btn border-0">
                                                        <i class="bi bi-dash-lg text-primary"></i>
                                                    </button>
                                                    <span class="mx-2">0</span>
                                                    <button class="btn border-0">
                                                        <i class="bi bi-plus-lg text-primary"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="text-secondary">Kredit</span>
                            </div>
                            <div class="col">
                                <span class="badge rounded-pill nominal_background">
                                    <h6 class="mb-0 px-2 py-1">Nominal</h6>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <h3>Ringkasan Belanja</h3>
                            <ul class="list-unstyled">
                                <li class="row justify-content-between gap-3">
                                    <div class="col">
                                        <span>Total Harga (Total Produk): </span>
                                        <span class="text-break">Nominal</span>
                                    </div>
                                </li>
                                <li class="row justify-content-between gap-3">
                                    <div class="col">
                                        <span>Total Ongkos Kirim: </span>
                                        <span class="text-break">Nominal</span>
                                    </div>
                                </li>
                            </ul>

                            <hr class="my-0">

                            <div class="row align-items-center gap-3">
                                <div class="col">
                                    <span class="text-secondary">Total Pembayaran: </span>
                                    <span class="fs-5 text-break"><strong>Nominal</strong></span>
                                </div>
                            </div>

                            <x-button>Pay</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>