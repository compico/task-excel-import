<?php

namespace App\Http\Controllers\Api;

use App\Cache\ProductsImportCache;
use App\Http\Controllers\Controller;
use App\Jobs\ImportExcelFileJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as Status;

class ProductsImportController extends Controller
{
    public function check_hash(Request $request): Response
    {
        $hash = $request->query('hash');
        if (is_null($hash)) {
            return response()->noContent(status: Status::HTTP_BAD_REQUEST);
        }

        Log::info('new request', ['hash' => $hash]);

        if (ProductsImportCache::IsFileImported($hash)) {
            Log::info('founded', ['hash' => $hash]);
            return response()->noContent(status: Status::HTTP_CONFLICT);
        }

        return response()->noContent(status: Status::HTTP_OK);
    }

    public function import(Request $request): Response
    {
        $file = $request->file('excel');
        if (!($file instanceof UploadedFile)) {
            return response()->noContent(status: Status::HTTP_BAD_REQUEST);
        }

        $hash = $request->get('hash', '');
        if (empty($hash)) {
            return response()->noContent(status: Status::HTTP_BAD_REQUEST);
        }

        $filePath = $file->store('laravel-excel');

        dispatch(new ImportExcelFileJob(
            $filePath,
            $hash
        ));

        return response()->noContent(status: Status::HTTP_OK);
    }
}
