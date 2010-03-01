<?php slot('submenu') ?>
<?php include_partial('menu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Change Member Point') ?>
<?php end_slot() ?>

<?php echo $form->renderFormTag(url_for('@op_point_update_member_point?id='.$member->getId())) ?>
<table>
<tr><th>ID</td><td><?php echo $member->getId() ?></td></tr>
<tr><th><?php echo __('Nickname') ?></th><td><?php echo $member->getName() ?></td></tr>
<?php echo $form ?>
<td colspan="2"><input type="submit" /></td>
</table>
</form>
