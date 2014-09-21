<div class='mainInfo'>

	<h1>Пользователи</h1>
	<p>Список пользователей.</p>

	<div id="infoMessage"><?php echo $message;?></div>

	<table cellpadding=0 cellspacing=10>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Group</th>
			<th>Status</th>
		</tr>
		<?php foreach ($users as $user):?>
			<tr>
				<td><?php echo $user['first_name']?></td>
				<td><?php echo $user['last_name']?></td>
				<td><?php echo $user['email'];?></td>
				<td><?php echo $user['group_description'];?></td>
				<td><?php echo ($user['active']) ? anchor($uri['point']."/auth/deactivate/".$user['id'], 'Active') : anchor($uri['point']."/auth/activate/". $user['id'], 'Inactive');?></td>
			</tr>
		<?php endforeach;?>
	</table>

	<p><a href="<?php echo site_url($uri['point'].'/auth/create_user');?>">Создать нового пользователя</a></p>

</div>

