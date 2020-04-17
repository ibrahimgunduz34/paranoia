<?php
namespace Paranoia\Nestpay\ResponseParser;

use Paranoia\Core\Exception\InvalidArgumentException;
use Paranoia\Core\Exception\InvalidResponseException;
use Paranoia\Core\Exception\UnapprovedTransactionException;
use Paranoia\Core\Response\AuthorizationResponse;
use Paranoia\Core\Transformer\XmlTransformer;
use Psr\Http\Message\ResponseInterface;

class AuthorizationResponseParser
{
    /** @var XmlTransformer */
    private $transformer;

    /**
     * AuthorizationResponseParser constructor.
     * @param XmlTransformer $transformer
     */
    public function __construct(XmlTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param ResponseInterface $response
     * @return AuthorizationResponse
     * @throws InvalidResponseException
     * @throws UnapprovedTransactionException
     */
    public function parse(ResponseInterface $response): AuthorizationResponse
    {
        try {
            $xml = $this->transformer->transform($response->getBody());
            if ( strtolower((string) $xml->Response) != 'approved') {
                throw new UnapprovedTransactionException(
                    (string) $xml->ErrMsg,
                    (string) $xml->Extra->ERRORCODE,
                    (string) $xml->Extra->HOSTMSG
                );
            }
            return new AuthorizationResponse((string) $xml->TransId, (string) $xml->AuthCode);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidResponseException('Invalid provider response', 0, $exception);
        }
    }
}