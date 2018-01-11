<?php

namespace App\Entity;

use Opportus\UserBundle\Entity\UserToken as BaseUserToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * The user token entity...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity()
 * @ORM\Table(name="user_token")
 */
class UserToken extends BaseUserToken
{}

