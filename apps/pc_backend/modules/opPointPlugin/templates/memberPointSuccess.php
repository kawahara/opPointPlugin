<?php slot('submenu') ?>
<?php include_partial('menu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Member Point') ?>
<?php end_slot() ?>

<?php slot('pager') ?>
<?php op_include_pager_navigation($pager, '@op_point_member_point?page=%d') ?>
<?php end_slot() ?>
<?php include_slot('pager') ?>

<table>
<thead>
<tr>
<th>ID</th><th><?php echo __('Nickname') ?></th><th><?php echo __('Point') ?></th>
</tr>
</thead>
<tbody>
<?php foreach ($pager->getResults() as $member): ?>
<tr>
<td><?php echo $member->getId() ?></td>
<td><?php echo $member->getName() ?></td>
<td><?php echo link_to((int)$member->getConfig('op_point'), '@op_point_edit_member_point?id='.$member->getId()) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php include_slot('pager') ?>
