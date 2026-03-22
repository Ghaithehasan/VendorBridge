<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestLine;
use App\Models\Rfq;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {

    /** @var User $user */
    $user = auth()->user();

    // Defaults
    $requesterSummary          = ['draft' => 0, 'submitted' => 0, 'approved' => 0];
    $requesterRecentPrs        = collect();
    $pendingApprovalPrs        = collect();
    $recentApprovalActivity    = collect();
    $approvedLinesReadyForRfq  = collect();
    $approvedLinesReadyForRfqCount = 0;
    $recentRfqs                = collect();
    $adminRecentPrs            = collect();
    $adminRecentRfqs           = collect();

    // Always visible to ALL roles — metrics row
    $adminTotals = [
        'purchase_requests' => PurchaseRequest::count(),
        'rfqs'              => Rfq::count(),
        'vendors'           => Vendor::count(),
    ];
    $pendingApprovalCount = PurchaseRequest::where('status', 'submitted')->count();

    if ($user->role === 'requester') {
        $requesterSummary = [
            'draft'     => PurchaseRequest::where('requester_id', $user->user_id)->where('status', 'draft')->count(),
            'submitted' => PurchaseRequest::where('requester_id', $user->user_id)->where('status', 'submitted')->count(),
            'approved'  => PurchaseRequest::where('requester_id', $user->user_id)->where('status', 'approved')->count(),
        ];
        $requesterRecentPrs = PurchaseRequest::with([
                'department',
                'lines.rawMaterial',
                'approver',
                'canceller',
            ])
            ->where('requester_id', $user->user_id)
            ->latest('request_date')
            ->take(5)
            ->get();
    }

    if ($user->role === 'procurement_manager') {
        $pendingApprovalPrs = PurchaseRequest::with(['requester', 'department'])
            ->where('status', 'submitted')
            ->latest('request_date')
            ->take(5)
            ->get();
        $recentApprovalActivity = PurchaseRequest::with([
                'requester',
                'department',
                'approver',
                'canceller',
            ])
            ->whereIn('status', ['approved', 'cancelled'])
            ->latest('updated_at')
            ->take(5)
            ->get();
    }

    if ($user->role === 'purchasing_officer') {
        $approvedLinesReadyForRfqCount = PurchaseRequestLine::whereDoesntHave('rfq')
            ->whereHas('purchaseRequest', fn ($q) => $q->where('status', 'approved'))
            ->count();
        $approvedLinesReadyForRfq = PurchaseRequestLine::with([
                'purchaseRequest.requester',
                'rawMaterial',
                'unit',
            ])
            ->whereDoesntHave('rfq')
            ->whereHas('purchaseRequest', fn ($q) => $q->where('status', 'approved'))
            ->latest('required_delivery_date')
            ->take(5)
            ->get();
        $recentRfqs = Rfq::with([
                'purchaseRequestLine.purchaseRequest',
                'rawMaterial',
                'recipients',
            ])
            ->latest('rfq_date')
            ->take(5)
            ->get();
    }

    if ($user->role === 'admin') {
        $adminRecentPrs = PurchaseRequest::with([
                'requester',
                'department',
                'approver',
                'canceller',
            ])
            ->latest('updated_at')
            ->take(5)
            ->get();
        $adminRecentRfqs = Rfq::with([
                'purchaseRequestLine.purchaseRequest',
                'rawMaterial',
                'issuer',
            ])
            ->latest('updated_at')
            ->take(5)
            ->get();
    }

    return view('dashboard', compact(
        'user', 'requesterSummary', 'requesterRecentPrs',
        'pendingApprovalPrs', 'pendingApprovalCount',
        'recentApprovalActivity', 'approvedLinesReadyForRfq',
        'approvedLinesReadyForRfqCount', 'recentRfqs',
        'adminTotals', 'adminRecentPrs', 'adminRecentRfqs',
    ));
}}