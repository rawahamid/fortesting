<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    public function notFoundResponse($message='Not found' )
    {
        return response()->json([
            'success' => false,
            'data' => [],
            'message' => $message,
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse($data, $message = 'Ok')
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], Response::HTTP_OK);
    }

    public function serverErrorResponse($message = 'Server Error')
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function invalidDataResponse($message = 'Invalid data')
    {
        return \response()->json([
            'success' => false,
            'message' => $message,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function unAuthorizedResponse($message = 'Unauthorized')
    {
        return \response()->json([
           'success' => false,
           'message' => $message
        ], Response::HTTP_UNAUTHORIZED);
    }


}
