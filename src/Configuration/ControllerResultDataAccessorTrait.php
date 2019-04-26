<?php

namespace App\Configuration;

/**
 * The controller result data accessor trait.
 *
 * @version 0.0.1
 * @package App\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
trait ControllerResultDataAccessorTrait
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * Constructs the controller result data accessor.
     * 
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->name = $values['name'];
    }

    /**
     * Gets the name.
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
