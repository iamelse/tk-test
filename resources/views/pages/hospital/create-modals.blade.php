<div class="modal fade" id="hospitalModal" tabindex="-1" aria-labelledby="hospitalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="hospitalModalLabel">Create New Hospital</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="hospitalForm">
          @csrf
          <input type="hidden" name="id" id="hospital_id">

          <div class="mb-3">
            <label for="name" class="form-label">Hospital Name</label>
            <input type="text" name="name" id="name" class="form-control">
            <div class="invalid-feedback" id="error-name"></div>
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" id="address" class="form-control">
            <div class="invalid-feedback" id="error-address"></div>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control">
            <div class="invalid-feedback" id="error-email"></div>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control">
            <div class="invalid-feedback" id="error-phone"></div>
          </div>

          <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="form-control">
            <div class="invalid-feedback" id="error-capacity"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveHospitalBtn" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>