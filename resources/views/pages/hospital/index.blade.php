@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Hospitals')

@section('content')
<x-module-table 
    :module="'hospital'" 
    :moduleTitle="'Hospital'" 
    :items="$hospitals"
    :columns="['#','ID','Hospital Name','Address','Email','Phone']"
    :fields="['id','id','name','address','email','phone']"
    :indexRoute="route('hospital.index')"
/>

@include('pages.hospital.save-modals')
@endsection

@section('bottom-scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const module = 'hospital';
  const modalEl = document.getElementById(`${module}Modal`);
  const modal = new bootstrap.Modal(modalEl);
  const form = document.getElementById(`${module}Form`);
  const saveBtn = document.getElementById(`saveHospitalBtn`);
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
    form.querySelector('#hospital_id').value = '';
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
  const successMessage = sessionStorage.getItem('hospital_alert');
  if(successMessage){ showAlert(successMessage,'success'); sessionStorage.removeItem('hospital_alert'); }

  modalEl.addEventListener('hidden.bs.modal', resetForm);

  // Open Create Modal
  document.querySelector('.open-create-modal').addEventListener('click', () => {
    modalTitle.textContent = 'Create New Hospital';
    resetForm();
  });

  // Edit buttons
  document.querySelectorAll('.edit-hospital-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      modalTitle.textContent = 'Edit Hospital';
      form.querySelector('#hospital_id').value = btn.dataset.id;
      form.querySelector('#name').value = btn.dataset.name;
      form.querySelector('#address').value = btn.dataset.address;
      form.querySelector('#email').value = btn.dataset.email;
      form.querySelector('#phone').value = btn.dataset.phone;
      modal.show();
    });
  });

  // Save / Update
  saveBtn.addEventListener('click', async ()=>{
    clearErrors();
    saveBtn.disabled = true;
    saveBtn.innerHTML = 'Saving...';

    const id = form.querySelector('#hospital_id').value;
    const url = id ? `{{ url('hospital') }}/${id}` : `{{ route('hospital.store') }}`;
    const formData = new FormData(form);
    if(id) formData.append('_method','PUT');

    try{
      const data = await httpRequest(url,'POST',formData);
      if(data.success){
        sessionStorage.setItem('hospital_alert', id?'Hospital updated successfully!':'Hospital created successfully!');
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
    const deleteBtn = e.target.closest('.delete-hospital-btn');
    if(!deleteBtn) return;
    const id = deleteBtn.dataset.id;
    const name = deleteBtn.dataset.name;
    if(!confirm(`Are you sure you want to delete hospital "${name}"?`)) return;
    try{
      const data = await httpRequest(`{{ url('hospital') }}/${id}`,'DELETE');
      if(data.success){
        sessionStorage.setItem('hospital_alert','Hospital deleted successfully!');
        location.reload();
      } else showAlert(data.message||'Failed to delete hospital!','danger');
    } catch(err){
      console.error(err);
      showAlert('An unexpected error occurred.','danger');
    }
  });

});
</script>
@endsection