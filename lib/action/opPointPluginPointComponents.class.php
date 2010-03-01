<?php

class opPointPluginPointComponents extends sfComponents
{
 /**
  * executes my point box
  *
  * @param sfWebRequest $request
  */
  public function executeMyPointBox(sfWebRequest $request)
  {
    $this->point = opPointUtil::getPoint();
  }

 /**
  * executes point box
  *
  * @param sfWebRequest $request
  */
  public function executePointBox(sfWebRequest $request)
  {
    $this->point = opPointUtil::getPoint($request->getParameter('id', null));
  }
}
