<div class="admin-users admin-page">
    <h1>Gestion des utilisateurs</h1>
    
    <?php if (empty($users)): ?>
        <p class="empty-state">Aucun utilisateur trouvé.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <a href="<?php echo adminUrl('user-edit-' . $user['id']); ?>" class="btn btn-sm btn-primary">Éditer</a>
                            <a href="<?php echo adminUrl('user-delete-' . $user['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
