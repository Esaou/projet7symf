<?php

namespace App\Controller\Phone;

use App\CustomException\ItemNotFoundException;
use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class PhoneGetItemActionController extends AbstractController
{
    public function __construct(private PhoneRepository $phoneRepository) {

    }

    /**
     * @param Uuid $uuid
     * @param TranslatorInterface $translator
     * @return Response
     */
    #[Route('/api/phones/{uuid}', name: 'get_phone', methods: 'GET')]
    public function _invoke(Uuid $uuid, TranslatorInterface $translator): Response
    {
        $phone = $this->phoneRepository->findOneBy(['uuid' => $uuid]);

        if (null === $phone) {
            throw new ItemNotFoundException($translator->trans('phone.not.found'));
        }

        return $this->json($phone, 200, [], ['groups' => 'phone:read']);
    }
}
