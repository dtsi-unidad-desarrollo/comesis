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
                    <th>MAC</th>
                    <th>IP</th>
                    <th>Torniquete</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($atms as $atm)
                    <tr>
                        <td>{{ $atm->id }}</td>
                        <td>{{ $atm->nombre }}</td>
                        <td>{{ $atm->mac_address }}</td>
                        <td>{{ $atm->ip_address }}</td>
                        <td>{{ $atm->torniquete->nombre ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.atms.edit', $atm->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                            <form action="{{ route('admin.atms.destroy', $atm->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Eliminar ATM?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                            <button class="btn btn-sm btn-success" onclick="openTurnstile({{ $atm->id }}, '{{ addslashes($atm->nombre) }}')">Abrir</button>
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
    async function openTurnstile(atmId, name) {
        if (!confirm('Enviar solicitud de apertura para ' + name + '?')) return;

        try {
            const resp = await fetch('/api/atms/' + atmId + '/open', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ id: Date.now().toString(), name: name, allowed: true })
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
