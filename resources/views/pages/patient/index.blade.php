@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Patients')

@section('content')
<x-module-table 
    :module="'patient'" 
    :moduleTitle="'Patient'" 
    :items="$patients"
    :columns="['#','Patient Name','Address','Phone','Hospital']"
    :fields="['name','address','phone','hospital_name']"
    :indexRoute="route('patient.index')"
    :showHospitalFilter="$showHospitalFilter"
    :hospitals="$hospitals"
/>

@include('pages.patient.save-modals')
@endsection

@section('bottom-scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>

<script>
$(function() {
  const module = 'patient';
  const $modal = $(`#${module}Modal`);
  const $form = $(`#${module}Form`);
  const $saveBtn = $('#savePatientBtn');
  const $modalTitle = $(`#${module}ModalLabel`);
  const $alertContainer = $('#alertContainer');
  const modalInstance = new bootstrap.Modal($modal[0]);

  const showAlert = (message, type='success') => {
    $alertContainer.html(`
      <div class="alert alert-${type} alert-dismissible fade show" role="alert">
        <strong>${type==='success'?'Success!':'Error!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    `);
  };

  const clearErrors = () => {
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').text('');
  };

  const resetForm = () => {
    $form[0].reset();
    $form.find('#patient_id').val('');
    clearErrors();
    $saveBtn.prop('disabled', false).text('Save');
  };

  const httpRequest = (url, method, data=null) => {
    return $.ajax({
      url: url,
      method: method,
      data: data,
      processData: false,
      contentType: false,
      headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'}
    });
  };

  // Show alert from sessionStorage
  const successMessage = sessionStorage.getItem('patient_alert');
  if(successMessage){ 
    showAlert(successMessage,'success'); 
    sessionStorage.removeItem('patient_alert'); 
  }

  $modal.on('hidden.bs.modal', resetForm);

  // Open Create Modal
  $('.open-create-modal').on('click', function() {
    $modalTitle.text('Create New Patient');
    resetForm();
  });

  // Edit buttons
  $('.edit-patient-btn').on('click', function() {
    const $btn = $(this);
    $modalTitle.text('Edit Patient');
    $form.find('#patient_id').val($btn.data('id'));
    $form.find('#name').val($btn.data('name'));
    $form.find('#address').val($btn.data('address'));
    $form.find('#phone').val($btn.data('phone'));
    $form.find('#hospital_id').val($btn.data('hospital-id') || '');
    modalInstance.show();
  });

  // Save / Update
  $saveBtn.on('click', function() {
    clearErrors();
    $saveBtn.prop('disabled', true).text('Saving...');
    
    const id = $form.find('#patient_id').val();
    const url = id ? `{{ url('patient') }}/${id}` : `{{ route('patient.store') }}`;
    const formData = new FormData($form[0]);
    if(id) formData.append('_method','PUT');

    httpRequest(url,'POST',formData)
      .done(function(data){
        if(data.success){
          sessionStorage.setItem('patient_alert', id ? 'Patient updated successfully!' : 'Patient created successfully!');
          location.reload();
        } else if(data.errors){
          $.each(data.errors, function(field, messages){
            const $input = $form.find(`[name="${field}"]`);
            const $errorEl = $(`#error-${field}`);
            $input.addClass('is-invalid');
            if($errorEl.length) $errorEl.text(messages[0]);
          });
        }
      })
      .fail(function(){
        showAlert('Failed to save data.','danger');
      })
      .always(function(){
        $saveBtn.prop('disabled', false).text('Save');
      });
  });

  // Delete
  $(document).on('click','.delete-patient-btn',function() {
    const $btn = $(this);
    const id = $btn.data('id');
    const name = $btn.data('name');
    if(!confirm(`Are you sure you want to delete patient "${name}"?`)) return;

    httpRequest(`{{ url('patient') }}/${id}`,'DELETE')
      .done(function(data){
        if(data.success){
          sessionStorage.setItem('patient_alert','Patient deleted successfully!');
          location.reload();
        } else showAlert(data.message||'Failed to delete patient!','danger');
      })
      .fail(function(){
        showAlert('An unexpected error occurred.','danger');
      });
  });
});
</script>
@endsection