@extends('layouts.app')

@section('title', 'Lista de Usuários')

@section('content')
<div class="container">
    <h1>Usuários</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->customer)
                            <a href="{{ route('admin.userOrders', $user->customer->id_customer) }}">Ver Histórico de Compras</a>
                        @else
                            No Customer Data
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection