@php
  $respuesta = [
    'estatus' => session('estatus', 200),
    'mensaje' => session('mensaje', ''),
    'clases' => [
      '200' => 'alert-success',
      '201' => 'alert-success',
      '301' => 'alert-warning',
      '401' => 'alert-warning',
      '404' => 'alert-danger',
      '500' => 'alert-danger',
    ],
    'icono' => [
      '200' => 'bi bi-check-circle me-1',
      '201' => 'bi bi-check-circle me-1',
      '301' => 'bi bi-exclamation-triangle me-1',
      '401' => 'bi bi-exclamation-octagon me-1',
      '404' => 'bi bi-exclamation-octagon me-1',
      '500' => 'bi bi-exclamation-octagon me-1',
    ],
  ];

  $estatusKey = (string) $respuesta['estatus'];
  if (! isset($respuesta['clases'][$estatusKey])) {
      $estatusKey = '200';
  }
@endphp

<div class="row" id="alert">
  <div class="col-sm-8"></div>
  <div class="col-sm-4">
    <div class="alert {{ $respuesta['clases'][$estatusKey] }} alert-dismissible fade show w-100" role="alert">
        <i class="{{ $respuesta['icono'][$estatusKey] }}"></i>
        {!! $respuesta['mensaje'] !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  </div>
</div>
