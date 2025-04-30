@extends('layouts/contentNavbarLayout')

<!-- @section('title', 'Dashboard - Analytics') -->
@section('content')
@section('title', 'Tables - Basic Tables')

<!-- Basic Bootstrap Table -->
<div class="card" style="background-color:">
  <div class="card-header">
    <h5>Dashboard</h5>
    <select>
      <option value="" disabled>Select option</option>
      <option value="">option 1</option>
      <option value="">option 1</option>
      <option value="">option 1</option>
</select>
</div> 
  <div class="row mb-12 g-6 mx-2">
   <div class="col-md-6 col-lg-3">
  <div class="card text-center p-3" style="background-color:rgb(190, 208, 226);">
    <div class="card-body">
      <h5 class="card-title mb-3">April 30, 2025</h5>
      <img src="{{ asset('assets/img/favicon/data-lake-logo.png') }}" alt="Cloudy" style="width: 100px; height: auto;">
      <h6 class="mt-3">Cloudy</h6>
      <p class="mt-3 mb-1"><strong>Rainfall mm:</strong> &lt;60%</p>
      <p class="mb-1"><strong>Rainfall Desc:</strong> Light Rains</p>
      <p class="mb-1"><strong>Ave Min:</strong> 29.23</p>
      <p><strong>Wind mps:</strong> 29.23</p>
    </div>
  </div>
</div>
 
  
</div>
<hr>


@endsection
