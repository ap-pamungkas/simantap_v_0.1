@props(['active'=> false, 'url'=>'', 'label'=>''])

@php
    $class = ($active ?? false) ? 'active' : '';

@endphp

<a wire:poll.1s  href="{{  route("$url")}}" {{ $attributes->merge(['class' => $class]) }}>
    <i class="nav-icon bi bi-chevron-right "></i>
    <p>{{ $label }} </p>
  </a>

  {{-- bi bi-chevron-right --}}
  {{-- url('admin/petugas')  --}}
