<?php

class opPointConfigureForm extends BaseForm
{
  public function setup()
  {
    $config = opPointPluginConfiguration::getPointConfig();
    foreach ($config as $name => $value)
    {
      $this->setWidget($name, new sfWidgetFormInput());
      $this->setValidator($name, new sfValidatorInteger());
      $this->setDefault($name, Doctrine::getTable('SnsConfig')->get('op_point_'.$name, 0));
      $caption = isset($value['caption']) ? $value['caption'] : $name;
      $this->widgetSchema->setLabel($name, $caption);
    }

    $this->widgetSchema->setNameFormat('op_point_configure[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    foreach ($this->getValues() as $name => $value)
    {
      Doctrine::getTable('SnsConfig')->set('op_point_'.$name, $value);
    }
  }

  public function bindAndSave($taintedValues)
  {
    $this->bind($taintedValues);
    if ($this->isValid())
    {
      $this->save();

      return true;
    }
    return false;
  }
}
