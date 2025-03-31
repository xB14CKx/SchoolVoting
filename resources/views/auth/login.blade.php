<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CSG Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
  @vite(['resources/css/login.css', 'resources/js/app.js']) <!-- Vite handles CSS and JS -->
</head>
<body>
  <!-- Background Image -->
  <main class="content-container">
    <figure class="image-container">
      <img
        src="https://cdn.builder.io/api/v1/image/assets/TEMP/814ddc42ba1128d4a4b8fb44f27005c2e9014d5d?placeholderIfAbsent=true&apiKey=aa78da9d1a8c4ca2babcebcf463f7106"
        class="responsive-image"
        alt="Featured image"
      />
    </figure>
  </main>
    
  <!-- LogIn Field -->
  <main class="login-container">
    <div class="background-wrapper">
      <div class="background-inner"></div>
    </div>

    <header class="form-header">
      <h1 class="login-title">Login</h1>
    </header>

    <!-- Updated Form -->
    <form class="form-fields" method="POST" action="{{ route('login') }}">
      @csrf
      <div class="input-field">
        <input type="email" name="email" placeholder="Email" aria-label="Email" required />
      </div>
      <div class="input-field">
        <input type="password" name="password" placeholder="Password" aria-label="Password" required />
      </div>

      <section class="form-actions">
        <button type="submit" class="sign-in-button">Sign In</button>
        <div class="remember-section">
          <label class="remember-wrapper">
            <input type="checkbox" name="remember" class="checkbox" aria-label="Remember me" />
            <span>Remember me</span>
          </label>
        </div>
      </section>
    </form>

    <footer class="register-section">
      <p class="no-account">No account yet?</p>
      <a href="#" class="register-link">Register now</a>
    </footer>
  </main>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>