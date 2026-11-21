
@extends('layouts.vendor')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/vendor/manage_foods.css') }}">
@endpush

@section('content')

<div class="container-fluid vendor-manage-foods">

    <!-- Main Page Card -->
    <div class="foods-main-card">

        <!-- Page Header -->
        <div class="foods-page-header">
            <h2 class="page-title">Manage Food Menu</h2>

            <p class="page-description">
                Register your food items with images and prices before submitting your daily invoices.
            </p>
        </div>

        <!-- Page Content -->
        <div class="foods-page-content">

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add New Food Section -->
            <div class="foods-section">

                <div class="foods-section-header">
                    <h5 class="foods-section-title">Add New Food</h5>
                    <p class="foods-section-description">
                        Enter the food details below to register a new menu item.
                    </p>
                </div>

                <form action="{{ route('vendor.foods.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="row g-3 align-items-end">

                        <!-- Food Name -->
                        <div class="col-lg-4 col-md-6">
                            <label for="foodName" class="form-label">
                                Food Name
                            </label>

                            <input type="text"
                                   id="foodName"
                                   name="name"
                                   class="form-control foods-input"
                                   placeholder="Enter food name"
                                   required>
                        </div>

                        <!-- Base Price -->
                        <div class="col-lg-2 col-md-6">
                            <label for="foodPrice" class="form-label">
                                Base Price (RM)
                            </label>

                            <input type="number"
                                   id="foodPrice"
                                   name="price"
                                   step="0.01"
                                   class="form-control foods-input"
                                   placeholder="0.00"
                                   required>
                        </div>

                        <!-- Food Image -->
                        <div class="col-lg-4 col-md-8">
                            <label for="foodImage" class="form-label">
                                Food Image
                            </label>

                            <input type="file"
                                   id="foodImage"
                                   name="image"
                                   class="form-control foods-input"
                                   required>
                        </div>

                        <!-- Add Button -->
                        <div class="col-lg-2 col-md-4">
                            <button type="submit"
                                    class="foods-btn btn-add-food">
                                <i class="bi bi-plus-lg"></i>
                                Add Food
                            </button>
                        </div>

                    </div>

                </form>

            </div>

            <!-- Section Divider -->
            <div class="foods-divider"></div>

            <!-- Registered Foods Section -->
            <div class="foods-section">

                <div class="foods-section-header">
                    <h5 class="foods-section-title">
                        Registered Foods
                    </h5>

                    <p class="foods-section-description">
                        View and manage the food items you have registered.
                    </p>
                </div>

                <!-- Foods Table -->
                <div class="table-responsive foods-table-wrapper">

                    <table class="table foods-table">

                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Food Name</th>
                                <th>Price (RM)</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($foods as $food)

                                <tr>

                                    <!-- Food Image -->
                                    <td>
                                        <img src="{{ asset($food->image_path) }}"
                                             alt="{{ $food->name }}"
                                             class="food-thumbnail">
                                    </td>

                                    <!-- Food Name -->
                                    <td class="food-name">
                                        {{ $food->name }}
                                    </td>

                                    <!-- Food Price -->
                                    <td class="food-price">
                                        RM {{ number_format($food->price, 2) }}
                                    </td>

                                    <!-- Delete Action -->
                                    <td>

                                        <form action="{{ route('vendor.foods.destroy', $food->id) }}"
                                              method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="foods-btn btn-delete-food"
                                                    onclick="return confirm('Are you sure you want to delete this food item?')">

                                                <i class="bi bi-trash"></i>
                                                Delete

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="foods-empty-state">
                                        No food items have been registered yet.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection