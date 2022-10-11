<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrescriptionFormRequest;
use App\Jobs\QuotationUpdatedJob;
use App\Models\Prescription;
use App\Models\Quotation;
use App\Models\Role;
use App\Models\User;
use App\Services\OrganizerService;
use DateTime;
use Exception;
use stdClass;

class UserController extends Controller
{
    /**
     * Display a listing of prescriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function prescriptionIndex(OrganizerService $organizer)
    {
        $data = [];
        $prescriptions = Prescription::with(
            ['created_user', 'quotation']
        )
            ->where('created_by', auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        foreach ($prescriptions as $prescription) {
            $quotation = new stdClass();

            if ($prescription->quotation) {
                $quotation = $prescription->quotation;
            }

            $data[] = $organizer->organizePrescriptionQuotation(
                $prescription,
                $quotation
            );
        }

        return view('user.prescription.index', compact('data'));
    }

    /**
     * Show the form for creating a new prescription.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPrescription()
    {
        return view('user.prescription.create');
    }

    /**
     * Store a newly created prescription in storage.
     *
     * @param  \Illuminate\Http\PrescriptionFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storePrescription(PrescriptionFormRequest $request)
    {
        try {
            $images = [
                $request->file('image_1')->store('public/prescriptions'),
            ];

            if ($request->hasFile('image_2'))
                $images[] = $request->file('image_2')->store('public/prescriptions');

            if ($request->hasFile('image_3'))
                $images[] = $request->file('image_3')->store('public/prescriptions');

            if ($request->hasFile('image_4'))
                $images[] = $request->file('image_4')->store('public/prescriptions');

            if ($request->hasFile('image_5'))
                $images[] = $request->file('image_5')->store('public/prescriptions');

            $fromTime = new DateTime($request->delivery_from_time);
            $toTime = new DateTime($request->delivery_to_time);

            $data = [
                'images' => json_encode($images),
                'delivery_address' => $request->delivery_address,
                'delivery_time_from' => $fromTime->format('H:i'),
                'delivery_time_to' => $toTime->format('H:i'),
                'note' => $request->note,
                'created_by' => auth()->user()->id,
            ];

            Prescription::create($data);

            return redirect()->route('user.prescription.index')
                ->with('message', 'Prescription added successfully.');
        } catch (\Throwable $th) {
            throw new Exception('Process error. Reason: ' . $th->getMessage());
        }
    }

    /**
     * Display selected prescription with quotation if exists.
     *
     * @param  int  $prescriptionId
     * @return \Illuminate\Http\Response
     */
    public function showPrescription($prescriptionId, OrganizerService $organizer)
    {
        $prescription = Prescription::with('quotation')->find($prescriptionId);
        $quotation = new stdClass();

        if ($prescription) {
            if ($prescription->quotation) {
                $quotation = $prescription->quotation;
            }

            $data = $organizer->organizePrescriptionQuotation(
                $prescription,
                $quotation
            );

            return view('user.prescription.view', compact('data'));
        }

        throw new Exception('Prescription not found');
    }

    /**
     * Mark quotation as approved.
     *
     * @param  int  $quotationId
     * @return \Illuminate\Http\Response
     */
    public function confirmQuotationApproved($quotationId)
    {
        try {
            $quotation = Quotation::with(
                ['prescription.created_user' => function ($query) {
                    return $query->select(['id', 'name']);
                }]
            )->find($quotationId);

            if ($quotation) {
                if ($quotation->status !== Quotation::PENDING) {
                    throw new Exception('Quotation is not pending');
                }

                if ($quotation->status === Quotation::APPROVED) {
                    throw new Exception('Quotation is already approved');
                }

                $quotation->status = Quotation::APPROVED;
                $quotation->save();

                if (
                    !$quotation->prescription ||
                    !$quotation->prescription->created_user
                ) {
                    throw new Exception(
                        'Prescription user not found'
                    );
                }

                $pharmacyUsers = User::where('role_id', Role::PHARMACY)->get();
                $username = $quotation->prescription->created_user->name;
                $data = [
                    'subject' => 'Quotation Approved',
                    'greeting' => 'Hello!',
                    'body' => "Quotation for the prescription has been approved by $username.",
                    'action' => 'View Prescription',
                    'actionLink' => route('pharmacy.prescription.show', [$quotation->prescription->id]),
                ];

                dispatch(
                    new QuotationUpdatedJob(
                        $pharmacyUsers,
                        $data
                    )
                )->delay(now()->addMinutes(1));

                return redirect()
                    ->route('user.prescription.index')
                    ->with('message', 'Quotation marked as approved successfully');
            }

            throw new Exception('Quotation not found');
        } catch (\Throwable $th) {
            throw new Exception('Process error. Reason: ' . $th->getMessage());
        }
    }

    /**
     * Mark quotation as rejected.
     *
     * @param  int  $quotationId
     * @return \Illuminate\Http\Response
     */
    public function confirmQuotationRejected($quotationId)
    {
        try {
            $quotation = Quotation::with(
                ['prescription.created_user' => function ($query) {
                    return $query->select(['id', 'name']);
                }]
            )->find($quotationId);

            if ($quotation) {
                if ($quotation->status !== Quotation::PENDING) {
                    throw new Exception('Quotation is not pending');
                }

                if ($quotation->status === Quotation::REJECTED) {
                    throw new Exception('Quotation is already rejected');
                }

                $quotation->status = Quotation::REJECTED;
                $quotation->save();

                if (
                    !$quotation->prescription ||
                    !$quotation->prescription->created_user
                ) {
                    throw new Exception(
                        'Prescription user not found'
                    );
                }

                $pharmacyUsers = User::where('role_id', Role::PHARMACY)->get();
                $username = $quotation->prescription->created_user->name;
                $data = [
                    'subject' => 'Quotation Rejected',
                    'greeting' => 'Hello!',
                    'body' => "Quotation for the prescription has been rejected by $username.",
                    'action' => 'View Prescription',
                    'actionLink' => route('pharmacy.prescription.show', [$quotation->prescription->id]),
                ];

                dispatch(
                    new QuotationUpdatedJob(
                        $pharmacyUsers,
                        $data
                    )
                )->delay(now()->addMinutes(1));

                return redirect()
                    ->route('user.prescription.index')
                    ->with('message', 'Quotation marked as rejected successfully');
            }

            throw new Exception('Quotation not found');
        } catch (\Throwable $th) {
            throw new Exception('Process error. Reason: ' . $th->getMessage());
        }
    }
}
