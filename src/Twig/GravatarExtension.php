<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * The Gravatar extension.
 *
 * @version 0.0.1
 * @package App\Twig
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class GravatarExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getGravatarUri', [$this, 'getGravatarUri']),
        ];
    }

    /**
     * Gets the Gravatar URI.
     *
     * @param string $email
     * @param int $size
     * @param string $imageSet
     * @param string $rating
     * @return string
     */
    public function getGravatarUri(string $email, int $size = 80, string $imageSet = 'mm', string $rating = 'g'): string
    {
        return \sprintf(
            'https://www.gravatar.com/avatar/%s?s=%s&d=%s&r=%s',
            \md5(\strtolower(\trim($email))),
            $size,
            $imageSet,
            $rating
        );
    }
}
