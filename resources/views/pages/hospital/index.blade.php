@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Hospitals')

@section('content')
<div class="row">
  <div class="col-xxl-12 mb-6 order-0">
    <div id="alertContainer"></div>
    <div class="card">
      <!-- Card Header -->
      <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
          <a href="javascript:void(0);" 
             class="btn btn-primary btn-sm open-create-modal" 
             data-bs-toggle="modal" 
             data-bs-target="#hospitalModal">
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
              placeholder="Search hospitals...">
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
                    <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a href="javascript:void(0);" 
                         class="dropdown-item edit-hospital-btn"
                         data-id="{{ $hospital->id }}"
                         data-name="{{ $hospital->name }}"
                         data-address="{{ $hospital->address }}"
                         data-email="{{ $hospital->email }}"
                         data-phone="{{ $hospital->phone }}"
                         data-capacity="{{ $hospital->capacity }}">
                        <i class="bx bx-edit-alt me-1"></i>Edit
                      </a>
                      <a href="javascript:void(0);" 
                         class="dropdown-item text-danger delete-hospital-btn"
                         data-id="{{ $hospital->id }}"
                         data-name="{{ $hospital->name }}">
                        <i class="bx bx-trash me-1"></i>Delete
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

@include('pages.hospital.create-modals')
@endsection

@section('bottom-scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modalEl = document.getElementById('hospitalModal');
  const modal = new bootstrap.Modal(modalEl);
  const form = document.getElementById('hospitalForm');
  const saveBtn = document.getElementById('saveHospitalBtn');
  const modalTitle = document.getElementById('hospitalModalLabel');
  const alertContainer = document.getElementById('alertContainer');

  // ===== Helpers =====
  const showAlert = (message, type = 'success') => {
    alertContainer.innerHTML = `
      <div class="alert alert-${type} alert-dismissible fade show" role="alert">
        <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    `;
  };

  const clearErrors = () => {
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
  };

  const resetForm = () => {
    form.reset();
    form.querySelector('#hospital_id').value = '';
    clearErrors();
    saveBtn.disabled = false;
    saveBtn.innerHTML = 'Save';
  };

  const httpRequest = async (url, method, body = null) => {
    const options = {
      method,
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
      },
    };
    if (body) options.body = body;
    const res = await fetch(url, options);
    return await res.json();
  };

  // ===== Show Alert After Reload =====
  const successMessage = sessionStorage.getItem('hospital_alert');
  if (successMessage) {
    showAlert(successMessage, 'success');
    sessionStorage.removeItem('hospital_alert');
  }

  // ===== Reset Modal on Close =====
  modalEl.addEventListener('hidden.bs.modal', resetForm);

  // ===== Open Create Modal =====
  document.querySelector('.open-create-modal').addEventListener('click', () => {
    modalTitle.textContent = 'Create New Hospital';
    resetForm();
  });

  // ===== Edit Hospital =====
  document.querySelectorAll('.edit-hospital-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      modalTitle.textContent = 'Edit Hospital';
      form.querySelector('#hospital_id').value = btn.dataset.id;
      form.querySelector('#name').value = btn.dataset.name;
      form.querySelector('#address').value = btn.dataset.address;
      form.querySelector('#email').value = btn.dataset.email;
      form.querySelector('#phone').value = btn.dataset.phone;
      form.querySelector('#capacity').value = btn.dataset.capacity;
      modal.show();
    });
  });

  // ===== Save / Update Hospital =====
  saveBtn.addEventListener('click', async () => {
    clearErrors();
    saveBtn.disabled = true;
    saveBtn.innerHTML = 'Saving...';

    const id = form.querySelector('#hospital_id').value;
    const url = id ? `{{ url('hospital') }}/${id}` : `{{ route('hospital.store') }}`;
    const formData = new FormData(form);
    if (id) formData.append('_method', 'PUT');

    try {
      const data = await httpRequest(url, 'POST', formData);

      if (data.success) {
        sessionStorage.setItem('hospital_alert', id 
          ? 'Hospital updated successfully!' 
          : 'Hospital created successfully!'
        );
        location.reload();
      } else if (data.errors) {
        Object.entries(data.errors).forEach(([field, messages]) => {
          const input = form.querySelector(`[name="${field}"]`);
          const errorEl = document.getElementById(`error-${field}`);
          if (input) input.classList.add('is-invalid');
          if (errorEl) errorEl.textContent = messages[0];
        });
      }
    } catch (err) {
      console.error('Error saving hospital:', err);
      showAlert('Failed to save data. Please try again.', 'danger');
    } finally {
      saveBtn.disabled = false;
      saveBtn.innerHTML = 'Save';
    }
  });

  // ===== Delete Hospital =====
  document.addEventListener('click', async e => {
    const deleteBtn = e.target.closest('.delete-hospital-btn');
    if (!deleteBtn) return;

    const id = deleteBtn.dataset.id;
    const name = deleteBtn.dataset.name;

    if (!confirm(`Are you sure you want to delete hospital "${name}"?`)) return;

    try {
      const data = await httpRequest(`{{ url('hospital') }}/${id}`, 'DELETE');
      if (data.success) {
        sessionStorage.setItem('hospital_alert', 'Hospital deleted successfully!');
        location.reload();
      } else {
        showAlert(data.message || 'Failed to delete hospital!', 'danger');
      }
    } catch (err) {
      console.error('Error deleting hospital:', err);
      showAlert('An unexpected error occurred.', 'danger');
    }
  });
});
</script>
@endsection
