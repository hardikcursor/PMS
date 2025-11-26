<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class QRApiController extends Controller
{
    public function createQR(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric',
            ]);

            $amount = $request->amount * 100; 

            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $qr = $api->qrcode->create([
                'type'           => 'upi_qr',
                'name'           => 'Order Payment',
                'usage'          => 'single_use',
                'fixed_amount'   => true,
                'payment_amount' => $amount,
                'description'    => 'QR Payment',
            ]);

            Payment::create([
                'qr_id'  => $qr['id'],
                'amount' => $amount,
                'status' => 'pending',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'QR Generated Successfully',
                'data'    => [
                    'qr_id'        => $qr['id'],
                    'amount'       => $request->amount,
                    'qr_image_url' => $qr['image_url'],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage(),
            ]);
        }
    }

    // Webhook for Payment Success
    public function webhook(Request $request)
    {
        $payload = $request->all();

        if ($payload['event'] == 'payment.captured') {

            $paymentId = $payload['payload']['payment']['entity']['id'];
            $qrId      = $payload['payload']['payment']['entity']['qr_id'];
            $amount    = $payload['payload']['payment']['entity']['amount'];

            Payment::where('qr_id', $qrId)->update([
                'payment_id' => $paymentId,
                'amount'     => $amount,
                'status'     => 'success',
            ]);

            return response()->json(['message' => 'Payment Updated'], 200);
        }

        return response()->json(['message' => 'Ignored'], 200);
    }
}
