@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                    <h6 class="card-title">Total User</h6>
                        <h2 class="mb-0">{{ number_format($totalUsers) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Kamar Tersedia</h6>
                        <h2 class="mb-0 text-success">{{ number_format($totalAvailableRooms) }}</h2>
                        <small class="text-muted">dari {{ number_format($totalRooms) }} total kamar</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-bed fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Kamar Terisi</h6>
                        <h2 class="mb-0 text-warning">{{ number_format($occupiedRooms) }}</h2>
                        <small class="text-muted">kamar sedang digunakan</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-door-closed fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Tickets</h6>
                        <h2 class="mb-0">{{ number_format($totalTickets) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-ticket-alt fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Attractions & Gallery Stats -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2"></i>
                    Top Wahana
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-2">Total Wahana</h6>
                            <h3 class="mb-0">{{ number_format($totalAttractions ?? 0) }}</h3>
                            <small class="text-muted">wahana terdaftar</small>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-2">Wahana Aktif</h6>
                            <h3 class="mb-0 text-success">{{ number_format($activeAttractions ?? 0) }}</h3>
                            <small class="text-muted">ditampilkan di website</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.top-attractions') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Kelola Wahana
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-images me-2"></i>
                    Galeri Foto
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-2">Total Foto</h6>
                            <h3 class="mb-0">{{ number_format($totalGalleryPhotos ?? 0) }}</h3>
                            <small class="text-muted">foto di galeri</small>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-2">Foto Aktif</h6>
                            <h3 class="mb-0 text-success">{{ number_format($activeGalleryPhotos ?? 0) }}</h3>
                            <small class="text-muted">ditampilkan di website</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.top-gallery') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Kelola Galeri
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access Menu -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Akses Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.users') }}" class="btn btn-primary w-100 py-3">
                            <div class="mb-2">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                            <div class="small">Kelola Users</div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.top-attractions') }}" class="btn btn-outline-primary w-100 py-3">
                            <div class="mb-2">
                                <i class="fas fa-star fa-lg"></i>
                            </div>
                            <div class="small">Top Wahana</div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.top-gallery') }}" class="btn btn-outline-primary w-100 py-3">
                            <div class="mb-2">
                                <i class="fas fa-images fa-lg"></i>
                            </div>
                            <div class="small">Galeri Foto</div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary w-100 py-3">
                            <div class="mb-2">
                                <i class="fas fa-external-link-alt fa-lg"></i>
                            </div>
                            <div class="small">Lihat Website</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- No chart scripts needed in clean design -->
@endpush
