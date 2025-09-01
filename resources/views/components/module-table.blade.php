<div id="alertContainer"></div>
<div class="card">

  <!-- Card Header -->
  <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
      
      <!-- Left: New Button -->
      <div>
          <a href="javascript:void(0);" 
            class="btn btn-primary btn-sm open-create-modal" 
            data-bs-toggle="modal" 
            data-bs-target="#{{ $module }}Modal">
              <i class="bx bx-plus me-1"></i> New {{ $moduleTitle }}
          </a>
      </div>

      <!-- Right: Search + Select Hospital -->
      <div class="d-flex flex-wrap gap-2">
          @if(!empty($showHospitalFilter) && !empty($hospitals))
          <form method="GET" action="{{ $indexRoute }}">
              <div class="input-group">
                  <select name="hospital_id" class="form-select" onchange="this.form.submit()">
                      <option value="">-- All Hospitals --</option>
                      @foreach($hospitals as $hospital)
                          <option value="{{ $hospital->id }}" {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>
                              {{ $hospital->name }}
                          </option>
                      @endforeach
                  </select>
              </div>
          </form>
          @endif

          <!-- Search Form -->
          <form method="GET" action="{{ $indexRoute }}" class="d-flex">
              <div class="input-group">
                  <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search {{ strtolower($moduleTitle) }}...">
                  <button class="btn btn-primary" type="submit">
                      <i class="bx bx-search"></i>
                  </button>
              </div>
          </form>
      </div>

  </div>

  <!-- Table -->
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          @foreach($columns as $col)
            <th>{{ $col }}</th>
          @endforeach
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
          <tr>
            @foreach($fields as $field)
              <td>{{ $item[$field] ?? '' }}</td>
            @endforeach
            <td class="text-center">
              <div class="dropdown">
                <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="javascript:void(0);" 
                    class="dropdown-item edit-{{ $module }}-btn"
                    @foreach($fields as $field)
                      data-{{ $field }}="{{ $item[$field] ?? '' }}"
                    @endforeach
                    data-hospital-id="{{ $item->hospital_id }}">
                      <i class="bx bx-edit-alt me-1"></i>Edit
                  </a>
                  <a href="javascript:void(0);" 
                     class="dropdown-item text-danger delete-{{ $module }}-btn"
                     data-id="{{ $item->id }}"
                     data-name="{{ $item->name ?? '' }}">
                    <i class="bx bx-trash me-1"></i>Delete
                  </a>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="{{ count($columns) + 1 }}" class="text-center text-muted py-4">
              No {{ strtolower($moduleTitle) }} found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
    <div class="small text-muted">
      Showing <strong>{{ $items->firstItem() }}</strong> to <strong>{{ $items->lastItem() }}</strong> of <strong>{{ $items->total() }}</strong> {{ strtolower($moduleTitle) }}
    </div>
    <div>
      {{ $items->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>