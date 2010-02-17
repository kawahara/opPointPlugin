<?php

class opPointUtil
{
  const CONFIG_NAME = 'op_point';

  public static function addPoint($p, $memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getMemberId();
    }

    $member = Doctrine::getTable('Member')->find($memberId);

    $value = (int)$member->getConfig(self::CONFIG_NAME) + $p;

    $params = array(
      'member_id' => $memberId,
      'addPoint' => $p,
      'resultPoint' => $value
    );

    $event = new sfEvent($this, 'op_point.add_point', $params);
    sfContext::getInstance()->getEventDispatcher()->notifyUntil($event);

    return $value;
  }

  public static function getPoint($memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getMemberId();
    }

    $member = Doctrine::getTable('Member')->find($memberId);
    return (int)$member->getConfig(self::CONFIG_NAME);
  }

  public static function getPagerOrderByPoint($desc = true)
  {
  }
}
