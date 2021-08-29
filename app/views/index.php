<h1><?= $title ?></h1>

<table border="1">
	<tr>
		<td>Nombre</td>
		<td>Sexo</td>
		<td>Edad</td>
	</tr>
	<?php foreach ($data as $user): ?>
	<tr>
		<td><?= $user['Nombre'] ?></td>
		<td><?= $user['Sexo'] ?></td>
		<td><?= $user['Edad'] ?></td>
	</tr>
	<?php endforeach ?>

</table>