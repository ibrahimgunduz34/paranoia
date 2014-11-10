<?php
namespace Paranoia\Transaction;


class Response implements TransactionInterface
{
    /**
     * @var bool
     */
    private $approved;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var \Paranoia\Transaction\Resource\ResourceInterface
     */
    private $resource;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return boolean
     */
    public function isApproved()
    {
        return $this->approved;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param \Paranoia\Transaction\Resource\ResourceInterface $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return \Paranoia\Transaction\Resource\ResourceInterface
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }


} 