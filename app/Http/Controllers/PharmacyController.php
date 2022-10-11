<?php

namespace App\Http\Controllers;

use App\Jobs\QuotationReceivedJob;
use App\Models\Drug;
use App\Models\Prescription;
use App\Models\Quotation;
use App\Services\OrganizerService;
use Exception;
use Illuminate\Http\Request;
use stdClass;

class PharmacyController extends Controller
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
            [
                'quotation',
                'created_user' => function ($query) {
                    return $query->select('id', 'name');
                }, // id must be selected to retrieve any other column
                'updated_user' => function ($query) {
                    return $query->select('id', 'name');
                },
            ]
        )
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

        return view('pharmacy.prescription.index', compact('data'));
    }

    /**
     * Show the form for creating quotation for provided prescription.
     *
     * @param int $prescriptionId
     * @return \Illuminate\Http\Response
     */
    public function createQuotation($prescriptionId)
    {
        $prescription = Prescription::find($prescriptionId);

        if ($prescription) {
            if ($prescription->has_quotation) {
                throw new Exception(
                    'Prescription already has quotation'
                );
            }

            $prescription->images = json_decode($prescription->images, true);

            return view('pharmacy.quotation.create', compact('prescription'));
        }

        throw new Exception('Prescription not found');
    }

    /**
     * Create and store quotation for provided prescription.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $prescriptionId
     * @return \Illuminate\Http\Response
     */
    public function storeQuotation(Request $request, $prescriptionId)
    {
        try {
            $prescription = Prescription::with(['created_user'])
                ->find($prescriptionId);

            if (!$prescription) {
                throw new Exception(
                    'Prescription not found'
                );
            }

            $drugList = json_decode($request->drug_list, true);

            if ($drugList) {
                Quotation::create([
                    'prescription_id' => $prescriptionId,
                    'quotation_detail' => $drugList,
                    'status' => Quotation::PENDING,
                    'created_by' => auth()->user()->id,
                ]);

                $prescription->has_quotation = true;
                $prescription->save();

                if (
                    !$prescription->created_user ||
                    !$prescription->created_user->email
                ) {
                    throw new Exception(
                        'Email of prescription user not found'
                    );
                }

                $username = $prescription->created_user->name;
                $data = [
                    'greeting' => "Hello $username!",
                    'body' => 'You have received a quotation for your prescription.',
                    'action' => 'View Prescription',
                    'actionLink' => route('user.prescription.show', [$prescription->id]),
                    'compliment' => 'Thank you for using our application!',
                ];

                dispatch(
                    new QuotationReceivedJob(
                        $prescription->created_user,
                        $data
                    )
                )->delay(now()->addMinutes(1));

                return redirect()
                    ->route('pharmacy.prescription.index')
                    ->with('message', 'Quotation sent successfully');
            } else {
                throw new Exception('No drugs found');
            }
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

            return view('pharmacy.prescription.view', compact('data'));
        }

        throw new Exception('Prescription not found');
    }

    /**
     * Search for resources based on query.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function searchDrug(Request $request)
    {
        $drug = $request->input('q');
        $drugs = Drug::when($drug, function ($query, $drug) {
            return $query->where('name', 'like', "%$drug%");
        })->get(['id', 'name', 'price']);

        return response()->json($drugs);
    }

    /**
     * Mark quotation as delivered.
     *
     * @param  int  $quotationId
     * @return \Illuminate\Http\Response
     */
    public function confirmQuotationDelivered($quotationId)
    {
        try {
            if ($quotation = Quotation::find($quotationId)) {
                if ($quotation->status !== Quotation::APPROVED) {
                    throw new Exception('Quotation is not approved');
                }

                if ($quotation->status === Quotation::DELIVERED) {
                    throw new Exception('Quotation is already delivered');
                }

                $quotation->status = Quotation::DELIVERED;
                $quotation->save();

                return redirect()
                    ->route('pharmacy.prescription.index')
                    ->with('message', 'Quotation marked as delivered successfully');
            }

            throw new Exception('Quotation not found');
        } catch (\Throwable $th) {
            throw new Exception('Process error. Reason: ' . $th->getMessage());
        }
    }
}
