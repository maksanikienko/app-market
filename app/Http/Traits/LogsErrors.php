<?php

namespace App\Http\Traits;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait LogsErrors
{
    protected function handleError(\Throwable $e): JsonResponse
    {
        if ($e instanceof ValidationException
            || $e instanceof AuthenticationException
            || $e instanceof HttpException) {
            throw $e;
        }

        activity('error')
            ->withProperties([
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
                'file'      => str_replace(base_path() . '/', '', $e->getFile()),
                'line'      => $e->getLine(),
                'url'       => request()->fullUrl(),
                'method'    => request()->method(),
            ])
            ->log(class_basename($e));

        return response()->json(['message' => 'Server error'], 500);
    }
}
