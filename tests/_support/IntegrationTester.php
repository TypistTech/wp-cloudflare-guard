<?php

declare(strict_types=1);

namespace TypistTech\WPCFG;

use Codeception\Actor;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class IntegrationTester extends Actor
{
    use _generated\IntegrationTesterActions;

    public function getContainer(): Container
    {
        $this->container = new Container;
        $this->container->initialize();

        return $this->container;
    }
}
