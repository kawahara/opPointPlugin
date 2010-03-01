<?php

class opMemberPointForm extends BaseForm
{
  public function setup()
  {
    if (!($this->getOption('member') instanceof Member))
    {
      throw new LogicException('opMemberPointForm needs member object by option'); 
    }

    $this->setWidget('point', new sfWidgetFormInput());
    $this->setValidator('point', new sfValidatorInteger());
    $this->setDefault('point', opPointUtil::getPoint($this->getOption('member')->getId()));
    $this->widgetSchema->setNameFormat('member[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    $this->getOption('member')->setConfig('op_point', $this->getValue('point'));
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
