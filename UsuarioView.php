<h1>Gestión de Usuarios</h1>

<form action="index.php?action=crear" method="POST">
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Agregar</button>
</form>

<hr>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= e($u['id']) ?></td>
            <td><?= e($u['nombre']) ?></td>
            <td><?= e($u['email']) ?></td>
            
            <td>
                <a href="index.php?action=editar&id=<?= $u['id'] ?>">Editar</a> | 
                <a href="index.php?action=eliminar&id=<?= $u['id'] ?>" 
                onclick="return confirm('¿Estás seguro?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>