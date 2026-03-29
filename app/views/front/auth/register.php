<div class="auth-container">
    <form method="POST" class="auth-form">
        <h2>Inscription</h2>
        
        <div class="form-group">
            <label for="username">Identifiant:</label>
            <input type="text" id="username" name="username" required>
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
