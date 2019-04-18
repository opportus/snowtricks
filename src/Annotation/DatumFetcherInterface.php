<?php

namespace App\Annotation;

use AbstractDatumReference as DatumReference;

/**
 * The datum fetcher interface.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface DatumFetcherInterface
{
    /**
     * Fetches the datum.
     * 
     * @param App\Annotation\AbstractDatumReference $datumReference
     * @param mixed $data
     * @throws App\Exception\DatumFetchingException
     */
    public function fetch(DatumReference $datumReference, $data): string;
}
