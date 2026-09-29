@php
    $segments = request()->segments();
    $categoria = isset($segments[0]) ? strtoupper($segments[0]) : 'PANEL';
    $subcategoria = isset($segments[1]) ? strtoupper($segments[1]) : '';
@endphp

<div class="pagetitle">
    <h1 class="text-primary">{{ $categoria }}</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">{{ Auth::user()->nombre }}</li>
        
      </ol>
    </nav>
</div>