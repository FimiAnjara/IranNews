<div class="admin-users">
    <h1>Gestion des utilisateurs</h1>
    
    <?php if (empty($data['users'])): ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['users'] as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="role-badge role-<?php echo $user['role']; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Éditer</a>
                            <a href="#" class="btn btn-sm btn-danger delete-link">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
.admin-users {
    background: white;
    padding: 2rem;
    border-radius: 8px;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.admin-table thead {
    background-color: #f8f9fa;
}

.admin-table th,
.admin-table td {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    text-align: left;
}

.admin-table tr:hover {
    background-color: #f9f9f9;
}

.role-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: bold;
}

.role-admin {
    background-color: #e74c3c;
    color: white;
}

.role-author {
    background-color: #3498db;
    color: white;
}

.role-user {
    background-color: #95a5a6;
    color: white;
}

.btn-sm {
    padding: 0.35rem 0.75rem !important;
    font-size: 0.875rem !important;
}

.btn-danger {
    background-color: #e74c3c !important;
}

.btn-danger:hover {
    background-color: #c0392b !important;
}
</style>
