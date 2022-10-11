@extends('layouts.navigation')

@section('dashboard')

<div class="col-md-3">
    <div class="card sidebar">
        <ul class="list-group list-group-flush">
            <li class="list-group-item {{ Request::is(['pharmacy/prescription']) ? 'active' : '' }}">
                <a href="{{ route('pharmacy.prescription.index') }}">
                    All Prescriptions
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