<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRfqRequest;
use App\Http\Requests\UpdateRfqRequest;
use App\Models\Currency;
use App\Models\PurchaseRequestLine;
use App\Models\Rfq;
use App\Models\User;
use App\Models\Vendor;
use App\Services\PDFGeneratorService;
use App\Services\RfqService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;

class RfqController extends Controller
{
    public function __construct(
        private readonly RfqService          $rfqService,
        private readonly PDFGeneratorService $pdfService,
    ) {}

    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $query = Rfq::with([
            'purchaseRequestLine.purchaseRequest',
            'rawMaterial',
            'unit',
            'issuer',
            'currency',
        ])->latest('rfq_date');

        if ($user->role === 'requester') {
            $query->whereHas('purchaseRequestLine.purchaseRequest', fn ($q) => $q->where('requester_id', $user->user_id));
        }

        $rfqs = $query->paginate(15);

        return view('rfqs.index', compact('rfqs'));
    }

    public function store(StoreRfqRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $line = PurchaseRequestLine::with('purchaseRequest')
            ->findOrFail($request->validated()['pr_line_id']);

        $rfq = $this->rfqService->createFromPrLine($line, $request->validated(), $user);

        return redirect()
            ->route('rfqs.show', $rfq->rfq_id)
            ->with('success', "RFQ {$rfq->rfq_number} has been saved as a draft and is pending procurement manager review.");
    }

    public function show(Rfq $rfq): View
    {
        /** @var User $user */
        $user = auth()->user();

        $rfq->load([
            'purchaseRequestLine.purchaseRequest.requester',
            'purchaseRequestLine.purchaseRequest.department',
            'rawMaterial.baseUnit',
            'unit',
            'currency',
            'issuer',
            'recipients.vendor',
        ]);

        // Requesters may only view RFQs linked to their own PRs
        if ($user->role === 'requester') {
            $prRequesterId = $rfq->purchaseRequestLine->purchaseRequest->requester_id;
            if ($prRequesterId !== $user->user_id) {
                abort(403, 'You are not authorised to view this RFQ.');
            }
        }

        return view('rfqs.show', compact('rfq'));
    }

    public function edit(Rfq $rfq): View
    {
        /** @var User $user */
        $user = auth()->user();

        if (! in_array($user->role, ['purchasing_officer', 'admin'], true)) {
            abort(403, 'Only purchasing officers or admins can edit draft RFQs.');
        }

        if ($rfq->status !== 'draft') {
            abort(422, 'Only draft RFQs can be edited.');
        }

        $rfq->load([
            'purchaseRequestLine.purchaseRequest',
            'rawMaterial.baseUnit',
            'unit',
            'currency',
            'recipients.vendor',
        ]);

        $vendors    = Vendor::orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();

        return view('rfqs.edit', compact('rfq', 'vendors', 'currencies'));
    }

    public function update(UpdateRfqRequest $request, Rfq $rfq): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (! in_array($user->role, ['purchasing_officer', 'admin'], true)) {
            abort(403, 'Only purchasing officers or admins can edit draft RFQs.');
        }

        $this->rfqService->update($rfq, $request->validated());

        return redirect()
            ->route('rfqs.show', $rfq->rfq_id)
            ->with('success', "RFQ {$rfq->rfq_number} draft has been updated successfully.");
    }

    public function downloadPdf(Rfq $rfq): Response
    {
        /** @var User $user */
        $user = auth()->user();

        // Re-apply ownership check for requesters accessing the PDF directly
        if ($user->role === 'requester') {
            $rfq->loadMissing('purchaseRequestLine.purchaseRequest');
            $prRequesterId = $rfq->purchaseRequestLine->purchaseRequest->requester_id;
            if ($prRequesterId !== $user->user_id) {
                abort(403, 'You are not authorised to download this RFQ PDF.');
            }
        }

        return $this->pdfService->generate($rfq);
    }

    public function issue(Rfq $rfq): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (! in_array($user->role, ['purchasing_officer', 'admin'], true)) {
            abort(403, 'Only purchasing officers or admins can issue RFQs.');
        }

        $this->rfqService->issue($rfq, $user);

        return redirect()
            ->route('rfqs.show', $rfq->rfq_id)
            ->with('success', "RFQ {$rfq->rfq_number} has been issued to vendors.");
    }

    public function cancelRfq(Rfq $rfq): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403, 'Only admins can cancel RFQs.');
        }

        $this->rfqService->cancelRfq($rfq, $user);

        return redirect()
            ->route('rfqs.show', $rfq->rfq_id)
            ->with('success', "RFQ {$rfq->rfq_number} has been cancelled.");
    }
}
