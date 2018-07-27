<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/03/17
 * Time: 11:06
 */

namespace EssentialsBundle\Pattern\Observer;

/**
 * Interface ObserverInterface
 * @package Utils\Pattern\Observer
 */
interface ObserverInterface
{
    /**
     * @param ObservableInterface $sender
     * @param $args
     * @return mixed
     */
    public function onChanged(ObservableInterface $sender, $args): ObserverInterface;
}
