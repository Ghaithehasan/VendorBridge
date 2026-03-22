<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequestRequest;
use App\Http\Requests\UpdatePurchaseRequestRequest;
use App\Models\Currency;
use App\Models\PurchaseRequest;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vendor;
use App\Services\PurchaseRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseRequestController extends Controller
{
    public function __construct(
        private readonly PurchaseRequestService $prService,
    ) {}

    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $query = PurchaseRequest::with(['requester', 'department', 'lines'])
            ->latest();

        if ($user->role === 'requester') {
            // Requesters see only their own PRs
            $query->where('requester_id', $user->user_id);
        } elseif ($user->role === 'purchasing_officer') {
            // Purchasing officers see only approved PRs — they act on approved PRs only
            $query->where('status', 'approved');
        }
        // procurement_manager and admin see all PRs

        $purchaseRequests = $query->paginate(15);

        return view('purchase-requests.index', compact('purchaseRequests'));
    }

    public function create(): View
    {
        $rawMaterials = RawMaterial::with('baseUnit')->orderBy('name')->get();
        $units        = Unit::orderBy('name')->get();

        return view('purchase-requests.create', compact('rawMaterials', 'units'));
    }

    public function store(StorePurchaseRequestRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $pr = $this->prService->create($request->validated(), $user);

        return redirect()
            ->route('purchase-requests.show', $pr->pr_id)
            ->with('success', "Purchase Request {$pr->pr_number} has been created successfully.");
    }

    public function show(PurchaseRequest $purchaseRequest): View
    {
        /** @var User $user */
        $user = auth()->user();
    
        if ($user->role === 'requester' && $purchaseRequest->requester_id !== $user->user_id) {
            abort(403, 'You are not authorised to view this Purchase Request.');
        }
    
        $purchaseRequest->load([
            'requester',
            'department',
            'lines.rawMaterial.baseUnit',
            'lines.rawMaterial.vendorMaterials.vendor',
            'lines.rawMaterial.vendorMaterials.currency',
            'lines.unit',
            'lines.rfq',
        ]);
    
        // Build per-line vendor data — only vendors who supply this material
        $vendorsByLine = [];
        foreach ($purchaseRequest->lines as $line) {
            $vendorsByLine[$line->pr_line_id] = $line->rawMaterial->vendorMaterials
                ->sortByDesc('preferred_vendor')
                ->values();
        }
    
        $currencies = Currency::orderBy('code')->get();
    
        return view('purchase-requests.show', compact(
            'purchaseRequest',
            'vendorsByLine',
            'currencies'
        ));
    }
    public function edit(PurchaseRequest $purchaseRequest): View
    {
        /** @var User $user */
        $user = auth()->user();

        if ($purchaseRequest->status !== 'draft') {
            return redirect()
                ->route('purchase-requests.show', $purchaseRequest->pr_id)
                ->with('error', 'Only draft Purchase Requests can be edited.');
        }

        if ($user->role === 'requester' && $purchaseRequest->requester_id !== $user->user_id) {
            abort(403, 'You can only edit your own Purchase Requests.');
        }

        $purchaseRequest->load(['lines.rawMaterial', 'lines.unit']);

        $rawMaterials = RawMaterial::with('baseUnit')->orderBy('name')->get();
        $units        = Unit::orderBy('name')->get();

        return view('purchase-requests.edit', compact('purchaseRequest', 'rawMaterials', 'units'));
    }

    public function update(UpdatePurchaseRequestRequest $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $this->prService->update($purchaseRequest, $request->validated());

        return redirect()
            ->route('purchase-requests.show', $purchaseRequest->pr_id)
            ->with('success', 'Purchase Request has been updated successfully.');
    }

    public function submit(PurchaseRequest $purchaseRequest): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->prService->submit($purchaseRequest, $user);

        return redirect()
            ->route('purchase-requests.show', $purchaseRequest->pr_id)
            ->with('success', "Purchase Request {$purchaseRequest->pr_number} has been submitted for approval.");
    }

    public function approve(PurchaseRequest $purchaseRequest): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (! in_array($user->role, ['procurement_manager', 'admin'], true)) {
            abort(403, 'Only procurement managers or admins can approve Purchase Requests.');
        }

        $this->prService->approve($purchaseRequest, $user);

        return redirect()
            ->route('purchase-requests.show', $purchaseRequest->pr_id)
            ->with('success', "Purchase Request {$purchaseRequest->pr_number} has been approved.");
    }

    public function cancel(Request $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'cancellation_reason.required' => 'A cancellation reason is required for audit trail.',
            'cancellation_reason.min'      => 'Please provide a meaningful cancellation reason (at least 10 characters).',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (! in_array($user->role, ['procurement_manager', 'admin'], true)) {
            abort(403, 'Only procurement managers or admins can cancel Purchase Requests.');
        }

        $this->prService->cancel($purchaseRequest, $user, $request->cancellation_reason);

        return redirect()
            ->route('purchase-requests.show', $purchaseRequest->pr_id)
            ->with('success', "Purchase Request {$purchaseRequest->pr_number} has been cancelled.");
    }
}
