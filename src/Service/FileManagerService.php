<?php


namespace App\Service;


use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService implements FileManagerServiceInterface
{
    private $postImageDir;

    public function __construct($postImageDir)
    {
        $this->postImageDir = $postImageDir;
    }
    /**
     * @return mixed
     */
    public function getPostImageDir()
    {
        return $this->postImageDir;
    }
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    public function imagePostUpload(UploadedFile $file): string
    {
        $fileName = uniqid('', true). '.'.$file->guessExtension();

        try{
            $file->move($this->getPostImageDir(), $fileName);
        }catch (FileException $exception){
            return $exception->getMessage();
        }

        return $fileName;
    }

    /**
     * @param string $fileName
     *
     * @return mixed
     */
    public function removePostImage(string $fileName)
    {
        $fileSystem  = new Filesystem();
        $file = $this->getPostImageDir() . $fileName;
        try{
            $fileSystem->remove($file);
        }catch (IOException $error){
            return $error->getMessage();
        }
    }


}