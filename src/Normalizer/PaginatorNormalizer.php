<?php


namespace App\Normalizer;


use App\Entity\Customer;
use App\Entity\Phone;
use App\Service\Paginator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PaginatorNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    private $router;
    private $normalizer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer, UrlGeneratorInterface $urlGenerator)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
        $this->urlGenerator = $urlGenerator;
    }

    public function normalize($topic, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($topic, $format, $context);

        $currentPageNumber = $topic->getPagination()['currentPageNumber'];
        $itemsPerPage = $topic->getPagination()['itemsPerPage'];
        $totalItems = $topic->getPagination()['totalItems'];

        $route = 'get_phones';

        foreach ($topic->getDatas() as $item) {
            if ($item instanceof Customer) {
                $route = 'get_customers';
                break;
            }
        }

        // Here, add, edit, or delete some data:

        $totalPages = ceil($totalItems / $itemsPerPage);

        $lastPage = ceil($totalItems / $itemsPerPage);
        $lastPageLink = $this->urlGenerator->generate($route, ['page' => $lastPage], UrlGeneratorInterface::ABSOLUTE_URL);
        $firstPageLink = $this->urlGenerator->generate($route, ['page' => 1], UrlGeneratorInterface::ABSOLUTE_URL);

        $nextPage = $currentPageNumber + 1;

        $nextPageLink = null;

        if ($nextPage <= $totalPages) {
            $nextPageLink = $this->urlGenerator->generate($route, ['page' => $nextPage], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        $previousPage = $currentPageNumber - 1;

        $previousPageLink = null;

        if ($previousPage >= 1) {
            $previousPageLink = $this->urlGenerator->generate($route, ['page' => $previousPage], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        $data['_pagination']['currentPageNumber'] = $currentPageNumber;
        $data['_pagination']['itemsPerPage'] = $itemsPerPage;
        $data['_pagination']['totalItems'] = $totalItems;
        $data['_pagination']['firstPageLink'] = $firstPageLink;
        $data['_pagination']['lastPageLink'] = $lastPageLink;
        $data['_pagination']['previousPageLink'] = $previousPageLink;
        $data['_pagination']['nextPageLink'] = $nextPageLink;
        $data['items'] = $data['datas'];
        unset($data['datas']);
        unset($data['pagination']);


        return $data;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Paginator;
    }
}