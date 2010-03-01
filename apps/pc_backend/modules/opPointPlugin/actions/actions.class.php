<?php

class opPointPluginActions extends sfActions
{
  public function executePointConfigure(sfWebRequest $request)
  {
    $this->form = new opPointConfigureForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bindAndSave($request->getParameter('op_point_configure'));
    }
  }

  public function executeMemberPoint(sfWebRequest $request)
  {
    $this->pager = opPointUtil::getMemberListPagerOrderByPoint($request->getParameter('page', 1));
  }

  public function executeEditMemberPoint(sfWebRequest $request)
  {
    $this->member = $this->getRoute()->getObject();
    $this->form = new opMemberPointForm(array(), array('member' => $this->member));
  }

  public function executeUpdateMemberPoint(sfWebRequest $request)
  {
    $this->member = $this->getRoute()->getObject();
    $this->form = new opMemberPointForm(array(), array('member' => $this->member));
    if ($this->form->bindAndSave($request->getParameter('member')))
    {
      $this->getUser()->setFlash('notice', 'Saved.');
      $this->redirect('@op_point_edit_member_point?id='.$this->member->getId());
    }

    $this->setTemplate('editMemberPoint');
  }
}
