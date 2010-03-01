<?php

/**
 * opPointPluginConfiguration
 *
 * @package    opPointPlugin
 * @subpackage config
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opPointPluginConfiguration extends sfPluginConfiguration
{
  protected static
    $pointConfig = null;

 /**
  * get point config
  *
  * @return array
  */
  public static function getPointConfig()
  {
    return self::$pointConfig;
  }

 /**
  * initialize plugin
  *
  */
  public function initialize()
  {
    if ($this->configuration instanceof sfApplicationConfiguration)
    {
      $file = 'config/op_point.yml';
      $this->configuration->getConfigCache()->registerConfigHandler($file, 'sfSimpleYamlConfigHandler');
      self::$pointConfig = include($this->configuration->getConfigCache()->checkConfig($file));
      foreach (self::$pointConfig as $key => $value)
      {
        if (isset($value['event']))
        {
          $listener = isset($value['listener']) ? $value['listener'] : array($this, 'listenTo'.sfInflector::camelize($key));
          
          if (is_array($value['event']))
          {
            foreach ($value['event'] as $eventName)
            {
              $this->dispatcher->connect($eventName, $listener);
            }
          }
          else
          {
            $this->dispatcher->connect($value['event'], $listener);
          }
        }
        else
        {
          throw new LogicException('op_point.yml is wrong');
        }
      }
    }
  }

 /**
  * point up action
  * 
  * @param string $name
  */
  protected function pointUp($name)
  {
    $point = (int)Doctrine::getTable('SnsConfig')->get('op_point_'.$name, 0);
    $reason = isset(self::$pointConfig[$name]['caption']) ? self::$pointConfig[$name]['caption'] : $name;
    opPointUtil::addPoint($point, null, $reason);
  }

 /**
  * magic method to use listen to some event
  *
  * @param string $methodName
  * @param array  $args
  */
  public function __call($methodName, $args)
  {
    if (0 === strpos($methodName, 'listenTo'))
    {
      $name = sfInflector::underscore(substr($methodName, 8));
      if (!in_array($name, array_keys(self::getPointConfig())))
      {
        throw new BadMethodCallException();
      }
      if (!(1 === count($args) && $args[0] instanceof sfEvent))
      {
        throw new InvalidArgumentException();
      }
      $event = $args[0];

      if (0 === strpos($event->getName(), 'op_action.post_execute'))
      {
        if (isset(self::$pointConfig[$name]['check']))
        {
          $check = self::$pointConfig[$name]['check'];
          if (
            ('redirect' === $check &&
            ($event->getSubject() instanceof opFrontWebController) &&
            sfView::SUCCESS === $event['result']) ||
            $event['result'] === $check
          )
          {
            $this->pointUp($name);
          }
          return;
        }
        $this->pointUp($name);
      }

      return;
    }

    throw new BadMethodCallException();
  }

 /**
  * listen to post create community
  *
  * @param sfEvent $event
  */
  public function listenToCreateCommunity(sfEvent $event)
  {
    if ($event->getSubject() instanceof opFrontWebController)
    {
      if (sfView::SUCCESS === $event['result'] && !$event['actionInstance']->getRequest()->hasParameter('id'))
      {
        $this->pointUp('create_community');
      }
    } 
  }
}
