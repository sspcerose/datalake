@extends('layouts/contentNavbarLayout')

@section('content')
<div class="container d-flex justify-content-center">
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Add Record to {{ ucfirst($selectedTable) }}</h5>
        <small class="text-muted float-end">
          <a href="javascript:history.back()">Back</a>
        </small>
      </div>
      <div class="card-body">
        {{-- Error Display --}}
        @if($errors->has('error'))
          <div class="alert alert-danger">{{ $errors->first('error') }}</div>
        @endif

        <form action="{{ route('table.store', $selectedTable) }}" method="POST" id="dynamicForm">
          @csrf

          <div class="row mb-3">
            @php $visibleIndex = 0; @endphp
              @foreach($columnDetails as $index => $column)
                <!-- Display columns except id, created_at, and updated_at -->
                @if(!in_array($column['name'], ['id', 'created_at', 'updated_at']))
                  <div class="col-md-4 mb-3">
                    <label class="col-form-label" for="{{ $column['name'] }}">{{ ucfirst($column['name']) }}</label>

                    @if($column['type'] === 'boolean')
                      <input type="hidden" name="{{ $column['name'] }}" value="0">
                      <input type="checkbox" name="{{ $column['name'] }}" id="{{ $column['name'] }}" value="1">
                    @else
                      <input 
                        type="{{ $column['type'] === 'integer' ? 'number' : ($column['type'] === 'date' ? 'date' : 'text') }}"
                        class="form-control" 
                        name="{{ $column['name'] }}" 
                        id="{{ $column['name'] }}"
                        required
                      >
                    @endif

                    @error($column['name'])
                      <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                    @enderror
                  </div>

                  @php $visibleIndex++; @endphp
                  
                  <!-- 3 column per row -->
                  @if($visibleIndex % 3 === 0)
                    </div><div class="row mb-3">
                  @endif
                @endif
              @endforeach
            </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-3">Save</button>
            <a href="javascript:history.back()" class="btn btn-outline-danger">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
