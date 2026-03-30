<div class="auth-container">
    <form method="POST" class="auth-form" accept-charset="UTF-8">
        <h2>Inscription</h2>

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
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm">Confirmer le mot de passe:</label>
            <input type="password" id="confirm" name="confirm" required>
        </div>

        <button type="submit" class="btn btn-primary">S'inscrire</button>
        
        <p class="auth-link">
            Déjà inscrit? <a href="<?php echo url('connexion'); ?>">Connexion</a>
        </p>
    </form>
</div>
