<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\TrickGroup;
use App\Entity\TrickVersion;
use App\Entity\Trick;
use App\Entity\TrickComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;

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
        $tricks = $this->generateTricks($entityManager);
    }

    /**
     * Generates the tricks.
     *
     * @param Doctrine\Common\Persistence\ObjectManager $entityManager
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    private function generateTricks(ObjectManager $entityManager) : ArrayCollection
    {
        $user = $this->generateUser($entityManager);
        $trickGroups = $this->generateTrickGroups($entityManager);
        $tricks = array();

        foreach ($this->getData()['tricks'] as $trickData) {
            $trick = new Trick(
                $trickData['title'],
                $trickData['description'],
                $trickData['body'],
                $user,
                $trickGroups[$trickData['group']]
            );

            $entityManager->persist($trick);

            $tricks[] = $trick;
        }

        $tricks = new ArrayCollection($tricks);

        foreach ($tricks as $trick) {
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

        return $tricks;
    }

    /**
     * Generates the trick groups.
     *
     * @param Doctrine\Common\Persistence\ObjectManager $entityManager
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    private function generateTrickGroups(ObjectManager $entityManager) : ArrayCollection
    {
        $trickGroups = array();

        foreach ($this->getData()['tricks'] as $trickData) {
            $trickGroups[$trickData['group']] = $trickData['group'];
        }

        foreach ($trickGroups as $trickGroupKey => $trickGroupTitle) {
            $trickGroup = new TrickGroup(
                $trickGroupTitle,
                'No description'
            );

            $entityManager->persist($trickGroup);

            $trickGroups[$trickGroupKey] = $trickGroup;
        }

        return new ArrayCollection($trickGroups);
    }

    /**
     * Generates the user.
     *
     * @param Doctrine\Common\Persistence\ObjectManager $entityManager
     * @return App\Entity\User
     */
    private function generateUser(ObjectManager $entityManager) : User
    {
        $user = new User(
            'Clément',
            'clem@clem.com',
            'azerty',
            true
        );

        $entityManager->persist($user);

        return $user;
    }

    /**
     * Gets the data.
     *
     * @return array
     */
    private function getData() : array
    {
        return \json_decode(\file_get_contents(__DIR__.'/data.json'), true);
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
