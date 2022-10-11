<?php

namespace App\Services;

class OrganizerService
{
    /**
     * Organize prescription and quotation for rendering.
     *
     * @param object $prescription
     * @param object $quotation
     * @return array $data
     */
    public function organizePrescriptionQuotation($prescription, $quotation)
    {
        $prescriptionArray = [];
        $quotationArray = [];

        if (count((array) $prescription)) {
            $createdBy = isset($prescription->created_user) ?
                $prescription->created_user->name :
                '';

            $updatedBy = isset($prescription->updated_user) ?
                $prescription->updated_user->name :
                '';

            $prescriptionArray = [
                'id' => $prescription->id,
                'images' => json_decode($prescription->images, true),
                'delivery_address' => $prescription->delivery_address,
                'delivery_time_from' => $prescription->delivery_time_from,
                'delivery_time_to' => $prescription->delivery_time_to,
                'note' => $prescription->note,
                'has_quotation' => $prescription->has_quotation,
                'created_by' => $createdBy,
                'updated_by' => $updatedBy,
                'created_at' => $prescription->created_at,
                'updated_at' => $prescription->updated_at,
            ];
        }

        if (count((array) $quotation)) {
            $createdBy = isset($quotation->created_user) ?
                $quotation->created_user->name :
                '';

            $updatedBy = isset($quotation->updated_user) ?
                $quotation->updated_user->name :
                '';

            $quotationArray = [
                'id' => $quotation->id,
                'prescription_id' => $quotation->prescription_id,
                'quotation_detail' => $quotation->quotation_detail,
                'status' => $quotation->status,
                'created_by' => $createdBy,
                'updated_by' => $updatedBy,
                'created_at' => $quotation->created_at,
                'updated_at' => $quotation->updated_at,
            ];
        }

        $data['prescription'] = $prescriptionArray;
        $data['quotation'] = $quotationArray;

        return $data;
    }
}
