@extends('user.dashboard')

@section('prescription')

@php
$prescription = $data['prescription'];
$quotation = $data['quotation'];
$quotationDetail = isset($quotation['quotation_detail']) ? $quotation['quotation_detail'] : null;
$totalAmount = 0;
@endphp

<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="card-subtitle text-muted">Click on image to enlarge</div>
            <div class="prescription-images text-center mt-2 mb-2">
                @foreach ($prescription['images'] as $image)
                <img src="{{ Storage::url($image) }}" class="rounded prescription-image" alt="..." height="240"
                    data-bs-toggle="modal" data-bs-target="#prescriptionImageModal">
                @endforeach
            </div>
            <div class="table-wrapper table-responsive">
                <table class="table table-bordered w-75">
                    <tbody class="text-start">
                        <tr class="d-flex">
                            <th class="col-4">Delivery address</th>
                            <td class="col-8">{{ $prescription['delivery_address'] }}</td>
                        </tr>
                        <tr class="d-flex">
                            <th class="col-4">From time</th>
                            <td class="col-8">{{ $prescription['delivery_time_from'] }}</td>
                        </tr>
                        <tr class="d-flex">
                            <th class="col-4">To time</th>
                            <td class="col-8">{{ $prescription['delivery_time_to'] }}</td>
                        </tr>
                        <tr class="d-flex">
                            <th class="col-4">Note</th>
                            <td class="col-8">{{ $prescription['note'] ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal fade" id="prescriptionImageModal" tabindex="-1"
                aria-labelledby="prescriptionImageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <img id="prescriptionImage" class="img-thumbnail" src="" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Quotation</div>
        <div class="card-body">
            @if ($quotationDetail)
            <div class="drug-wrapper">
                <div class="row fw-bold quotation-drug-header">
                    <div class="col-6">Drug</div>
                    <div class="col-3">Quantity</div>
                    <div class="col-3 text-end">Amount</div>
                </div>
                <div class="quotation-drug-list">
                    @foreach ($quotationDetail as $drug)
                    <div class="row drug-item mt-1">
                        <div class="col-6">{{ $drug['name'] }}</div>
                        <div class="col-3">
                            {{ number_format($drug['price'], 2) }} x {{$drug['quantity'] }}
                        </div>
                        <div class="col-3 text-end">{{ number_format($drug['amount'], 2) }}</div>
                    </div>
                    @php
                    $totalAmount += $drug['amount'];
                    @endphp
                    @endforeach
                </div>
                <div class="row quotation-drug-total mt-3">
                    <div class="col-3 offset-6"><strong>Total</strong></div>
                    <div class="col-3 text-end" id="drug-total-amount">
                        <strong>{{ number_format($totalAmount, 2) }}</strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="quotation-footer mt-2 d-flex justify-content-between">
                <div class="quotation-status">
                    <strong>
                        Status:
                        <span class="badge
                        @if ($quotation['status'] === 'PENDING')
                            bg-warning text-dark
                        @elseif ($quotation['status'] === 'APPROVED')
                            bg-info text-dark
                        @elseif ($quotation['status'] === 'DELIVERED')
                            bg-success
                        @else
                            bg-danger
                        @endif">
                            {{ $quotation['status'] }}
                        </span>
                    </strong>
                </div>
                <div class="quotation-approval">
                    @if ($quotation['status'] === 'PENDING')
                    <button class="btn btn-outline-success confirm-quotation-status-button" data-action="approve"
                        data-route="{{ route('user.quotation.confirm-status.approved', [$quotation['id']]) }}">
                        Approve
                    </button>
                    <button class="btn btn-outline-danger confirm-quotation-status-button" data-action="reject"
                        data-route="{{ route('user.quotation.confirm-status.rejected', [$quotation['id']]) }}">
                        Reject
                    </button>
                    @endif
                </div>
            </div>
            @else
            <div class="quotation-pending d-flex justify-content-between">
                <span class="mt-1 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                        <path
                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg>
                    <span class="fw-bold">Quotation not received yet</span>
                </span>
            </div>
            @endif
        </div>
    </div>
</div>

@if ($quotationDetail)
<div class="modal fade" id="confirm-quotation-status-modal" tabindex="-1" role="dialog"
    aria-labelledby="confirm-quotation-status-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm-quotation-status-modal-label">Mark as Delivered</h5>
                <button type="button" class="btn-close hide-confirm-quotation-status-modal-button" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                Quotation will be marked as <span id="confirm-quotation-status-action">#response</span>. This action
                cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary hide-confirm-quotation-status-modal-button">
                    Cancel
                </button>
                <form id="confirm-quotation-status-form" action="#" method="post">
                    @csrf
                    <button id="confirm-quotation-status-button-submit" type="submit"
                        class="btn btn-secondary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection