<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait CustomResponse
{

    /**
     * Return success response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSuccessResponse($data, $statusCode = 200, $message = '')
    {
        $response = [];
        if ((is_array($data) && (count($data) > 0)) || $data instanceof Collection || is_object($data)) {
            $response['data'] = $data;
        }

        if ($message == '') {
            if ($statusCode === 200) {
                $message = 'request_successful';
            } elseif ($statusCode === 201) {
                $message = 'creation_successful';
            } elseif ($statusCode === 202) {
                $message = 'acceptance_successful';
            } elseif ($statusCode === 204) {
                $message = 'deletion_successful';
            }
        }

        if ($message != '') {
            $response['message'] = trans("messages.{$message}");
            $response['result'] = true;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendErrorResponse($message, $statusCode = 400, $errors = [])
    {

        $response = [
            'message' => trans("messages.{$message}")
        ];

        $response['result'] = false;

        if (is_array($message)) {
            $response = $message;
        }

        if (count($errors) > 0) {
            $response['errors'] = $errors;
        }
        return response()->json($response, $statusCode);
    }
}
