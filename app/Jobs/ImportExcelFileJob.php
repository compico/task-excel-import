<?php

namespace App\Jobs;

use App\Cache\ProductsImportCache;
use App\Imports\ProductImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class ImportExcelFileJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        readonly private string $filePath,
        readonly private string $hash,
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("new job", [
            'job_name' => "ImportExcelFileJob",
            'file_name' => $this->filePath,
            'hash' => $this->hash,
        ]);

        HeadingRowFormatter::default('none');

        Excel::import(new ProductImport, $this->filePath);

        ProductsImportCache::SetImportStatus($this->hash, ProductsImportCache::STATUS_IMPORTED);
    }
}
