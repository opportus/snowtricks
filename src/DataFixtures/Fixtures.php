<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\TrickGroup;
use App\Entity\TrickVersion;
use App\Entity\Trick;
use App\Entity\TrickComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * The fixtures...
 *
 * @version 0.0.1
 * @package App\DataFixtures
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class Fixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $entityManager)
    {
        $user = new User();

        $user->setUsername('opportus');
        $user->setEmail('opportus@gmail.com');
        $user->setPlainPassword('azerty');
        $user->addRole('ROLE_USER');
        $user->enable();

        $entityManager->persist($user);

        for ($i = 0; $i < 40; $i++) {
            $trickGroup = new TrickGroup();

            $trickGroup->setTitle($this->generateLoremIpsum('title'));
            $trickGroup->setDescription($this->generateLoremIpsum('description'));
            $trickGroup->setAuthor($user);

            $entityManager->persist($trickGroup);

            $trick        = new Trick();
            $trickVersion = new TrickVersion();

            $trickVersion->setTitle($this->generateLoremIpsum('title'));
            $trickVersion->setDescription($this->generateLoremIpsum('description'));
            $trickVersion->setBody($this->generateLoremIpsum('body'));
            $trickVersion->setGroup($trickGroup);
            $trickVersion->setAuthor($user);
            $trickVersion->setTrick($trick, true);

            $entityManager->persist($trickVersion);

            $trickComment = new TrickComment();

            $trickComment->setBody($this->generateLoremIpsum('body'));
            $trickComment->setThread($trick);
            $trickComment->setAuthor($user);

            $entityManager->persist($trickComment);

            $trickCommentChild = new TrickComment();

            $trickCommentChild->setBody($this->generateLoremIpsum('body'));
            $trickCommentChild->setThread($trick);
            $trickCommentChild->setParent($trickComment);
            $trickCommentChild->setAuthor($user);

            $entityManager->persist($trickCommentChild);
        }

        $entityManager->flush();
    }

    /**
     * Generates a lorem ipsum string.
     *
     * @param  string $type
     * @return string
     */
    protected function generateLoremIpsum(string $type = null) : string
    {
        $para = $type === 'title' || $type === 'description' ? '1' : '5';
        $url  = 'https://baconipsum.com/api/?type=meat-and-filler&paras=' . $para . '&format=text'; 
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $loremIpsum = curl_exec($curl);

        curl_close($curl);

        if ($type === 'title') {
            $loremIpsum = explode(' ', substr($loremIpsum, 0, 20));
            $loremIpsum = ucwords(trim(implode(' ', $loremIpsum)));
            return $loremIpsum;

        } elseif ($type === 'description') {
            $loremIpsum = explode(' ', substr($loremIpsum, 0, 255));
            $loremIpsum = trim(implode(' ', $loremIpsum));
            return $loremIpsum;

        } else {
            return $loremIpsum;
        }
    }

    /**
     * Defines data.
     *
     * @return array
     */
    protected function defineData() : array
    {

        /*$trickGroups = array(
            array(
                'title'       => 'Grab',
                'description' => 'A grab consists of grabing the snowboard with one hand during the jump.'
            ),
            array(
                'title'       => 'Rotation',
                'description' => 'It consists of an horizantal rotation during the jump and landing in normal or switch position.'
            ),
            array(
                'title'       => 'Flip',
                'description' => 'A flip is a vertical rotation.'
            ),
            array(
                'title'       => 'Slide',
                'description' => 'Slides are tricks performed along the surface of obstacles
                    like handrails and funboxes. In skateboarding, slides are distinguished from 
                    grinds because some tricks are performed by sliding on the surface of the skateboard, 
                    and others are performed by grinding on the trucks of the skateboard. However, 
                    because snowboards don\'t have trucks, the term grind doesn\'t apply to these types of maneuvers. They can still be called grinds.'
            ),
            array(
                'title'       => 'Stall',
                'description' => 'Stalls in snowboarding are derived from similar tricks in skateboarding, 
                    and are typically performed in halfpipes or on similar obstacles. 
                    Variations have been adapted as snowboards do not have trucks and wheels.'
            ),
        );

        $trickVersions = array(
            array(
                'title'       => 'One Two',
                'description' => 'A trick in which the rider\'s front hand grabs the heel edge behind his back foot.'
                'body'        => 'A trick in which the rider\'s front hand grabs the heel edge behind his back foot.'
                'group'       => 'grab',
                'author'      => 'oc',
            ),
        );*/
    }
}

