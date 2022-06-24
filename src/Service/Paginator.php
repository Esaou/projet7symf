<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class Paginator
{
    /**
     * @var array<object>
     */
    private array $datas;

    private int $page;

    private int $itemsPerPage;

    private int $totalItems;

    private EntityManagerInterface $manager;

    private UrlGeneratorInterface $urlGenerator;

    private RequestStack $request;

    private CacheInterface $cache;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $manager,RequestStack $request, CacheInterface $cache)
    {
        $this->manager = $manager;
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
        $this->cache = $cache;
    }

    /**
     * @param string $class
     * @param array<mixed> $searchCriteria
     * @param array<string> $orderBy
     * @param int $itemsPerPage
     * @param string $route
     * @return Paginator
     */
    public function createPaginator(
        string $class,
        array $searchCriteria,
        array $orderBy,
        int $itemsPerPage,
        string $route
    ): self {

        /** @var Request $request */
        $request = $this->request->getCurrentRequest();
        $repository = $this->manager->getRepository($class);
        $this->itemsPerPage = $itemsPerPage;
        $this->page = (int)$request->query->get('page', 1);

        $this->totalItems = count($repository->findBy($searchCriteria));

        // On vérifie si la page demandée est supérieur/inférieur à la dernière/première page
        if ($this->totalItems < ($itemsPerPage * $this->page)) {
            $this->page = ceil($this->totalItems / $this->itemsPerPage);
        } elseif ($this->page < 1) {
            $this->page = 1;
        }

        $limit = $this->itemsPerPage;
        $start = ($this->page * $this->itemsPerPage) - $this->itemsPerPage;

        $this->datas = $repository->findBy($searchCriteria, $orderBy, $limit, $start);

        $this->datas = $this->cache->get('total_items'.$class, function(ItemInterface $item) use ($repository, $searchCriteria, $orderBy, $limit, $start) {
            $item->expiresAfter(3600);
            return $repository->findBy($searchCriteria, $orderBy, $limit, $start);
        });

        return $this;

    }

    public function getPagination(): array
    {
        return [
            'currentPageNumber' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'totalItems' => $this->totalItems,
        ];
    }

    public function getDatas(): array
    {
        return $this->datas;
    }
}
