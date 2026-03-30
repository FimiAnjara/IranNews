<div class="auth-container">
    <form method="POST" class="auth-form" accept-charset="UTF-8">
        <h2>Connexion</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger form-alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success form-alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="admin@example.com" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" value="admin123!" required>
        </div>

        <button type="submit" class="btn btn-primary">Connexion</button>
        
        <p class="auth-link">
            Pas encore inscrit? <a href="<?php echo url('inscription'); ?>">S'inscrire</a>
        </p>
    </form>
</div>
