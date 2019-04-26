<?php

namespace App\Configuration;

/**
 * The controller result data fetcher interface.
 *
 * @version 0.0.1
 * @package App\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ControllerResultDataFetcherInterface
{
    /**
     * Fetches a value from the controller result data.
     * 
     * @param App\Configuration\ControllerResultDataAccessorInterface $accessor
     * @param mixed $data
     * @throws App\Exception\ConfigurationException
     */
    public function fetch(ControllerResultDataAccessorInterface $accessor, $data): string;
}
