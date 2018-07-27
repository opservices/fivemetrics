<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/03/17
 * Time: 11:08
 */

namespace EssentialsBundle\Pattern\Observer;

/**
 * Interface ObservableInterface
 * @package Utils\Pattern\Observer
 */
interface ObservableInterface
{
    /**
     * @param ObserverInterface $observer
     * @return mixed
     */
    public function addObserver(ObserverInterface $observer): ObservableInterface;
}
