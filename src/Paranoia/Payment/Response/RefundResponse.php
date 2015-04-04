<?php
namespace Paranoia\Payment\Response;

class RefundResponse extends ResponseAbstract
{
    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @param string $transactionId
     * @return \Paranoia\Payment\Response\RefundResponse
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
}