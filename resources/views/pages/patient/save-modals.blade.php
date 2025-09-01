<div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="patientModalLabel">Create New Patient</h5>
      </div>
      <div class="modal-body">
        <form id="patientForm">
          @csrf
          <input type="hidden" name="id" id="patient_id">

          <div class="mb-3">
            <label for="name" class="form-label">Patient Name</label>
            <input type="text" name="name" id="name" class="form-control">
            <div class="invalid-feedback" id="error-name"></div>
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" id="address" class="form-control">
            <div class="invalid-feedback" id="error-address"></div>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control">
            <div class="invalid-feedback" id="error-phone"></div>
          </div>

          <div class="mb-3">
            <label for="hospital_id" class="form-label">Hospital</label>
            <select name="hospital_id" id="hospital_id" class="form-select">
              <option value="">-- Select Hospital --</option>
              @foreach($hospitals as $hospital)
                  <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback" id="error-hospital_id"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary me-1" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="savePatientBtn" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
