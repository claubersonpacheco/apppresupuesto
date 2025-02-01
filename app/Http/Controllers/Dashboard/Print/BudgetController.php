<?php

namespace App\Http\Controllers\Dashboard\Print;

use App\Http\Controllers\Controller;
use App\Mail\SendBudget;
use App\Models\Budget;

use App\Models\SendEmail;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
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
            ->setOption('executablePath', '/home/sail/.cache/puppeteer/chrome/linux-131.0.6778.204/chrome-linux64/chrome') // '/usr/bin/google-chrome' Defina o caminho correto para o Chrome
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


    public function sendEmail($id)
    {
        $budget = Budget::where('id', $id)
            ->with(['items' => function ($query) {
                $query->orderBy('sort_order', 'asc');
            }])
            ->first();

        $pdfName = Date('dmY').$budget->code . '.pdf';
        $storagePath = storage_path('app/reports/' . $pdfName);

        $setting = Setting::first();

        $template = view('print.budget.items-budget', compact('budget', 'setting'))->render();

        Browsershot::html($template)
            ->setNodeBinary('/usr/bin/node')
            ->setNpmBinary('/usr/bin/npm')
            ->setOption('args', ['--no-sandbox'])
            ->setOption('executablePath', '/home/sail/.cache/puppeteer/chrome/linux-131.0.6778.204/chrome-linux64/chrome') //'/usr/bin/google-chrome'
            ->emulateMedia('screen')
            ->showBackground()
            ->showBrowserHeaderAndFooter()
            ->hideHeader()
            ->footerHtml($this->getFooterHtml($budget))
            ->setOption('pageRanges', '1-')
            ->format('A4')
            ->timeout(120)
            ->waitUntilNetworkIdle()
            ->ignoreHttpsErrors()
            ->savePdf($storagePath);

        if (!file_exists($storagePath)) {
            abort(404, 'File not found.');
        }

        // Enviar o e-mail com o PDF em anexo
        $emailData = [
            'name' => $budget->customer_name,
            'code' => $budget->code,
            'total' => $budget->total,
            'budget' => $budget->name,
            'logo_impress' => $setting->logo_impress,
            'title' => $setting->title,
            'email' =>  $setting->email,
            'whatsapp' => $setting->whatsapp
        ];

        $pdfContent = file_get_contents($storagePath);

        Mail::to($budget->customer->email)->send(new SendBudget($emailData, $pdfContent, $pdfName ));

        return back()->with('success', 'Email successfully sent.');
    }


}
