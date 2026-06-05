@extends('layouts.app')

@section('title', 'ATMs')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div id="alert"></div>

    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <h2>Administrar ATMs</h2>
        <a href="{{ route('admin.atms.create') }}" class="btn btn-primary">Crear ATM</a>
    </div>

    <div class="col-lg-12 table-responsive">
        <table class="table table-hover bg-white mt-2">
            <thead>
                <tr class="bg-primary text-white">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Torniquete</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($atms as $atm)
                    <tr>
                        <td>{{ $atm->id }}</td>
                        <td>{{ $atm->nombre }}</td>
                        <td>{{ $atm->torniquete->nombre ?? '-' }}
                            {{ $atm->torniquete->id ?? '-' }}
                            {{ $atm->torniquete->endpoint_url ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.atms.edit', $atm->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                            <form action="{{ route('admin.atms.destroy', $atm->id) }}" method="POST"
                                style="display:inline-block" onsubmit="return confirm('¿Eliminar ATM?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                            <button class="btn btn-sm btn-success"
                                onclick="handledTurnstile(27123456, 'Juan Gomez (Prueba)', {{ $atm->torniquete->id }}, '{{ $atm->torniquete->endpoint_url }}', true)">Test Abrir</button>
                           
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $atms->links() }}
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        async function handledTurnstile(dni, name, turnstileId, endpoint_url, switche = false) {
            if (!confirm('Enviar solicitud de' + (switche ? ' apertura' : ' cierre') + ' para ' + name + '?')) return;

            try {
                const resp = await fetch(endpoint_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: dni,
                        name: name,
                        allowed: switche,
                        doorId: turnstileId
                    })
                });
                const data = await resp.json();

                if (resp.ok) alert('Comando enviado correctamente');
                else alert('Error: ' + (data.message || JSON.stringify(data)));
            } catch (e) {
                alert('Error enviando solicitud: ' + e.message);
            }
        }
    </script>
@endsection
