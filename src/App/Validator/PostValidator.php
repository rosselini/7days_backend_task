<?php
declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class PostValidator
{
    public static function validate($title, $content): array
    {
        $input = [
            'title' => $title,
            'content' => $content,
        ];

        $constraint = new Assert\Collection([
            'title' => [
                new Assert\NotBlank(),
                new Assert\Type('string')
            ],
            'content' => [
                new Assert\NotBlank(),
                new Assert\Type('string')
            ]
        ]);

        $validator = Validation::createValidator();
        $violations = $validator->validate($input, $constraint);

        $errors = [];
        if ($violations) {
            foreach ($violations as $violation) {
                $propertyPath = trim($violation->getPropertyPath(), '[]');
                $errors[$propertyPath][] = $violation->getMessage();
            }
        }

        return $errors;
    }
}