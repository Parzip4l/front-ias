<?php

namespace App\Http\Controllers\Sppd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SppdPaymentController extends Controller
{
    /**
     * Create payment invoice untuk SPPD
     */
    public function create(Request $request, $sppdId)
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/payment/create";
        $token  = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token tidak tersedia, silakan login.');
        }

        try {
            $response = Http::withToken($token)
                ->post($apiUrl, [
                    'sppd_id' => $sppdId,
                    'amount' => $request->amount,
                    'payment_type' => $request->payment_type
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Redirect ke halaman pembayaran Xendit
                if (!empty($data['invoice_url'])) {
                    return redirect()->away($data['invoice_url']);
                }

                return redirect()->back()->with('error', 'Gagal membuat pembayaran.');
            } else {
                return redirect()->back()->with('error', 'API error: ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function invoice($paymentId)
    {
        $apiUrl = rtrim(env('SPPD_API_URL'), '/') . "/payment/invoice/{$paymentId}";
        $token = Session::get('jwt_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token tidak tersedia, silakan login.');
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($apiUrl);

            if (!$response->successful()) {
                $message = $response->json('message') ?? 'Invoice tidak dapat dibuka.';
                return redirect()->back()->with('error', $message);
            }

            $invoice = $response->json('data');
            $invoiceUrl = $invoice['invoice_url'] ?? null;

            if (!$invoiceUrl) {
                return redirect()->back()->with('error', 'Invoice belum tersedia.');
            }

            $host = parse_url($invoiceUrl, PHP_URL_HOST);
            $isPlaceholderInvoice = in_array($host, ['example.test', 'www.example.test'], true);

            return view('pages.sppd.invoice', [
                'invoice' => $invoice,
                'invoiceUrl' => $invoiceUrl,
                'isPlaceholderInvoice' => $isPlaceholderInvoice,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuka invoice: ' . $e->getMessage());
        }
    }
}
