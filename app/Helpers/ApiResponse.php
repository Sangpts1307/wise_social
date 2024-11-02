<?php 

namespace App\Helpers;
/**
 * Common class response api data to clients
 * 
 * Define all response http status code
 */
class ApiResponse
{
    public function continue()
    {
        return json_encode([
            'code' => 100,
            'data' => null,
            'message' => 'Request approved.'
        ]);
    }

    public function switchProtocol()
    {
        return json_encode([
            'code' => 101,
            'data' => null,
            'message' => 'Request switch protocol.'
        ]);
    }

    public function success($data = null)
    {
        return json_encode([
            'code' => 200,
            'data' => $data,
            'message' => 'Success.'
        ]);
    }

    public function dataNotfound()
    {
        return json_encode([
            'code' => 204,
            'data' => null,
            'message' => 'Data not found.'
        ]);
    }

    public function BadResource()
    {
        return json_encode([
            'code' => 301,
            'data' => null,
            'message' => 'Bad resource.'
        ]);
    }

    public function BadRequest()
    {
        return json_encode([
            'code' => 400,
            'data' => null,
            'message' => 'Bad request.'
        ]);
    }

    public function UnAuthorization()
    {
        return json_encode([
            'code' => 401,
            'data' => null,
            'message' => 'UnAuthorization.'
        ]);
    }

    public function forbidden()
    {
        return json_encode([
            'code' => 403,
            'data' => null,
            'message' => 'Forbidden.'
        ]);
    }

    public function InternalServerError()
    {
        return json_encode([
            'code' => 500,
            'data' => null,
            'message' => 'Internal server error.'
        ]);
    }
}