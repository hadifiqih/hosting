<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    //definis properti
    public $status;
    public $message;

    public function __construct($status, $message, $resource)
    {
        //inisialisasi properti
        $this->status = $status;
        $this->message = $message;

        //panggil parent construct
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        //
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
        ];
    }
}
