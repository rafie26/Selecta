@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - Selecta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            color: #333;
            margin: 0;
        }

        .search-hero {
            padding: 6rem 1.5rem 2rem;
            background: linear-gradient(135deg, #26265A, #3b82f6);
            color: #fff;
        }

        .search-container-page {
            max-width: 1100px;
            margin: 0 auto;
        }

        .search-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .search-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .search-form-page {
            margin-top: 1.5rem;
        }

        .search-box-page {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 0.4rem 0.4rem 0.4rem 1rem;
            border: 1px solid rgba(255,255,255,0.25);
            backdrop-filter: blur(10px);
        }

        .search-box-page i {
            margin-right: 0.6rem;
            color: rgba(255,255,255,0.85);
        }

        .search-input-page {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            color: #fff;
            font-size: 0.95rem;
        }

        .search-input-page::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .search-button-page {
            border: none;
            background: #fff;
            color: #26265A;
            border-radius: 999px;
            padding: 0.5rem 1.4rem;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .results-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1.5rem 3rem;
        }

        .section-heading {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 1.5rem 0 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-heading i {
            color: #26265A;
        }

        .result-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
        }

        .result-card {
            background: #fff;
            border-radius: 12px;
            padding: 1rem 1.1rem;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
        }

        .result-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: #111827;
        }

        .result-meta {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .result-desc {
            font-size: 0.85rem;
            color: #4b5563;
            line-height: 1.4;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #eff6ff;
            color: #1d4ed8;
            margin-right: 0.4rem;
        }

        .price-badge {
            background: #ecfdf5;
            color: #047857;
        }

        .muted {
            color: #9ca3af;
            font-size: 0.85rem;
        }

        @media (max-width: 640px) {
            .search-hero {
                padding-top: 5rem;
            }

            .search-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
@include('components.navbar')

<section class="search-hero">
    <div class="search-container-page">
        <h1 class="search-title">Hasil Pencarian</h1>
        <p class="search-subtitle">
            @if(!empty($keyword))
                Menampilkan hasil untuk kata kunci "<strong>{{ $keyword }}</strong>".
            @else
                Ketik kata kunci untuk mencari tiket, hotel, restoran, galeri, atau wahana di Selecta.
            @endif
        </p>

        <form class="search-form-page" action="{{ route('search.index') }}" method="GET">
            <div class="search-box-page">
                <i class="fas fa-search"></i>
                <input
                    type="text"
                    name="q"
                    class="search-input-page"
                    placeholder="Cari tiket, hotel, restoran, galeri, atau wahana..."
                    value="{{ $keyword }}"
                >
                <button type="submit" class="search-button-page">Cari</button>
            </div>
        </form>
    </div>
</section>

<div class="results-wrapper">
    @php
        $hasResults =
            ($results['hotels'] ?? collect())->isNotEmpty() ||
            ($results['tickets'] ?? collect())->isNotEmpty() ||
            ($results['restaurants'] ?? collect())->isNotEmpty() ||
            ($results['galleries'] ?? collect())->isNotEmpty() ||
            ($results['attractions'] ?? collect())->isNotEmpty();
    @endphp

    @if(!empty($keyword) && !$hasResults)
        <p class="muted">Tidak ada hasil yang cocok dengan kata kunci tersebut.</p>
    @endif

    @if(($results['hotels'] ?? collect())->isNotEmpty())
        <h2 class="section-heading"><i class="fas fa-hotel"></i> Hotel & Penginapan</h2>
        <div class="result-grid">
            @foreach($results['hotels'] as $item)
                <div class="result-card">
                    <div class="result-title">{{ $item->title }}</div>
                    <div class="result-meta">
                        <span class="badge">Hotel</span>
                        @if(isset($item->price_per_night))
                            <span class="badge price-badge">Mulai Rp {{ number_format($item->price_per_night, 0, ',', '.') }}/malam</span>
                        @endif
                    </div>
                    @if(!empty($item->description))
                        <div class="result-desc">{{ Str::limit($item->description, 120) }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @if(($results['tickets'] ?? collect())->isNotEmpty())
        <h2 class="section-heading"><i class="fas fa-ticket-alt"></i> Tiket & Paket Wisata</h2>
        <div class="result-grid">
            @foreach($results['tickets'] as $item)
                <div class="result-card">
                    <div class="result-title">{{ $item->title }}</div>
                    <div class="result-meta">
                        <span class="badge">Tiket</span>
                        @if(isset($item->price))
                            <span class="badge price-badge">Mulai Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    @if(!empty($item->description))
                        <div class="result-desc">{{ Str::limit($item->description, 120) }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @if(($results['restaurants'] ?? collect())->isNotEmpty())
        <h2 class="section-heading"><i class="fas fa-utensils"></i> Restoran & Kuliner</h2>
        <div class="result-grid">
            @foreach($results['restaurants'] as $item)
                <div class="result-card">
                    <div class="result-title">{{ $item->title }}</div>
                    <div class="result-meta">
                        <span class="badge">Restoran</span>
                        @if(!empty($item->cuisine_type))
                            <span class="badge">{{ $item->cuisine_type }}</span>
                        @endif
                    </div>
                    @if(!empty($item->description))
                        <div class="result-desc">{{ Str::limit($item->description, 120) }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @if(($results['attractions'] ?? collect())->isNotEmpty())
        <h2 class="section-heading"><i class="fas fa-mountain-sun"></i> Wahana & Atraksi</h2>
        <div class="result-grid">
            @foreach($results['attractions'] as $item)
                <div class="result-card">
                    <div class="result-title">{{ $item->title }}</div>
                    <div class="result-meta">
                        <span class="badge">Wahana</span>
                        @if(!empty($item->location))
                            <span class="muted"><i class="fas fa-map-marker-alt"></i> {{ $item->location }}</span>
                        @endif
                    </div>
                    @if(!empty($item->description))
                        <div class="result-desc">{{ Str::limit($item->description, 120) }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @if(($results['galleries'] ?? collect())->isNotEmpty())
        <h2 class="section-heading"><i class="fas fa-images"></i> Galeri Foto</h2>
        <div class="result-grid">
            @foreach($results['galleries'] as $item)
                <div class="result-card">
                    <div class="result-title">{{ $item->title }}</div>
                    <div class="result-meta">
                        <span class="badge">Galeri</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

</body>
</html>
