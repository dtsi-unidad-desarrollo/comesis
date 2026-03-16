<table>
  <thead>
    <tr><th>DIA</th><th>FECHA</th><th>BANDEJAS</th></tr>
  </thead>
  <tbody>
    @foreach($diario as $fila)
      <tr>
        <td>{{ strtoupper($fila->dia) }}</td>
        <td>{{ $fila->fecha }}</td>
        <td>{{ number_format($fila->bandejas) }}</td>
      </tr>
    @endforeach
    <tr>
      <td colspan="2" class="text-end"><strong>TOTAL</strong></td>
      <td><strong>{{ number_format($totalComidas) }}</strong></td>
    </tr>
  </tbody>
</table>