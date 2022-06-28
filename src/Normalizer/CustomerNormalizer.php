<?php


namespace App\Normalizer;

use App\Entity\Customer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CustomerNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    private $router;
    private $normalizer;

    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
    }

    public function normalize($topic, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($topic, $format, ['groups' => 'customer:read']);

        // Here, add, edit, or delete some data:
        $links['_links']['self'] = $this->router->generate('get_customer', [
            'uuid' => $topic->getUuid(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $links['_links']['create'] = $this->router->generate('add_customer', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $links['_links']['update'] = $this->router->generate('edit_customer', [
            'uuid' => $topic->getUuid(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $links['_links']['delete'] = $this->router->generate('delete_customer', [
            'uuid' => $topic->getUuid(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $resultArray = array($links, $data);
        $data = array_merge($resultArray[0], $resultArray[1]);

        return $data;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Customer;
    }
}