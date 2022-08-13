<?php declare(strict_types=1);

namespace App\Service\ActivityPub\Wrapper;

use ApiPlatform\Core\Api\UrlGeneratorInterface;
use App\Entity\Contracts\ActivityPubActivityInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Uid\Uuid;

class AcceptWrapper
{
    public function __construct(private UrlGeneratorInterface $urlGenerator, private FollowWrapper $followWrapper)
    {
    }

    #[ArrayShape(['@context' => "string", 'id' => "string", 'type' => "string", 'actor' => "string", 'object' => "string"])] public function build(
        string $user,
        string $actor,
        string $remoteId,
    ): array {
        $id = Uuid::v4()->toRfc4122();

        return [
            '@context' => ActivityPubActivityInterface::CONTEXT_URL,
            'id'       => $this->urlGenerator->generate('ap_object', ['id' => $id], UrlGeneratorInterface::ABS_URL).'#accept',
            'type'     => 'Accept',
            'actor'    => $user,
            'object'   => [
                'id'     => $remoteId,
                'type'   => 'Follow',
                'actor'  => $actor,
                'object' => $user,
            ],
        ];
    }
}
