<?php

class opPointUtil
{
  const CONFIG_NAME = 'op_point';

 /**
  * add point
  *
  * @param integer $p the point
  * @param integer $memberId the member id
  * @param string  $reason
  * @return integer
  */
  public static function addPoint($p, $memberId = null, $reason = '')
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $member = Doctrine::getTable('Member')->find($memberId);

    $value = (int)$member->getConfig(self::CONFIG_NAME) + $p;
    if (0 === $p)
    {
      return $value;
    }
    $member->setConfig(self::CONFIG_NAME, $value);

    $params = array(
      'member_id' => $memberId,
      'addPoint' => $p,
      'resultPoint' => $value,
      'reason' => $reason
    );

    $event = new sfEvent(null, 'op_point.add_point', $params);
    sfContext::getInstance()->getEventDispatcher()->notifyUntil($event);

    return $value;
  }

 /**
  * get point
  * 
  * @param integer $memberId
  * @return integer
  */
  public static function getPoint($memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $member = Doctrine::getTable('Member')->find($memberId);
    return (int)$member->getConfig(self::CONFIG_NAME);
  }

 /**
  * get member list pager order by point
  *
  * @param integer $page
  * @param integer $size
  * @param boolean $desc
  * @return sfDoctrinePager
  */
  public static function getMemberListPagerOrderByPoint($page = 1, $size = 20, $desc = true)
  {
    $query = Doctrine::getTable('Member')->createQuery('m')
      ->leftJoin("m.MemberConfig mc ON m.id = mc.member_id AND mc.name = 'op_point'")
      ->orderBy('mc.value + 0'.($desc ? ' DESC' : ''));

    $pager = new sfDoctrinePager('Member', 20);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
