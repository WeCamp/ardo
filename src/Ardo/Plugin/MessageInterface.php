<?php
/**
 * Created by IntelliJ IDEA.
 * User: jerryverhoef
 * Date: 26/08/15
 * Time: 14:48
 */

namespace Ardo\Plugin;


interface MessageInterface
{

    /**
     * @return Boolean
     */
    public function isEmpty();

    /**
     * @return string
     */
    public function toString();
}