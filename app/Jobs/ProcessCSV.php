<?php

namespace App\Jobs;

use App\Models\Employee;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $csvData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $csvData)
    {
        $this->csvData = $csvData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Employee::create($this->csvData);
    }
}
