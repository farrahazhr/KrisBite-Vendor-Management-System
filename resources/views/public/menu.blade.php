
@extends('layouts.public')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/public/menu.css') }}">
@endpush

@section('content')

<div class="public-menu-page">

    <!-- =========================================
         HERO BANNER SECTION
    ========================================== -->

    <section class="hero-section public-hero-section">

        <div class="container public-hero-content">

            <h1 class="public-hero-title">
                Good Food Brings People Together
            </h1>

            <p class="public-hero-description">
                Tempah makanan kegemaran anda atau jadi vendor KrisBite hari ini!
            </p>

            <a href="{{ route('public.apply.vendor') }}"
               class="btn btn-lg public-apply-btn">
                Apply as Vendor &rarr;
            </a>

        </div>

    </section>

    <!-- =========================================
         SEARCH BAR SECTION
    ========================================== -->

    <div class="container public-search-section">

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="public-search-wrapper">

                    <div class="public-search-field">

                        <i class="bi bi-search public-search-icon"></i>

                        <input type="text"
                               class="form-control public-search-input"
                               placeholder="Cari makanan kegemaran anda..."
                               id="searchInput"
                               onkeyup="filterMenu()">

                    </div>

                    <button class="btn public-search-btn"
                            type="button"
                            onclick="filterMenu()">
                        <i class="bi bi-search"></i>
                        Cari
                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================================
         AVAILABLE MENU SECTION
    ========================================== -->

    <div class="container public-menu-section">

        <div class="public-menu-header">

            <h3 class="public-menu-title">
                Available Menu Today
            </h3>

        </div>

        <!-- MENU GRID -->
        <div class="row g-4" id="menuGrid">

            @if($menuItems->count() > 0)

                @foreach($menuItems as $item)

                <div class="col-md-6 col-lg-3 menu-item">

                    <div class="card public-food-card h-100">

                        <!-- FOOD IMAGE -->
                        @if($item->food && $item->food->image_path)

                            <img src="{{ asset($item->food->image_path) }}"
                                 class="card-img-top public-food-image"
                                 alt="{{ $item->food->name }}">

                        @else

                            <img src="https://placehold.co/300x180/e9ecef/6c757d?text=No+Image"
                                 class="card-img-top public-food-image"
                                 alt="No Image">

                        @endif

                        <!-- FOOD DETAILS -->
                        <div class="card-body public-food-body">

                            <h5 class="card-title public-food-name">
                                {{ $item->food->name ?? $item->name }}
                            </h5>

                            <p class="public-food-vendor">
                                By:
                                <b>
                                    {{ $item->invoice->vendor->vendorApplication->business_name ?? 'Unknown Vendor' }}
                                </b>
                            </p>

                            <div class="public-food-footer">

                                <span class="public-food-price">
                                    RM {{ number_format($item->price, 2) }}
                                </span>

                                @if($item->menu_status == 'available')

                                    <span class="badge bg-success public-food-status">
                                        Available
                                    </span>

                                @else

                                    <span class="badge bg-danger public-food-status">
                                        Sold Out
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

                @endforeach

            @else

                <div class="col-12">

                    <div class="public-empty-state">
                        <h5>
                            No menu items are available for sale today. Please check back tomorrow.
                        </h5>
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

<!-- ORIGINAL SEARCH JAVASCRIPT - UNCHANGED -->
<script>
function filterMenu() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let items = document.getElementsByClassName('menu-item');

    for (let i = 0; i < items.length; i++) {
        let title = items[i].getElementsByTagName('h5')[0].innerText.toLowerCase();
        if (title.includes(input)) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}
</script>

@endsection