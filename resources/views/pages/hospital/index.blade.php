@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Hospitals')

@section('vendor-style')
  @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
  @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
  @vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
<div class="row">
  <div class="col-xxl-12 mb-6 order-0">
    <div class="card">
      <!-- Card Header with Search and Add Button -->
      <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
          <a href="{{ route('hospital.create') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i> New Hospital
          </a>
        </div>
        <form method="GET" action="{{ route('hospital.index') }}" class="d-flex w-md-auto">
          <div class="input-group">
            <input 
              type="text" 
              name="search" 
              value="{{ request('search') }}" 
              class="form-control" 
              placeholder="Search hospitals..."
            >
            <button class="btn btn-primary" type="submit">
              <i class="bx bx-search"></i>
            </button>
          </div>
        </form>
      </div>

      <!-- Table -->
      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>ID</th>
              <th>Hospital Name</th>
              <th>Address</th>
              <th>Email</th>
              <th>Phone</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($hospitals as $hospital)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $hospital->id }}</td>
                <td>{{ $hospital->name }}</td>
                <td>{{ $hospital->address }}</td>
                <td>{{ $hospital->email }}</td>
                <td>{{ $hospital->phone }}</td>
                <td class="text-center">
                  <div class="dropdown">
                    <button 
                      type="button" 
                      class="btn btn-sm btn-icon dropdown-toggle hide-arrow" 
                      data-bs-toggle="dropdown"
                    >
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item disabled" href="javascript:void(0);">
                        <i class="bx bx-edit-alt me-1"></i>Edit (coming soon)
                      </a>
                      <a class="dropdown-item disabled" href="javascript:void(0);">
                        <i class="bx bx-trash me-1"></i>Delete (coming soon)
                      </a>
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  No hospitals found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="small text-muted">
          Showing 
          <strong>{{ $hospitals->firstItem() }}</strong> 
          to 
          <strong>{{ $hospitals->lastItem() }}</strong> 
          of 
          <strong>{{ $hospitals->total() }}</strong> 
          hospitals
        </div>
        <div>
          {{ $hospitals->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
