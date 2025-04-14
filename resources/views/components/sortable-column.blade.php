@php
    $currentSortField = request('sort_field');
    $currentSortOrder = request('sort_order');

    $nextSortOrder = $currentSortField === $field
        ? ($currentSortOrder === 'asc' ? 'desc' : ($currentSortOrder === 'desc' ? 'none' : 'asc'))
        : 'asc';

    $sortIcon = $currentSortField === $field
        ? ($currentSortOrder === 'asc' ? '⥣' : ($currentSortOrder === 'desc' ? '⥥' : '⥮'))
        : '⥮';
@endphp

<a href="{{ route($route, ['sort_field' => $field, 'sort_order' => $nextSortOrder]) }}"
   class="text-reset text-decoration-none">
    {{ $label }} &nbsp;&nbsp; {{  $sortIcon }}
</a>
