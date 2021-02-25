<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class PaymentGatewayChargeException extends Exception
{

    public function __construct(string $message, array $data )
    {
        $this->data = $data;

        parent::__construct($message);
    }

    /**
     * @return array
     */

    public function getData(): array{
        return $this->data;
    }



}
