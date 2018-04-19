<?php

namespace App\Jobs\GoogleDrive;

use App\Models\Article;
use App\Models\Project;
use App\Services\Google\Drive;
use App\Services\Google\GoogleDrivePath;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\Media;

/**
 * Class GoogleDriveUpload
 * @package App\Jobs
 */
class GoogleDriveUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GoogleDrivePath;

    /**
     * @var Project
     */
    protected $project;
    /**
     * @var Article
     */
    protected $article;
    /**
     * @var Media
     */
    protected $file;
    /**
     * @var null
     */
    protected $name;
    /**
     * @var Drive
     */
    protected $drive;

    /**
     * Create a new job instance.
     *
     * @param Project $project
     * @param Article $article
     * @param Media $file
     * @param null $name
     */
    public function __construct(Project $project, Article $article, Media $file, $name)
    {
        $this->project = $project;
        $this->article = $article;
        $this->file    = $file;
        $this->name    = $name;
        $this->drive   = new Drive();
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {

        try {
            $expected_path = $this->composePath();

            //get the top folder
            $file_parent = last($this->createPath($expected_path));

            $file = $this->drive->uploadFile(
                $this->file->getPath(),
                $this->name,
                $file_parent
            );


            $this->article->google_id = $file->id;
            $this->article->save();

            $permissions = $this->createPermissions();

            $this->drive->addPermission($file_parent, $permissions->toArray());
            
            $this->article->export(Drive::PDF);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
