<?php declare(strict_types=1);

namespace App\Service\ActivityPub\Wrapper;

use ApiPlatform\Core\Api\UrlGeneratorInterface;
use App\Entity\Contracts\ActivityPubActivityInterface;
use JetBrains\PhpStorm\ArrayShape;
use Pagerfanta\PagerfantaInterface;

class CollectionItemsWrapper
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    #[ArrayShape([
        '@context'     => "string",
        'type'         => "string",
        'partOf'       => "string",
        'id'           => "string",
        'totalItems'   => "int",
        'orderedItems' => "\Pagerfanta\PagerfantaInterface",
        'next'         => "string",
    ])] public function build(string $routeName, array $routeParams, PagerfantaInterface $pagerfanta, array $items, int $page): array
    {
        $result = [
            '@context'     => ActivityPubActivityInterface::CONTEXT_URL,
            'type'         => 'OrderedCollectionPage',
            'partOf'       => $this->urlGenerator->generate($routeName, $routeParams, UrlGeneratorInterface::ABS_URL),
            'id'           => $this->urlGenerator->generate(
                $routeName,
                $routeParams + ['page' => $page],
                UrlGeneratorInterface::ABS_URL
            ),
            'totalItems'   => $pagerfanta->getNbResults(),
            'orderedItems' => $items,
        ];

        if ($pagerfanta->hasNextPage()) {
            $result['next'] = $this->urlGenerator->generate(
                $routeName,
                $routeParams + ['page' => $pagerfanta->getNextPage()],
                UrlGeneratorInterface::ABS_URL
            );
        }

        return $result;
    }
}