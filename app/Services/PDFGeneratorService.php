<?php

namespace App\Services;

use App\Models\Rfq;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PDFGeneratorService
{
    /**
     * Generate and stream a PDF download for a given RFQ.
     * All relationships are eagerly loaded here so the Blade template
     * never triggers additional queries.
     */
    public function generate(Rfq $rfq): Response
    {
        $rfq->loadMissing([
            'purchaseRequestLine.purchaseRequest.requester',
            'purchaseRequestLine.purchaseRequest.department',
            'rawMaterial.baseUnit',
            'unit',
            'currency',
            'issuer',
            'recipients.vendor',
        ]);

        $pdf = Pdf::loadView('rfqs.pdf', ['rfq' => $rfq]);
        $pdf->setPaper('A4', 'portrait');

        $filename = "{$rfq->rfq_number}.pdf";

        return $pdf->download($filename);
    }
}