@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container d-flex justify-content-center">
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Edit Record in {{ ucfirst($table) }}</h5>
        <small class="text-muted float-end">
          <a href="javascript:history.back()">Back</a>
        </small>
      </div>
      <div class="card-body">
        <form action="{{ route('table.update', [$table, $record->id]) }}" method="POST" id="dynamicEditForm">
          @csrf
          @method('PUT')

          <div class="row mb-3">
            @php $visibleIndex = 0; @endphp
            @foreach($columnDetails as $column)
              @php
                $name = $column['name'];
                $type = $column['type'];

                if (in_array($name, ['id', 'created_at', 'updated_at'])) continue;

                // Default values
                $inputType = 'text';
                $step = '';

                if (in_array($type, ['integer', 'bigint'])) {
                    $inputType = 'number';
                } elseif (in_array($type, ['float', 'double', 'decimal', 'double precision'])) {
                    $inputType = 'number';
                    $step = 'any';
                } elseif ($type === 'date') {
                    $inputType = 'date';
                } elseif ($type === 'boolean') {
                    $inputType = 'checkbox';
                }
              @endphp

              <div class="col-md-4 mb-3">
                <label class="col-form-label" for="{{ $name }}">{{ ucfirst($name) }}</label>

                @if($inputType === 'checkbox')
                  <input type="hidden" name="{{ $name }}" value="0">
                  <input 
                    type="checkbox"
                    class="form-check-input"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    value="1"
                    {{ old($name, $record->$name ?? false) ? 'checked' : '' }}
                  >
                @else
                  <input 
                    type="{{ $inputType }}"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="form-control"
                    value="{{ old($name, $record->$name ?? '') }}"
                    {{ $step ? "step=$step" : '' }}
                    required
                  >
                @endif

                @error($name)
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
              </div>

              @php $visibleIndex++; @endphp
              @if($visibleIndex % 3 === 0)
                </div><div class="row mb-3">
              @endif
            @endforeach
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-3">Update</button>
            <a href="javascript:history.back()" class="btn btn-outline-danger">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
