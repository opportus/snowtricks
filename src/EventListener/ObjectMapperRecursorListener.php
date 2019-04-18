<?php

namespace App\EventListener;

use Opportus\ObjectMapper\ObjectMapperInterface;
use Opportus\ObjectMapperBundle\Event\TargetPointValueAssignmentEventInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The object mapper recursor listener.
 *
 * @version 0.0.1
 * @package App\EventListner
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ObjectMapperRecursorListener
{
    /**
     * @var Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    private $objectMapper;

    /**
     * Constructs the object mapper listener.
     *
     * @param Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    public function __construct(ObjectMapperInterface $objectMapper)
    {
        $this->objectMapper = $objectMapper;
    }

    /**
     * Operates on set target point value.
     *
     * @param Opportus\ObjectMapperBundle\TargetPointValueAssignmentEventInterface $event
     */
    public function onSetTargetPointValue(TargetPointValueAssignmentEventInterface $event)
    {
        switch ($event->getRoute()->getFqn()) {
            case 'App\Entity\Trick::getAttachments()=>App\Entity\Dto\TrickDto::$attachments':
                $trickAttachmentDtos = new ArrayCollection();

                foreach ($event->getSourcePointValue() as $trickAttachment) {
                    $trickAttachmentDto = $this->objectMapper->map($trickAttachment, 'App\Entity\Dto\TrickAttachmentDto');

                    $trickAttachmentDtos->add($trickAttachmentDto);
                }
                    
                $event->setTargetPointValueToAssign($trickAttachmentDtos);
                
                break;
        }
    }
}
