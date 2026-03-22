<?php

namespace App\Services;

use App\Models\PurchaseRequestLine;
use App\Models\Rfq;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RfqService
{
    /**
     * Create an RFQ from an approved PR line.
     * Snapshot fields are copied from the PR line at creation time and become immutable
     * once the RFQ is issued — immutability is enforced in this service, not the model.
     */

    /**
     * Transition: draft → issued.
     * purchasing_officer or admin only.
     */
    public function issue(Rfq $rfq, User $issuer): Rfq
    {
        if (! in_array($issuer->role, ['purchasing_officer', 'admin'], true)) {
            abort(403, 'Only purchasing officers or admins can issue RFQs.');
        }

        if ($rfq->status !== 'draft') {
            abort(422, 'Only draft RFQs can be issued.');
        }

        $rfq->update([
            'status'    => 'issued',
            'issued_by' => $issuer->user_id,
            'issued_at' => now(),
        ]);

        return $rfq;
    }

    /**
     * Update a draft RFQ.
     * Editable fields only: rfq_date, quotation_due_date, payment_terms,
     * delivery_location, and recipients.
     */
    public function createFromPrLine(PurchaseRequestLine $line, array $data, User $issuer): Rfq
    {
        return DB::transaction(function () use ($line, $data, $issuer) {
            $rfq = Rfq::create([
                'pr_line_id'             => $line->pr_line_id,
                'rfq_number'             => $this->generateRfqNumber(),
                'material_id'            => $line->material_id,
                'quantity'               => $line->quantity,
                'unit_id'                => $line->unit_id,
                'required_delivery_date' => $line->required_delivery_date,
                'rfq_date'               => $data['rfq_date'],
                'quotation_due_date'     => $data['quotation_due_date'],
                'currency_id'            => $data['currency_id'], // ← أضفناها
                'payment_terms'          => $data['payment_terms'] ?? null,
                'delivery_location'      => $data['delivery_location'] ?? null,
                'issued_by'              => null,
                'issued_at'              => null,
                'status'                 => 'draft',
            ]);
    
            foreach ($data['vendor_ids'] as $vendorId) {
                $rfq->recipients()->create([
                    'vendor_id' => $vendorId,
                    'status'    => 'pending',
                ]);
            }
    
            return $rfq;
        });
    }
    
    public function update(Rfq $rfq, array $data): Rfq
    {
        if ($rfq->status !== 'draft') {
            abort(422, 'Only draft RFQs can be edited.');
        }
    
        return DB::transaction(function () use ($rfq, $data) {
            $rfq->update([
                'rfq_date'           => $data['rfq_date'],
                'quotation_due_date' => $data['quotation_due_date'],
                'currency_id'        => $data['currency_id'], // ← أضفناها
                'payment_terms'      => $data['payment_terms'] ?? null,
                'delivery_location'  => $data['delivery_location'] ?? null,
            ]);
    
            $rfq->recipients()->delete();
            foreach ($data['vendor_ids'] as $vendorId) {
                $rfq->recipients()->create([
                    'vendor_id' => $vendorId,
                    'status'    => 'pending',
                ]);
            }
    
            return $rfq;
        });
    }

    /**
     * Transition: draft → cancelled.
     * Only draft RFQs can be cancelled. Once cancelled, the PR line becomes eligible for a new RFQ.
     */
    public function cancelRfq(Rfq $rfq, User $actor): Rfq
    {
        if ($rfq->status !== 'draft') {
            abort(422, 'Only draft RFQs can be cancelled.');
        }

        $rfq->update(['status' => 'cancelled']);

        return $rfq;
    }

    /**
     * Generate a unique RFQ number scoped to the current day.
     * Format: RFQ-YYYYMMDD-XXXX
     */
    private function generateRfqNumber(): string
    {
        $today  = now()->format('Ymd');
        $prefix = "RFQ-{$today}-";

        $last = Rfq::withTrashed()
            ->where('rfq_number', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->orderByDesc('rfq_number')
            ->value('rfq_number');

        $sequence = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
