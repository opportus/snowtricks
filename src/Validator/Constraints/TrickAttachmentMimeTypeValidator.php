<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * The trick attachment mime type constraint validator.
 * 
 * @version 0.0.1
 * @package App\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickAttachmentMimeTypeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TrickAttachmentMimeType) {
            throw new UnexpectedTypeException($constraint, TrickAttachmentMimeType::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $acceptedMimeTypes = array(
            'video/embed',
            'video/mpeg',
            'video/ogg',
            'video/avi',
            'video/webm',
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/webp',
        );

        if (!in_array($value, $acceptedMimeTypes)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation()
            ;
        }
    }
}
