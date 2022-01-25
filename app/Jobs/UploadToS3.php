<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use File;
use Illuminate\Support\Facades\Storage;

class UploadToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filename;
    public $uploadpath;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename, $uploadpath)
    {
        $this->filename = $filename;
        $this->uploadpath = $uploadpath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = storage_path() . '/uploads/' . $this->filename;
        if (Storage::disk('s3')->put("/".$this->uploadpath."/".$this->filename, fopen($file, 'r+'))) {
                File::delete($file);
        }
    }
}
