<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Paginator
{
    /**
     * @var array<object>
     */
    private array $datas;

    private int $page;

    private int $itemsPerPage;

    private int $totalItems;

    private string $lastPageLink;

    private string $firstPageLink;

    private ?string $nextPageLink;

    private ?string $previousPageLink;

    private EntityManagerInterface $manager;

    private UrlGeneratorInterface $urlGenerator;

    private RequestStack $request;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $manager,RequestStack $request)
    {
        $this->manager = $manager;
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
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

        $limit = $this->itemsPerPage;
        $start = ($this->page * $this->itemsPerPage) - $this->itemsPerPage;

        $this->totalItems = count($repository->findBy($searchCriteria));
        $totalPages = ceil($this->totalItems / $this->itemsPerPage);

        $lastPage = ceil($this->totalItems / $this->itemsPerPage);
        $this->lastPageLink = $this->urlGenerator->generate($route, ['page' => $lastPage], UrlGeneratorInterface::ABSOLUTE_URL);
        $this->firstPageLink = $this->urlGenerator->generate($route, ['page' => 1], UrlGeneratorInterface::ABSOLUTE_URL);

        $nextPage = $this->page + 1;

        $this->nextPageLink = null;

        if ($nextPage <= $totalPages) {
            $this->nextPageLink = $this->urlGenerator->generate($route, ['page' => $nextPage], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        $previousPage = $this->page - 1;

        $this->previousPageLink = null;

        if ($previousPage >= 1) {
            $this->previousPageLink = $this->urlGenerator->generate($route, ['page' => $previousPage], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        $this->datas = $repository->findBy($searchCriteria, $orderBy, $limit, $start);

        return $this;

    }

    public function getPagination(): array
    {
        return [
            'currentPageNumber' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'totalItems' => $this->totalItems,
            'firstPageLink' => $this->firstPageLink,
            'lastPageLink' => $this->lastPageLink,
            'previousPageLink' => $this->previousPageLink,
            'nextPageLink' => $this->nextPageLink,
        ];
    }

    public function getDatas(): array
    {
        return $this->datas;
    }
}
