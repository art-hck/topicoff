<?php

namespace ProfileBundle\Service\Strategy;

use AvatarBundle\Image\Strategy\ImageStrategy;
use AvatarBundle\Parameter\UploadedImageParameter;
use ImageBundle\Image\BackdropEntity;
use ImageBundle\Image\Image;
use ImageBundle\Service\ImageService;
use ProfileBundle\Entity\Profile;
use Intervention\Image\Image as ImageLayout;

class BackdropStrategy extends ImageStrategy
{

    /** @var  $imageService ImageService */
    private $imageService;

    public function setImageService(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __construct(string $storageDirPath, string $publicDirPath)
    {
        $this->storageDirPath = $storageDirPath;
        $this->publicDirPath = $publicDirPath;
    }


    public function getSizes()
    {
        // TODO: Implement getSizes() method.
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function generateImage(BackdropEntity $profile, UploadedImageParameter $imageParameter)
    {

        $imageParameter->setWidth(1500)->setHeight(200);

        $image = $this->generate(
            $imageParameter->getFile()->getRealPath(),
            $imageParameter
        );

        $profile->setBackdrop($image);
    }


    public function generate(string $imagePath,
                             UploadedImageParameter $parameter = null,
                    $name = 'default'): Image
    {

        $absolutePath = $this->getStorageDirPath();
        $webPath = $this->getPublicDirPath();

        if (!is_dir($absolutePath)) {
            mkdir($absolutePath);
        }

        $imageName = sprintf('%s_%s.%s', $name, uniqid(), 'jpg');

        $storageFileDirPath = sprintf('%s/%s', $absolutePath, $name);

        $storageFilePath = sprintf('%s/%s', $storageFileDirPath, $imageName);

        $publicFilePath = sprintf('%s/%s/%s', $webPath, $name, $imageName);

        if (!file_exists($storageFileDirPath)) {
            mkdir($storageFileDirPath);
        }

        $quality = 60;

        $image = $this->imageService->getImageManager()
            ->make($imagePath)
            ->encode($encode = 'jpg', $quality);

        if ($parameter instanceof UploadedImageParameter) {
            $image->crop(
                $image->getWidth(),
                $image->getHeight(),
                $parameter->getStartX(),
                $parameter->getStartY()
            );

            $image->fit(
                $parameter->getWidth(),
                $parameter->getHeight(),
                null,
                "top"
            );
        }

        $image->save($storageFilePath, $quality);

        $originImage = new Image(
            $storageFilePath,
            $publicFilePath,
            $name
        );

        return $originImage;
    }

    public function resize(ImageLayout $image,
                           int $width = null,
                           int $height = null, callable $calback = null): ImageLayout
    {
        return $image->resize($width, $height, $calback);
    }
}