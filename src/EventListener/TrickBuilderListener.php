<?php

namespace App\EventListener;

use App\Entity\TrickAttachment;
use App\Form\Data\TrickAttachmentData;
use Opportus\ObjectMapper\ObjectMapperInterface;
use Opportus\ObjectMapperBundle\Event\TargetPointValueAssignmentEventInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * The trick builder listener.
 * 
 * @version 0.0.1
 * @package App\EventListner
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickBuilderListener
{
    /**
     * @var Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    private $objectMapper;

    /**
     * Constructs the trick builder listener.
     *
     * @param Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    public function __construct(ObjectMapperInterface $objectMapper)
    {
        $this->objectMapper = $objectMapper;
    }

    /**
     * Operates on set instantiated target point value.
     *
     * @param Opportus\ObjectMapperBundle\TargetPointValueAssignmentEventInterface $event
     */
    public function onSetInstantiatedTargetPointValue(TargetPointValueAssignmentEventInterface $event)
    {
        switch ($event->getRoute()->getFqn()) {
            case 'App\Entity\Trick::getAttachments()=>App\Form\Data\TrickData::$attachments':
                $trickAttachmentDataCollection = new ArrayCollection();

                foreach ($event->getSourcePointValue() as $trickAttachment) {
                    $trickAttachmentData = $this->objectMapper->map($trickAttachment, TrickAttachmentData::class);

                    $trickAttachmentDataCollection->add($trickAttachmentData);
                }

                $event->setTargetPointValueToAssign($trickAttachmentDataCollection);
                
                return;
            case 'App\Form\Data\TrickData::$attachments=>App\Entity\Trick::setAttachments()::$attachments':
                if (null === $event->getSourcePointValue()) {
                    $event->setTargetPointValueToAssign(new ArrayCollection());

                    return;
                }

                foreach ($event->getTarget()->getAttachments() as $trickAttachment) {
                    $criteria = new Criteria();

                    if (0 === \count($event->getSourcePointValue()->matching(
                        $criteria->where(
                            $criteria->expr()->eq('src', $trickAttachment->getSrc())
                        )
                    ))) {
                        $event->getTarget()->removeAttachment($trickAttachment);
                    }

                }

                foreach ($event->getSourcePointValue() as $trickAttachmentData) {
                    $event->getTarget()->addAttachment(new TrickAttachment(
                        $trickAttachmentData->src,
                        $trickAttachmentData->type,
                        $event->getTarget()
                    ));
                }

                $event->disableTargetPointValueAssignment();

                return;
        }
    }
}
