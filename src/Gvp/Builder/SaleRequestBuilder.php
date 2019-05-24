<?php
namespace Paranoia\Gvp\Builder;

use Paranoia\Core\Request\Request;
use Paranoia\Core\Serializer\Serializer;
use Paranoia\Gvp\Configuration\Gvp;

class SaleRequestBuilder extends BaseRequestBuilder
{
    const TRANSACTION_TYPE = 'sales';
    const ENVELOPE_NAME    = 'GVPSRequest';

    public function build(Request $request)
    {
        $data = array_merge(
            $this->buildBaseRequest($request),
            ['Card' => $this->buildCard($request->getResource())]
        );

        $serializer = new Serializer(Serializer::XML);
        return $serializer->serialize($data, ['root_name' => self::ENVELOPE_NAME]);
    }

    protected function buildTransaction(Request $request)
    {
        return [
            'Type'                  => self::TRANSACTION_TYPE,
            'InstallmentCnt'        => $this->installmentFormatter->format($request->getInstallment()),
            'Amount'                => $this->amountFormatter->format($request->getAmount()),
            'CurrencyCode'          => $this->currencyCodeFormatter->format($request->getCurrency()),

            #TODO: Will be changed after 3D integration
            'CardholderPresentCode' => self::CARD_HOLDER_PRESENT_CODE_NON_3D,

            'MotoInd'               => 'N',
            'OriginalRetrefNum'     => null,
        ];
    }

    protected function getCredentialPair()
    {
        /** @var \Paranoia\Gvp\Configuration\Gvp $configuration */
        $configuration = $this->configuration;
        return [$configuration->getAuthorizationUsername(), $configuration->getAuthorizationPassword()];
    }

    protected function buildHash(Request $request, $password)
    {
        /** @var \Paranoia\Gvp\Configuration\Gvp $configuration */
        $configuration = $this->configuration;
        return strtoupper(
            sha1(
                sprintf(
                    '%s%s%s%s%s',
                    $request->getOrderId(),
                    $configuration->getTerminalId(),
                    $request->getResource()->getNumber(),
                    $this->amountFormatter->format($request->getAmount()),
                    $this->generateSecurityHash($password)
                )
            )
        );
    }
}
