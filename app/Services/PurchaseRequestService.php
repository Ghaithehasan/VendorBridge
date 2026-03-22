<?php

namespace App\Services;

use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PurchaseRequestService
{
    /**
     * Create a new Purchase Request with its line items.
     * The PR number, requester, and department are resolved here — never in the controller.
     */
    public function create(array $data, User $requester): PurchaseRequest
    {
        return DB::transaction(function () use ($data, $requester) {
            $pr = PurchaseRequest::create([
                'pr_number'     => $this->generatePrNumber(),
                'requester_id'  => $requester->user_id,
                'department_id' => $requester->department_id,
                'request_date'  => $data['request_date'],
                'status'        => 'draft',
                'notes'         => $data['notes'] ?? null,
            ]);

            foreach ($data['lines'] as $index => $lineData) {
                $pr->lines()->create([
                    'line_no'                => $index + 1,
                    'material_id'            => $lineData['material_id'],
                    'quantity'               => $lineData['quantity'],
                    'unit_id'                => $lineData['unit_id'],
                    'required_delivery_date' => $lineData['required_delivery_date'],
                    'notes'                  => $lineData['notes'] ?? null,
                ]);
            }

            return $pr->load(['requester', 'department', 'lines.rawMaterial', 'lines.unit']);
        });
    }

    /**
     * Update a draft PR's header fields and replace all line items.
     * Only allowed while status = draft; rejected otherwise.
     */
    public function update(PurchaseRequest $pr, array $data): PurchaseRequest
    {
        if ($pr->status !== 'draft') {
            abort(422, 'Only draft Purchase Requests can be edited.');
        }

        return DB::transaction(function () use ($pr, $data) {
            $pr->update([
                'request_date' => $data['request_date'],
                'notes'        => $data['notes'] ?? null,
            ]);

            $pr->lines()->delete();

            foreach ($data['lines'] as $index => $lineData) {
                $pr->lines()->create([
                    'line_no'                => $index + 1,
                    'material_id'            => $lineData['material_id'],
                    'quantity'               => $lineData['quantity'],
                    'unit_id'                => $lineData['unit_id'],
                    'required_delivery_date' => $lineData['required_delivery_date'],
                    'notes'                  => $lineData['notes'] ?? null,
                ]);
            }

            return $pr->load(['requester', 'department', 'lines.rawMaterial', 'lines.unit']);
        });
    }

    /**
     * Transition: draft → submitted.
     * Only the original requester or an admin may submit.
     */
    public function submit(PurchaseRequest $pr, User $user): PurchaseRequest
    {
        if ($pr->status !== 'draft') {
            abort(422, 'Only draft Purchase Requests can be submitted.');
        }

        if ($user->role === 'requester' && $pr->requester_id !== $user->user_id) {
            abort(403, 'You can only submit your own Purchase Requests.');
        }

        $pr->update(['status' => 'submitted']);

        return $pr;
    }

    /**
     * Transition: submitted → approved.
     * A procurement_manager cannot approve their own PR if they are also the requester.
     */
/**
 * Transition: submitted → approved.
 * A procurement_manager cannot approve their own PR if they are also the requester.
 */
public function approve(PurchaseRequest $pr, User $approver): PurchaseRequest
{
    if ($pr->status !== 'submitted') {
        abort(422, 'Only submitted Purchase Requests can be approved.');
    }

    if ($approver->role === 'procurement_manager' && $pr->requester_id === $approver->user_id) {
        abort(403, 'You cannot approve a Purchase Request that you created.');
    }

    $pr->update([
        'status'      => 'approved',
        'approved_by' => $approver->user_id,
        'approved_at' => now(),
    ]);

    return $pr;
}

/**
 * Transition: submitted → cancelled.
 * A cancellation reason is required for audit trail.
 */
public function cancel(PurchaseRequest $pr, User $actor, string $reason): PurchaseRequest
{
    if ($pr->status !== 'submitted') {
        abort(422, 'Only submitted Purchase Requests can be cancelled.');
    }

    $pr->update([
        'status'              => 'cancelled',
        'cancellation_reason' => $reason,
        'cancelled_by'        => $actor->user_id,
        'cancelled_at'        => now(),
    ]);

    return $pr;
}
    /**
     * Generate a unique PR number scoped to the current day.
     * Format: PR-YYYYMMDD-XXXX
     * lockForUpdate() inside a transaction prevents duplicate sequences under concurrency.
     */
    private function generatePrNumber(): string
    {
        $today  = now()->format('Ymd');
        $prefix = "PR-{$today}-";

        $last = PurchaseRequest::withTrashed()
            ->where('pr_number', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->orderByDesc('pr_number')
            ->value('pr_number');

        $sequence = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
