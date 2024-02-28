<?php
declare(strict_types=1);

namespace App\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Timezone;

class TimezoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Date()
                ]
            ])
            ->add('timezone', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Timezone()
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}