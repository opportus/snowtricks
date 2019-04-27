<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\TrickGroup;
use App\Entity\Trick;
use App\Entity\TrickComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * The fixtures.
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
        // Loads the default user...
        $user = new User(
            'Clément',
            'clem@example.com',
            'azerty',
            true
        );

        $entityManager->persist($user);

        // Loads the default trick groups...
        $trickGroups = [];

        foreach ($this->getData()['tricks'] as $trickData) {
            $trickGroups[$trickData['group']] = $trickData['group'];
        }

        foreach ($trickGroups as $trickGroupKey => $trickGroupTitle) {
            $trickGroup = new TrickGroup($trickGroupTitle);

            $entityManager->persist($trickGroup);

            $trickGroups[$trickGroupKey] = $trickGroup;
        }

        // Loads the default tricks...
        $tricks = [];

        foreach ($this->getData()['tricks'] as $trickData) {
            $trick = new Trick(
                $trickData['title'],
                $trickData['description'],
                $trickData['body'],
                $trickGroups[$trickData['group']],
                $user
            );

            $entityManager->persist($trick);

            $tricks[] = $trick;
        }

        // Loads the default trick comments...
        foreach ($tricks as $trick) {
            $trickComment = new TrickComment(
                $this->generateLoremIpsum(),
                $trick,
                $user
            );

            $entityManager->persist($trickComment);

            $trickCommentChild = new TrickComment(
                $this->generateLoremIpsum(),
                $trick,
                $user,
                $trickComment
            );

            $entityManager->persist($trickCommentChild);
        }

        $entityManager->flush();
    }

    /**
     * Gets the data.
     *
     * @return array
     */
    private function getData() : array
    {
        return \json_decode(\file_get_contents(__DIR__.\DIRECTORY_SEPARATOR.'data.json'), true);
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
