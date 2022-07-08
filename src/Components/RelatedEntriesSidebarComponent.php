<?php declare(strict_types=1);

namespace App\Components;

use App\Entity\Entry;
use App\Entity\Magazine;
use App\Repository\EntryRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Twig\Environment;

#[AsTwigComponent('related_entries_sidebar')]
class RelatedEntriesSidebarComponent
{
    const RELATED_LIMIT = 3;

    public Magazine $magazine;
    public ?Entry $entry = null;

    public function __construct(
        private EntryRepository $repository,
        private Environment $twig,
        private Security $security,
        private CacheInterface $cache
    ) {
    }

    public function getHtml(): string
    {
        return $this->cache->get('related_entries_sidebar_'.$this->magazine->name.'_'.$this->security->getUser()?->getId(), function (ItemInterface $item) {
//            $item->expiresAfter(3600);
            $item->expiresAfter(0);

            $entries = $this->repository->findRelatedByTag($this->magazine->name, self::RELATED_LIMIT);
            if ($this->entry) {
                $entries = array_filter($entries, fn($e) => $e->getId() !== $this->entry->getId());
            }

            if (!count($entries)) {
                return '';
            }

            return $this->twig->render('entry/_related_sidebar.html.twig', ['entries' => $entries]);
        });
    }
}