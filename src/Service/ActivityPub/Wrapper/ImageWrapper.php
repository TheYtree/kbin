<?php declare(strict_types=1);

namespace App\Service\ActivityPub\Wrapper;

use App\Entity\Image;
use App\Service\ImageManager;

class ImageWrapper
{
    public function __construct(
        private ImageManager $imageManager
    ) {
    }

    public function build(array $item, Image $image, string $title = ''): array
    {
        $item['attachment'][] = [
            'type' => 'Image',
            'mediaType' => $this->imageManager->getMimetype($image),
            'url' => $this->imageManager->getUrl($image),
            'name' => $image->altText,
            'blurhash' => $image->blurhash,
            'focalPoint' => [0, 0],
            'width' => $image->width,
            'height' => $image->height,
        ];

        $item['image'] = [ // @todo Lemmy
            'type' => 'Image',
            'url' => $this->imageManager->getUrl($image),
        ];

        return $item;
    }
}
