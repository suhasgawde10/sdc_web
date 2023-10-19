<?php


use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayMaster
{
    public function processPaymentDetails($payment_id, $amount, $currency)
    {
        $data = array();

        if ($payment_id != "") {
            $api = new Api(RAZOR_LIVE_API, RAZOR_LIVE_SECRET);

            $payment_capture = $api->payment->fetch($payment_id)->capture(array('amount' => $amount, 'currency' => $currency));
            $payment = $api->payment->fetch($payment_capture->id);

            $data["currency"] = $payment->currency;
            $data["amount"] = $payment->amount;
            $data["order_id"] = $payment->order_id;
            $data["invoice_id"] = $payment->invoice_id;
            $data["payment_method"] = $payment->method;
            $data["captured_status"] = $payment->captured;
            $data["payment_status"] = $payment->status;
            $data["error_code"] = $payment->error_code;
            $data["error_description"] = $payment->error_description;
            $data["upi"] = $payment->vpa;
            $data["refund_status"] = $payment->refund_status;
            $data["wallet"] = $payment->wallet;

            if ($data["payment_method"] == "card") {
                $card_details = $api->card->fetch($payment->card_id);
                $data["card_network"] = $card_details->network;
                $data["card_type"] = $card_details->type;
                $data["bank"] = $card_details->issuer;
            } else if ($data["payment_method"] == "netbanking") {
                $data["bank"] = $payment->bank;
            }
        } else {
            $data["payment_status"] = "failed";
        }
        return $data;
    }

    public function transferAmount($payment_id, $account_id, $amount)
    {
        $data = array();
        $api = new Api(RAZOR_LIVE_API, RAZOR_LIVE_SECRET);
        //$account_id = "acc_F7DSySdooyUPuL";
        $transfer = $api->payment->fetch($payment_id)->transfer(array(
                'transfers' => array(
                    array(
                        'account' => $account_id,
                        'amount' => $amount / 2,
                        'currency' => 'INR'
                    )
                )
            )
        );

        $data["transfer_id"] = $transfer->id;
        return $data;
    }

    public function getPaymentDetails($payment_id)
    {
        $data = array();

        if ($payment_id != "") {
            $api = new Api(RAZOR_LIVE_API, RAZOR_LIVE_SECRET);
            $payment = $api->payment->fetch($payment_id);

            $data["currency"] = $payment->currency;
            $data["amount"] = $payment->amount;
            $data["order_id"] = $payment->order_id;
            $data["invoice_id"] = $payment->invoice_id;
            $data["payment_method"] = $payment->method;
            $data["captured_status"] = $payment->captured;
            $data["payment_status"] = $payment->status;
            $data["error_code"] = $payment->error_code;
            $data["error_description"] = $payment->error_description;
            $data["upi"] = $payment->vpa;
            $data["refund_status"] = $payment->refund_status;
            $data["wallet"] = $payment->wallet;
            $data["notes"] = $payment->notes;

            if ($data["payment_method"] == "card") {
                $card_details = $api->card->fetch($payment->card_id);
                $data["card_network"] = $card_details->network;
                $data["card_type"] = $card_details->type;
                $data["bank"] = $card_details->issuer;
            } else if ($data["payment_method"] == "netbanking") {
                $data["bank"] = $payment->bank;
            }
        } else {
            $data["payment_status"] = "failed";
        }
        return $data;
    }

    function convertCurrency($amount, $from_currency, $to_currency)
    {
        $apikey = CURRENCY_API_KEY;
        $from_Currency = urlencode($from_currency);
        $to_Currency = urlencode($to_currency);
        $query = "{$from_Currency}_{$to_Currency}";
        $url = "https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}";
        //$url = preg_replace("/ /", "%20", $url);
        // change to the free URL if you're using the free version
        $json = file_get_contents($url);
        $obj = json_decode($json, true);
        $val = floatval($obj["$query"]);
        $total = $val * $amount;
        return number_format($total, 2, '.', '');
    }
}

?>
