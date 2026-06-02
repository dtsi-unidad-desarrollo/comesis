@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear ATM</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.atms.store') }}">
        @include('admin.atm._form')
    </form>
</div>
@endsection
