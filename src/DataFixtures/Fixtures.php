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
        $user = new User(
            'Angelika',
            'angel4ka@gmail.com',
            'azerty',
            true
        );

        $entityManager->persist($user);

        for ($i = 0; $i < 40; $i++) {
            $trickGroup = new TrickGroup(
                $this->generateLoremIpsum('title'),
                $this->generateLoremIpsum('description')
            );

            $entityManager->persist($trickGroup);

            $trick = new Trick(
                $this->generateLoremIpsum('title'),
                $this->generateLoremIpsum('description'),
                $this->generateLoremIpsum(),
                $user,
                $trickGroup
            );

            $entityManager->persist($trick);

            $trickComment = new TrickComment(
                $this->generateLoremIpsum(),
                $user,
                $trick
            );

            $entityManager->persist($trickComment);

            $trickCommentChild = new TrickComment(
                $this->generateLoremIpsum(),
                $user,
                $trick,
                $trickComment
            );

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
}

