@extends('user.dashboard')

@section('prescription')

<div class="card">
    <div class="card-header">{{ __('Add prescription') }}</div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert">
                <span class="visually-hidden">&times;</span>
            </button>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </div>
        @endif
        <form action="{{ route('user.prescription.store') }}" method="post" enctype="multipart/form-data">@csrf
            <div class="form-group mb-3">
                <label for="prescription-images" class="form-label">
                    Select upto five prescription images (first one is required).
                </label>
                <div id="prescription-images">
                    <input class="form-control mb-2" type="file" id="image-1" name="image_1" required>
                    <input class="form-control mb-2" type="file" id="image-2" name="image_2">
                    <input class="form-control mb-2" type="file" id="image-3" name="image_3">
                    <input class="form-control mb-2" type="file" id="image-4" name="image_4">
                    <input class="form-control mb-2" type="file" id="image-5" name="image_5">
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="delivery-address" class="form-label">Delivery address</label>
                <input type="text" class="form-control" id="delivery-address" name="delivery_address" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" for="delivery-from-time">From time</span>
                <input type="time" class="form-control" id="delivery-from-time" name="delivery_from_time" required>

                <span class="input-group-text" for="delivery-to-time">To time</span>
                <input type="time" class="form-control" id="delivery-to-time" name="delivery_to_time" required>
            </div>
            <div class="form-group mb-3">
                <label for="note" class="form-label">Note</label>
                <input type="text" class="form-control" id="note" name="note">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary float-end">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection