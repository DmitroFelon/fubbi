<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 12/18/17
 * Time: 7:59 AM
 */

namespace App\Services\Google;


use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use Google_Service_Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class Drive
 * @package App\Services\Google
 */
class Drive
{

    /**
     *
     */
    const MS_WORD = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    /**
     *
     */
    const PDF = 'application/pdf';
    /**
     *
     */
    const HTML = 'text/html';
    /**
     *
     */
    const TEXT = 'text/plain';

    /**
     * @var
     */
    protected $client_secret_path;
    /**
     * @var string
     */
    protected $app_name;
    /**
     * @var
     */
    protected $credentials_path;
    /**
     * @var
     */
    protected $scopes;
    /**
     * @var Google_Client
     */
    protected $client;
    /**
     * @var Google_Service_Drive
     */
    protected $service;

    /**
     * Drive constructor.
     */
    public function __construct()
    {
        $this->client_secret_path = storage_path('app/google/client_secret.json');
        $this->app_name           = 'Fubbi Service Storage';
        $this->credentials_path   = storage_path('app/google/.credentials/drive-php-quickstart.json');
        $this->scopes             = implode(' ',
            [
                Google_Service_Drive::DRIVE
            ]
        );

        $this->client = $this->getClient();
        $this->client->setAuthConfig(storage_path('app/google/service_account.json'));
        $this->client->useApplicationDefaultCredentials(true);
        $this->service = $this->getService();
    }

    /**
     * @return Google_Client
     */
    private function getClient()
    {
        $client = new Google_Client();
        $client->setScopes($this->scopes);
        $client->useApplicationDefaultCredentials();

        $this->client = $client;
        return $client;
    }

    /**
     * @return Google_Service_Drive
     */
    private function getService()
    {
        return new Google_Service_Drive($this->client);
    }

    /**
     * @param $format
     * @return mixed|string
     */
    public static function getExtension($format)
    {
        $extensions = [
            self::MS_WORD => 'docx',
            self::PDF     => 'pdf',
            self::HTML    => 'html',
            self::TEXT    => 'txt',
        ];

        return (isset($extensions[$format])) ? $extensions[$format] : '';
    }

    /**
     * @return \Google_Service_Drive_FileList
     */
    public function getFiles()
    {
        $optParams = array(
            'pageSize' => 10,
            'fields'   => 'nextPageToken, files(id, name, parents)'
        );
        return $this->service->files->listFiles($optParams);

    }

    /**
     * @param $path
     * @param $name
     * @param $folder_id
     * @return mixed
     * @throws \Exception
     */
    public function uploadFile($path, $name, $folder_id)
    {
        try {
            $fileMetadataCollection = collect();

            $fileMetadataCollection->put('name', $name);
            $fileMetadataCollection->put('parents', [$folder_id]);
            $fileMetadataCollection->put('mimeType', 'application/vnd.google-apps.document');

            $fileMetadata = new Google_Service_Drive_DriveFile($fileMetadataCollection->toArray());

            $data           = File::get($path);
            $bodyCollectiob = collect();
            $bodyCollectiob->put('data', $data);
            $bodyCollectiob->put('mimeType', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            $bodyCollectiob->put('uploadType', 'multipart');
            $bodyCollectiob->put('fields', 'id, name, parents');

            $file = $this->service->files->create($fileMetadata, $bodyCollectiob->toArray());

            return $file;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param $name
     * @param $folder_id
     * @return Google_Service_Drive_DriveFile
     * @throws \Exception
     */
    public function createFile($name, $folder_id)
    {
        try {
            $fileDataCollection = collect();

            $fileDataCollection->put('name', $name);
            $fileDataCollection->put('parents', [$folder_id]);
            $fileDataCollection->put('mimeType', 'application/vnd.google-apps.document');

            $fileMetadata = new Google_Service_Drive_DriveFile($fileDataCollection->toArray());

            $optionsCollection = collect();
            $optionsCollection->put('mimeType', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            $optionsCollection->put('uploadType', 'multipart');
            $optionsCollection->put('fields', 'id, name, parents');

            $file = $this->service->files->create($fileMetadata, $optionsCollection->toArray());

            return $file;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param string $mime
     * @return mixed
     */
    public function getFile($id, $mime = 'application/pdf')
    {
        $response = $this->service->files->export($id, $mime, ['alt' => 'media']);
        return $response->getBody()->getContents();
    }

    /**
     * @param $id
     * @return Google_Service_Drive_DriveFile
     */
    public function getFolder($id)
    {
        return $this->service->files->get($id);
    }

    /**
     * @param $id
     * @return Google_Http_Request
     */
    public function deleteFile($id)
    {
        return $this->service->files->delete($id);
    }

    /**
     * @param $file_id
     * @param $emails
     * @param string $type
     */
    public function addPermission($file_id, $emails, $type = 'user')
    {
        $this->service->getClient()->setUseBatch(true);
        $batch = $this->service->createBatch();

        //add permissions by role
        foreach ($emails as $email => $role) {
            $userPermissionCollection = collect();
            $userPermissionCollection->put('type', $type);
            $userPermissionCollection->put('role', $role);
            $userPermissionCollection->put('emailAddress', $email);

            $userPermission = new Google_Service_Drive_Permission($userPermissionCollection->toArray());
            $request        = $this->service->permissions->create(
                $file_id, $userPermission);
            $batch->add($request, $email);
        }

        //add access to anyone
        $userPermissionCollection = collect();
        $userPermissionCollection->put('type', 'anyone');
        $userPermissionCollection->put('role', 'writer');

        $userPermission = new Google_Service_Drive_Permission($userPermissionCollection->toArray());
        $request        = $this->service->permissions->create(
            $file_id, $userPermission);
        $batch->add($request);

        $result = $batch->execute();

        return $result;
    }

    /**
     * @param $name
     * @param null $parent
     * @return Google_Service_Drive_DriveFile
     */
    public function addFolder($name, $parent = null)
    {
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name'     => $name,
            'parents'  => [$parent],
            'mimeType' => 'application/vnd.google-apps.folder',

        ]);

        $file = $this->service->files->create($fileMetadata, ['fields' => 'id, name, parents']);

        return $file;
    }

    /**
     * @param null $file_id
     */
    public function getChanges($file_id = null)
    {
        $response = $this->service->changes->getStartPageToken();

        $pageToken = $response->startPageToken;

        while ($pageToken != null) {
            $response = $this->service->changes->listChanges($pageToken, [
                'spaces' => 'drive'
            ]);
            foreach ($response->changes as $change) {
                // Process change
                printf("Change found for file: %s", $change->fileId);
            }
            if ($response->newStartPageToken != null) {
                // Last page, save this token for the next polling interval
                $savedStartPageToken = $response->newStartPageToken;
            }
            $pageToken = $response->nextPageToken;
        }
    }

    /**
     * @param $folder_id
     * @return \Illuminate\Support\Collection
     */
    public function getFilesInFolder($folder_id)
    {
        $pageToken = NULL;
        $files     = collect();

        $pageToken = null;

        do {
            $response = $this->service->files->listFiles([
                'q'         => "'{$folder_id}' in parents",
                'pageToken' => $pageToken,
                'fields'    => 'nextPageToken, files(id, name)',
            ]);
            foreach ($response->files as $file) {
                $files->push($file);
            }

            $pageToken = $response->nextPageToken;
        } while ($pageToken != null);

        return $files;
    }

    /**
     * @param array $path
     * @return array
     * @throws \Exception
     */
    public function isPathExist(array $path)
    {
        $this->service->getClient()->setUseBatch(true);
        $batch = $this->service->createBatch();
        foreach ($path as $folder) {
            $request = $this->service->files->listFiles([
                'q'      => "name = '{$folder}'",
                'fields' => 'files(id, name)',
            ]);
            $batch->add($request, $folder);
        }
        $results = $batch->execute();
        $ids     = [];
        foreach ($results as $folder => $result) {
            if ($result instanceof Google_Service_Exception) {
                throw new \Exception('Google API error');
            } else {
                if ($result->files) {
                    foreach ($result->files as $key => $file) {
                        $ids[$folder] = $file->id;
                        continue;
                    }
                } else {
                    $ids[$folder] = false;
                }
            }
        }
        $this->service->getClient()->setUseBatch(false);
        return $ids;
    }

    /**
     * @param $file_id
     * @param string $as
     * @return mixed
     */
    public function exportFile($file_id, $as = self::MS_WORD)
    {
        try {
            $response = $this->service->files->export($file_id, $as, [
                'alt' => 'media'
            ]);

            $content = $response->getBody()->getContents();

            if(!$content){
                $exceptoin = new \Exception(_i('Some error happened while exporting, try later please.'));
                report($exceptoin);
                throw $exceptoin;
            }

            return $content;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $file_id
     * @return \Google_Service_Drive_Revision|null
     */
    public function retrieveRevisions($file_id)
    {
        try {
            $revisions = $this->service->revisions->listRevisions($file_id);
            return $revisions->getRevisions();
        } catch (\Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
        return null;
    }


    /**
     * @param $file_id
     * @param $revision_id
     * @return \Google_Service_Drive_Revision|null
     */
    public function retrieveRevision($file_id, $revision_id)
    {
        try {
            return $this->service->revisions->get($file_id, $revision_id);
        } catch (\Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
        return null;
    }


}