
@extends('layouts.worker')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/worker/manage_menu.css') }}">
@endpush

@section('content')

<div class="w-100 worker-manage-menu">

    <!-- MAIN CARD -->
    <div class="menu-main-card">

        <!-- PAGE HEADER -->
        <div class="menu-page-header">
            <h2 class="menu-page-title">Manage Menu Today</h2>

            <p class="menu-page-description">
                Update food items to "Sold Out" when they are no longer available.
                Once marked as Sold Out, the status cannot be undone.
            </p>
        </div>

        <!-- PAGE CONTENT -->
        <div class="menu-page-content">

            <!-- ALERT MESSAGES -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- SEARCH SECTION -->
            <div class="menu-search-section">

                <div class="menu-section-header">
                    <h5 class="menu-section-title">Food Menu</h5>
                    <p class="menu-section-description">
                        Search and manage the availability of today's food items.
                    </p>
                </div>

                <div class="menu-search-wrapper">

                    <div class="menu-search-field">
                        <i class="bi bi-search menu-search-icon"></i>

                        <input type="text"
                               class="form-control menu-search-input"
                               placeholder="Search food names..."
                               id="searchInput"
                               onkeyup="filterMenu()">
                    </div>

                    <button class="btn menu-search-btn"
                            type="button"
                            onclick="filterMenu()">
                        <i class="bi bi-search"></i>
                        Search
                    </button>

                </div>

            </div>

            <!-- MENU GRID -->
            <div class="row g-4 menu-grid" id="menuGrid">

                @if($menuItems->count() > 0)

                    @foreach($menuItems as $item)

                        <div class="col-md-6 col-lg-3 menu-item">

                            <div class="card menu-food-card h-100">

                                <!-- FOOD IMAGE -->
                                @if($item->food && $item->food->image_path)

                                    <img src="{{ asset($item->food->image_path) }}"
                                         class="card-img-top menu-food-image"
                                         alt="{{ $item->food->name }}">

                                @else

                                    <img src="https://placehold.co/300x180/e9ecef/6c757d?text=No+Image"
                                         class="card-img-top menu-food-image"
                                         alt="No Image">

                                @endif

                                <!-- FOOD DETAILS -->
                                <div class="card-body menu-food-body">

                                    <h5 class="card-title menu-food-name">
                                        {{ $item->food->name ?? $item->name }}
                                    </h5>

                                    <p class="menu-food-vendor">
                                        By:
                                        <b>
                                            {{ $item->invoice->vendor->vendorApplication->business_name ?? 'Unknown Vendor' }}
                                        </b>
                                    </p>

                                    <!-- PRICE AND STATUS -->
                                    <div class="menu-food-footer">

                                        <span class="menu-food-price">
                                            RM {{ number_format($item->price, 2) }}
                                        </span>

                                        @if($item->menu_status == 'available')

                                            <!-- LINK UNTUK SET SOLD OUT -->
                                            <a href="{{ route('worker.menu.toggle', $item->id) }}"
                                               class="btn btn-sm menu-available-btn"
                                               onclick="return confirm('Are you sure this food item is sold out?')">
                                                Available
                                            </a>

                                        @else

                                            <!-- BADGE STATIK (TAK BOLEH TEKAN) -->
                                            <span class="badge menu-soldout-badge">
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
                        <div class="menu-empty-state">
                            <h5>There are no active menu items to update today.</h5>
                        </div>
                    </div>

                @endif

            </div>

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