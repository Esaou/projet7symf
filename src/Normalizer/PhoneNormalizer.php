<?php


namespace App\Normalizer;


use App\Entity\Customer;
use App\Entity\Phone;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PhoneNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
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
        $data = $this->normalizer->normalize($topic, $format, ['groups' => 'phone:read']);

        // Here, add, edit, or delete some data:
        $data['_links']['self'] = $this->router->generate('get_phone', [
            'uuid' => $topic->getUuid(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Phone;
    }
}