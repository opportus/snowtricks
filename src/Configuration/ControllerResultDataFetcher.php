<?php

namespace App\Configuration;

use App\Exception\ConfigurationException;

/**
 * The controller result data fetcher.
 *
 * @version 0.0.1
 * @package App\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ControllerResultDataFetcher implements ControllerResultDataFetcherInterface
{
    /**
     * {@inheritdoc}
     */
    public function fetch(ControllerResultDataAccessorInterface $accessor, $data): string
    {
        if ($accessor instanceof ControllerResultDataGetter) {
            if (!\is_callable([$data, $accessor->getName()])) {
                throw new ConfigurationException(\sprintf(
                    'Got an accessor of type "%s", but data in the controller result neither is an object nor has a public method called "%s".',
                    ControllerResultDataGetter::class,
                    $accessor->getName()
                ));
            }

            $datum = $data->{$accessor->getName()}();
        } elseif ($accessor instanceof ControllerResultDataProperty) {
            if (!\is_object($data) || !\property_exists($data, $accessor->getName()) || !\array_key_exists($accessor->getName(), \get_object_vars($data))) {
                throw new ConfigurationException(\sprintf(
                    'Got an accessor of type "%s", but data in the controller result neither is an object nor has a public property called "%s".',
                    ControllerResultDataProperty::class,
                    $accessor->getName()
                ));
            }

            $datum = $data->{$accessor->getName()};
        } elseif ($accessor instanceof ControllerResultDataKey) {
            if (!\is_array($data) || !\array_key_exists($accessor->getName(), $data)) {
                throw new ConfigurationException(\sprintf(
                    'Got an accessor of type "%s", but data in the controller result neither is an array nor has a key called "%s".',
                    ControllerResultDataKey::class,
                    $accessor->getName()
                ));
            }

            $datum = $data[$accessor->getName()];
        } else {
            throw new ConfigurationException(\sprintf(
                'Accessor of type "%s" is not supported by "%s".',
                \get_class($accessor),
                self::class
            ));
        }

        if (!\is_string($datum)) {
            throw new ConfigurationException(\sprintf(
                'A value fetchable from the data in the controller result must be of type "string". Got a value of type "%s".',
                \gettype($datum)
            ));
        }

        return $datum;
    }
}
