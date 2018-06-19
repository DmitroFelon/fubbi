<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.06.18
 * Time: 10:53
 */

namespace App\Models\Traits;

use App\Services\Response\ResponseDTO;

trait ResponseMessage
{
    /**
     * @param string $message
     * @param string $status
     * @return ResponseDTO
     */
    protected function make(string $message, string $status): ResponseDTO
    {
        $response = $this->getResponse();
        $response->message = $message;
        $response->status = $status;

        return $response;
    }

    protected function getResponse()
    {
        return new ResponseDTO();
    }
}