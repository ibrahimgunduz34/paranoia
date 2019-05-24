<?php
namespace Paranoia\Nestpay\Builder;

use Paranoia\Core\Request\Request;
use Paranoia\Core\Serializer\Serializer;

class CancelRequestBuilder extends BaseRequestBuilder
{
    const TRANSACTION_TYPE = 'Void';
    const ENVELOPE_NAME    = 'CC5Request';

    public function build(Request $request)
    {
        $data = $this->buildBaseRequest(self::TRANSACTION_TYPE);

        if ($request->getOrderId() && !$request->getTransactionId()) {
            $data = array_merge($data, [
                'OrderId'  => $request->getOrderId()
            ]);
        }

        if ($request->getTransactionId()) {
            $data = array_merge($data, [
                'TransId' => $request->getTransactionId()
            ]);
        }

        $serializer = new Serializer(Serializer::XML);
        return $serializer->serialize($data, ['root_name' => self::ENVELOPE_NAME]);
    }
}
