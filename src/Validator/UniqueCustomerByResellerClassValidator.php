<?php

namespace App\Validator;

use App\Entity\Reseller;
use App\Repository\CustomerRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueCustomerByResellerClassValidator extends ConstraintValidator
{
    private CustomerRepository $customerRepository;

    private Security $security;

    public function __construct(CustomerRepository $customerRepository, Security $security)
    {
        $this->customerRepository = $customerRepository;
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueCustomerByResellerClass) {
            throw new UnexpectedTypeException($constraint, UniqueCustomerByResellerClass::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        // access your configuration options like this:
        if ('strict' === $constraint->mode) {
            // ...
        }

        /** @var Reseller $reseller */
        $reseller = $this->security->getUser();

        $nbCustomer = $this->customerRepository->getCustomerByReseller($value, $reseller);

        if ($nbCustomer >= 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value->getEmail())
                ->atPath('name')
                ->addViolation();
        }
    }
}