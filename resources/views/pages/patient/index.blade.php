@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Patients')

@section('content')
<x-module-table 
    :module="'patient'" 
    :moduleTitle="'Patient'" 
    :items="$patients"
    :columns="['#','ID','Patient Name','Address','Phone','Hospital']"
    :fields="['id','id','name','address','phone','hospital_name']"
    :indexRoute="route('patient.index')"
/>

@include('pages.patient.save-modals')
@endsection

@section('bottom-scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const module = 'patient';
  const modalEl = document.getElementById(`${module}Modal`);
  const modal = new bootstrap.Modal(modalEl);
  const form = document.getElementById(`${module}Form`);
  const saveBtn = document.getElementById(`savePatientBtn`);
  const modalTitle = document.getElementById(`${module}ModalLabel`);
  const alertContainer = document.getElementById('alertContainer');

  const showAlert = (message, type='success') => {
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
    form.querySelector('#patient_id').value = '';
    clearErrors();
    saveBtn.disabled = false;
    saveBtn.innerHTML = 'Save';
  };

  const httpRequest = async (url, method, body=null) => {
    const options = { method, headers: {'Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'} };
    if(body) options.body = body;
    const res = await fetch(url, options);
    return await res.json();
  };

  // Show alert from sessionStorage
  const successMessage = sessionStorage.getItem('patient_alert');
  if(successMessage){ showAlert(successMessage,'success'); sessionStorage.removeItem('patient_alert'); }

  modalEl.addEventListener('hidden.bs.modal', resetForm);

  // Open Create Modal
  document.querySelector('.open-create-modal').addEventListener('click', () => {
    modalTitle.textContent = 'Create New Patient';
    resetForm();
  });

  // Edit buttons
  document.querySelectorAll('.edit-patient-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      modalTitle.textContent = 'Edit Patient';
      form.querySelector('#patient_id').value = btn.dataset.id;
      form.querySelector('#name').value = btn.dataset.name;
      form.querySelector('#address').value = btn.dataset.address;
      form.querySelector('#phone').value = btn.dataset.phone;

      // Set hospital_id
      const hospitalSelect = form.querySelector('#hospital_id');
      if(btn.dataset.hospitalId){
        hospitalSelect.value = btn.dataset.hospitalId;
      } else {
        hospitalSelect.value = '';
      }

      modal.show();
    });
  });

  // Save / Update
  saveBtn.addEventListener('click', async ()=>{
    clearErrors();
    saveBtn.disabled = true;
    saveBtn.innerHTML = 'Saving...';

    const id = form.querySelector('#patient_id').value;
    const url = id ? `{{ url('patient') }}/${id}` : `{{ route('patient.store') }}`;
    const formData = new FormData(form);
    if(id) formData.append('_method','PUT');

    try{
      const data = await httpRequest(url,'POST',formData);
      if(data.success){
        sessionStorage.setItem('patient_alert', id?'Patient updated successfully!':'Patient created successfully!');
        location.reload();
      } else if(data.errors){
        Object.entries(data.errors).forEach(([field,messages])=>{
          const input = form.querySelector(`[name="${field}"]`);
          const errorEl = document.getElementById(`error-${field}`);
          if(input) input.classList.add('is-invalid');
          if(errorEl) errorEl.textContent = messages[0];
        });
      }
    } catch(err){
      console.error(err);
      showAlert('Failed to save data.','danger');
    } finally {
      saveBtn.disabled = false;
      saveBtn.innerHTML = 'Save';
    }
  });

  // Delete
  document.addEventListener('click', async e=>{
    const deleteBtn = e.target.closest('.delete-patient-btn');
    if(!deleteBtn) return;
    const id = deleteBtn.dataset.id;
    const name = deleteBtn.dataset.name;
    if(!confirm(`Are you sure you want to delete patient "${name}"?`)) return;
    try{
      const data = await httpRequest(`patient) }}/${id}`,'DELETE');
      if(data.success){
        sessionStorage.setItem('patient_alert','Patient deleted successfully!');
        location.reload();
      } else showAlert(data.message||'Failed to delete patient!','danger');
    } catch(err){
      console.error(err);
      showAlert('An unexpected error occurred.','danger');
    }
  });

});
</script>
@endsection