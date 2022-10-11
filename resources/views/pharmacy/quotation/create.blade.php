@extends('pharmacy.dashboard')

@section('quotation')

<div class="card-group">
    <div class="card">
        <div class="card-header">Prescription</div>
        <div class="card-body">
            <div class="card-subtitle text-muted">Click on image to enlarge</div>
            <div class="prescription-images text-center mt-2 mb-2">
                @foreach ($prescription->images as $image)
                <img src="{{ Storage::url($image) }}" class="rounded prescription-image" alt="..." height="240"
                    data-bs-toggle="modal" data-bs-target="#prescriptionImageModal">
                @endforeach
            </div>
            <div class="table-wrapper table-responsive mt-4">
                <table class="table table-bordered w-75">
                    <tbody class="text-start">
                        <tr class="d-flex">
                            <th class="col-4">Delivery address</th>
                            <td class="col-8">{{ $prescription->delivery_address }}</td>
                        </tr>
                        <tr class="d-flex">
                            <th class="col-4">From time</th>
                            <td class="col-8">{{ $prescription->delivery_time_from }}</td>
                        </tr>
                        <tr class="d-flex">
                            <th class="col-4">To time</th>
                            <td class="col-8">{{ $prescription->delivery_time_to }}</td>
                        </tr>
                        <tr class="d-flex">
                            <th class="col-4">Note</th>
                            <td class="col-8">{{ $prescription->note ?? '-' }}</td>
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
            <form id="send-quotation-form" action="{{ route('pharmacy.quotation.store', [$prescription->id]) }}"
                method="post">@csrf
                <div class="drug-wrapper">
                    <div class="row fw-bold drug-header">
                        <div class="col-6">Drug</div>
                        <div class="col-3">Quantity</div>
                        <div class="col-2 text-end">Amount</div>
                        <div class="col-1"></div>
                    </div>
                    <div class="drug-list"></div>
                    <input type="hidden" id="selected-drug" data-selected_drug="{}">
                    <input type="hidden" name="drug_list" id="drug-list" value="">
                    <div class="row drug-total mt-3">
                        <div class="col-3 offset-6"><strong>Total</strong></div>
                        <div class="col-2 text-end" id="drug-total-amount"><strong>00.00</strong></div>
                    </div>
                    <div class="drug-add mt-4">
                        <div class="row form-group">
                            <label for="select-drug-list" class="col-6 text-end">Drug</label>
                            <select id="select-drug-list" class="col-6"></select>
                        </div>
                        <div class="row form-group mt-2">
                            <label for="drug-quantity" class="col-6 text-end">Quantity</label>
                            <input type="number" id="drug-quantity" class="col-6" min="1" step="1"
                                onfocus="this.previousValue = this.value" onkeydown="this.previousValue = this.value"
                                oninput="validity.valid || (value = this.previousValue)">
                        </div>
                        <div class="row form-group mt-2">
                            <button id="add-drug-item" type="button"
                                class="btn btn-dark col-3 offset-9 float-end">Add</button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row form-group mt-4">
                    <button id="send-quotation-button" class="btn btn-primary col-4 offset-8 float-end" disabled="true">
                        {{ __('Send Quotation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection