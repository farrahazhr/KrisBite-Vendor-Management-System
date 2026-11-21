
@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/manage_workers.css') }}">
@endpush

@section('content')

<div class="container-fluid manage-workers-page">

    <!-- Main Worker Approvals Card -->
    <div class="workers-main-card">

        <!-- Card Header -->
        <div class="workers-page-header">
            <h2 class="page-title">Worker Approvals</h2>

            <p class="page-description">
                Review and approve new worker registration applications.
            </p>
        </div>

        <!-- Card Content -->
        <div class="workers-card-content">

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Worker Applications Section -->
            <div class="workers-section-header">
                <h5 class="workers-section-title">
                    Pending Worker Applications
                </h5>
            </div>

            <!-- Worker Applications Table -->
            <div class="table-responsive">
                <table class="table workers-table">

                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($pendingWorkers->count() > 0)

                            @foreach($pendingWorkers as $worker)
                                <tr>

                                    <!-- Worker Name -->
                                    <td class="worker-name">
                                        {{ $worker->name }}
                                    </td>

                                    <!-- Worker Email -->
                                    <td class="worker-email">
                                        {{ $worker->email }}
                                    </td>

                                    <!-- Registration Date -->
                                    <td>
                                        {{ $worker->created_at->format('d M Y') }}
                                    </td>

                                    <!-- Approval Action -->
                                    <td>
                                        <a href="{{ route('admin.workers.approve', $worker->id) }}"
                                           class="worker-btn btn-approve"
                                           onclick="return confirm('Are you sure you want to approve this worker account?')">
                                            Approve
                                        </a>
                                    </td>

                                </tr>
                            @endforeach

                        @else

                            <!-- Empty Table Message -->
                            <tr>
                                <td colspan="4" class="empty-state">
                                    No pending worker applications.
                                </td>
                            </tr>

                        @endif
                    </tbody>

                </table>
            </div>

        </div>

    </div>

</div>

@endsection