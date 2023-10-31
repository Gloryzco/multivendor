<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function failureResponse($message, $statusCode = 400)
	{
		return response()->json([
			'success' => false,
			'message' => $message,
		], $statusCode);
	}

	public function successResponse($message, $data=null, $statusCode = 200)
	{
		$result = [
			'success' => true,
			'message' => $message,
		];

		if ($data) {
			$result['data'] = $data;
		}
		return  response()->json($result, $statusCode);
	}
	
	public function manageError($error, $methodTrace)
    {
        if ($error instanceof NotFoundException) {
			return $this->notFoundError($error);
		} else if ($error instanceof RequestFailedException) {
			return $this->manageFailedError($error);
		}
        else return $this->failureResponse($error->getMessage());
    }

	private function notFoundError(NotFoundException $error)
    {
        return $this->failureResponse($error->getMessage(), 404);
    }

	private function manageFailedError(RequestFailedException $error)
    {
		return $this->failureResponse($error->getMessage());
    }
}
