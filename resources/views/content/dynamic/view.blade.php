@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <!-- Card Header with improved styling -->
  <div class="card-header d-flex justify-content-between align-items-center px-8">
    <div>
      <h4 class="mb-0 text-primary fw-bold">{{ ucfirst($table) }} Details</h4>
      <p class="mb-0 text-muted small">Viewing individual record information</p>
    </div>
    <a href="{{ route('table.viewer', $table) }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-2"></i> Back to Table
    </a>
  </div>
  
  <!-- Card Body with improved layout -->
  <div class="card-body p-4">
    <div class="container-fluid">
      <div class="row g-4">
        @foreach ($record as $key => $value)
          @if (!in_array($key, ['id', 'created_at', 'updated_at']))
            <div class="col-md-6 col-lg-4">
              <div class="p-3 border rounded bg-white h-100 transition-all hover-shadow">
                <div class="d-flex align-items-center mb-2">
                  <i class="bx bx-info-circle text-primary me-2"></i>
                  <h6 class="mb-0 text-primary fw-semibold text-uppercase small">{{ str_replace('_', ' ', $key) }}</h6>
                </div>
                <div class="p-2 bg-light rounded">
                  <p class="mb-0 text-dark">
                    @if(is_bool($value))
                      <span class="badge bg-{{ $value ? 'success' : 'danger' }}">
                        {{ $value ? 'Yes' : 'No' }}
                      </span>
                    @elseif(is_array($value) || is_object($value))
                      <pre class="mb-0 small">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                    @else
                      {{ $value ?? 'N/A' }}
                    @endif
                  </p>
                </div>
              </div>
            </div>
          @endif
        @endforeach
      </div>
      
      <!-- Optional footer with metadata -->
     
    </div>
  </div>
</div>

<style>
  .hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
    transform: translateY(-2px);
    transition: all 0.2s ease;
  }
  .transition-all {
    transition: all 0.3s ease;
  }
</style>
@endsection