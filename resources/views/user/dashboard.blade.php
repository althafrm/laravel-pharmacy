@extends('layouts.navigation')

@section('dashboard')

<div class="col-md-3">
    <div class="card sidebar">
        <ul class="list-group list-group-flush">
            @can('users-only')
            <li class="list-group-item {{ Request::is('user/prescription/create') ? 'active' : '' }}">
                <a href="{{ route('user.prescription.create') }}">Add Prescription</a>
            </li>
            @endcan
            <li class="list-group-item {{ Request::is(['user/prescription']) ? 'active' : '' }}">
                <a href="{{ route('user.prescription.index') }}">
                    My Prescriptions
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="col-md-9">
    @yield('prescription')
    @yield('quotation')
</div>

@endsection