<div class="auth-container">
    <form method="POST" class="auth-form">
        <h2>Connexion</h2>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Connexion</button>
        
        <p class="auth-link">
            Pas encore inscrit? <a href="<?php echo url('inscription'); ?>">S'inscrire</a>
        </p>
    </form>
</div>
