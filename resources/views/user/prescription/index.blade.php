@extends('user.dashboard')

@section('prescription')

@include('inc.message')

<div class="table-responsive">
    <table class="table table-bordered">
        @if ($data)
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Delivery Address</th>
                <th scope="col">From Time</th>
                <th scope="col">To Time</th>
                <th scope="col">Note</th>
                <th scope="col">Quotation Received</th>
                <th scope="col">Status</th>
                <th scope="col">Created At</th>
                <th scope="col"></th>
            </tr>
        </thead>
        @endif
        <tbody>
            @forelse ($data as $key => $array)
            @php
            $prescription = $array['prescription'];
            $quotation = $array['quotation'];
            @endphp
            <tr>
                <th scope="row">{{ $key + 1 }}</th>
                <td>
                    {{
                    $prescription['delivery_address'] ?
                    trim(substr($prescription['delivery_address'], 0, 18)) . '...' :
                    '-'
                    }}
                </td>
                <td>{{ $prescription['delivery_time_from'] }}</td>
                <td>{{ $prescription['delivery_time_to'] }}</td>
                <td>
                    {{
                    $prescription['note'] ?
                    trim(substr($prescription['note'], 0, 12)) . '...' :
                    '-'
                    }}
                </td>
                <td>
                    @if ($prescription['has_quotation'])
                    <span class="badge bg-light text-success border border-success">Yes</span>
                    @else
                    <span class="badge bg-light text-danger border border-danger">No</span>
                    @endif
                </td>
                <td>
                    <span class="badge
                        @if (!$quotation)
                            bg-secondary
                        @elseif ($quotation['status'] === 'PENDING')
                            bg-warning text-dark
                        @elseif ($quotation['status'] === 'APPROVED')
                            bg-info text-dark
                        @elseif ($quotation['status'] === 'DELIVERED')
                            bg-success
                        @else
                            bg-danger
                        @endif">
                        {{
                        $quotation && $quotation['status'] ?
                        $quotation['status'] :
                        'NO QUOTATION'
                        }}
                    </span>
                </td>
                <td>{{ $prescription['created_at']->format('Y-m-d h:i A') }}</td>
                <td>
                    <a href="{{ route('user.prescription.show', [$prescription['id']]) }}">
                        <button class="btn btn-primary">View</button>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td>You have not added any prescriptions</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection