<?php

namespace App\Annotation;

use App\Exception\DatumFetchingException;

/**
 * The datum fetcher.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class DatumFetcher implements DatumFetcherInterface
{
    /**
     * {@inheritdoc}
     */
    public function fetch(DatumReference $datumReference, $data): string
    {
        if ($datumReference instanceof DatumGetterReference) {
            if (!\is_callable([$data, $datumReference->getName()])) {
                throw new DatumFetchingException(\sprintf(
                    'Got a reference of type "%s", but data neither is an object or has a public method called "%s".',
                    DatumGetterReference::class,
                    $datumReference
                ));
            }

            $datum = $data->{$datumReference}();
        } elseif ($datumReference instanceof DatumPropertyReference) {
            if (!\is_object($data) || !\property_exists($data, $datumReference->getName()) || !\array_key_exists($datumReference->getName(), \get_object_vars($data))) {
                throw new DatumFetchingException(\sprintf(
                    'Got a reference of type "%s", but data neither is an object or has a public property called "%s".',
                    DatumPropertyReference::class,
                    $datumReference
                ));
            }

            $datum = $data->{$datumReference};
        } elseif ($datumReference instanceof DatumKeyReference) {
            if (!\is_array($data) || !\array_key_exists($datumReference->getName(), $data)) {
                throw new DatumFetchingException(\sprintf(
                    'Got a reference of type "%s", but data neither is an array or has a key called "%s".',
                    DatumKeyReference::class,
                    $datumReference
                ));
            }

            $datum = $data[$datumReference];
        } else {
            throw new DatumFetchingException(\sprintf(
                'Reference of type "%s" is not supported.',
                \get_class($datumReference)
            ));
        }

        if (!\is_string($datum)) {
            throw new DatumFetchingException(\sprintf(
                'A fetchable datum must be of type string. Got a value of type %s.',
                \gettype($datum)
            ));
        }

        return $datum;
    }
}
