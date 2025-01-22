<?php

namespace App\Http\Controllers\Dashboard\Print;

use App\Http\Controllers\Controller;
use App\Models\Budget;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Response;

class BudgetController extends Controller
{

    public function generatePDF($id)
    {

        $budget = Budget::where('id', $id)
            ->with(['items' => function ($query) {
                $query->orderBy('sort_order', 'asc');
            }])
            ->first();

        $pdfName = $budget->code.'.pdf';
        $storagePath = storage_path('app/reports/'.$pdfName);

        $setting = Setting::first();

        $template = view('print.budget.items-budget', compact('budget', 'setting'))->render();

        Browsershot::html($template)
            ->setNodeBinary('/usr/bin/node')
            ->setNpmBinary('/usr/bin/npm')
            ->setOption('args', ['--no-sandbox'])
            ->setOption('executablePath', '/home/sail/.cache/puppeteer/chrome/linux-131.0.6778.204/chrome-linux64/chrome') // Defina o caminho correto para o Chrome
            ->emulateMedia('screen')
            ->showBackground()
            ->showBrowserHeaderAndFooter()
            ->hideHeader()
            ->footerHtml($this-> getFooterHtml($budget))
            ->setOption('pageRanges', '1-')
            ->format('A4')
            ->timeout(120)
            ->waitUntilNetworkIdle()
            ->ignoreHttpsErrors()
            ->savePdf($storagePath);

        if (!file_exists($storagePath)) {
            abort(404, 'File not found.');
        }

        return response()->file($storagePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $pdfName . '"',
        ])->deleteFileAfterSend(true);


    }

    /**
     * Get the Footer HTML for browsershot.
     * Injects styles to fix the bug with a font size of zero
     * @see https://github.com/puppeteer/puppeteer/issues/1853
     */
    function getFooterHtml($budget)
    {
        ob_start() ?>
        <style>
            .pageFooter {
                -webkit-print-color-adjust: exact;
                font-family: system-ui;
                font-size: 6pt;
                text-align: center;
                width: 100%;
                display: block;
                border-top: #71717a ;
            }
        </style>
        <div class="pageFooter">
            <span>Página</span> <span class="pageNumber"></span> de <span class="totalPages"></span>
        </div>
        <?php return ob_get_clean();
    }

    public function print($id)
    {

        $budget = Budget::where('id', $id)
            ->with(['items' => function ($query) {
                $query->orderBy('sort_order', 'asc');
            }])
            ->first();
        $setting = Setting::first();


        return view('print.budget.items-budget', compact('budget', 'setting'))->render();


    }

}
