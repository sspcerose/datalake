@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <div class="bg-primary d-flex justify-content-between align-items-center mb-4 p-3">
    <h6 class="mb-0 text-white" style="font-size: 0.9rem;">{{ ucfirst($table) }} Record Details</h6>
    <a href="{{ route('table.viewer', $table) }}" 
       class="btn border bg-white text-primary view-btn" 
       style="border-color: #ddd; transition: background-color 0.3s;">
      <i class="bx bx-arrow-back"></i> Back to Table
    </a>
  </div>
  <div class="card-body">
    <div class="container">
      <div class="row gy-3">
        @foreach ($record as $key => $value)
          @if (!in_array($key, ['id', 'created_at', 'updated_at']))
            <div class="col-md-4">
              <div class="border p-2 rounded bg-light">
                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                <span class="d-block">{{ $value }}</span>
              </div>
            </div>
          @endif
        @endforeach
      </div>
    </div>
  </div>
</div>

<!-- Inline CSS for hover effect -->
<style>
  .view-btn:hover {
    background-color: red !important;
    color: white !important;
    border-color: red !important;
  }
</style>
@endsection
