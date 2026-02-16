<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --orange: #e8900c;
            --orange-light: #FFEEE8;
            --white: #FFFFFF;
            --dark: #2E2E2E;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
        }
        
        .login-image {
            flex: 1;
            background-image: url('{{ asset('admin/assets/img/fille.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        
        .login-image-content {
            max-width: 500px;
            margin: 0 auto;
            z-index: 2;
        }
        
        .login-image h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .login-image p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: var(--white);
        }
        
        .login-form {
            background-color: var(--white);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: #d5d5d5 solid 1px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .logo {
            width: 200px;
            height: auto;
            margin: 0 auto 1.5rem;
            display: block;
        }
        
        .login-header h2 {
            color: var(--orange);
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 61, 0.2);
        }
        
        .form-label {
            color: #555;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
            text-align: left;
        }
        
        .btn-login {
            background-color: var(--orange);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background-color: var(--orange);
            transform: translateY(-2px);
        }
        
        .text-danger {
            color: #dc3545 !important;
            font-size: 0.85rem;
            margin-top: -0.8rem;
            margin-bottom: 1rem;
            display: block;
            text-align: left;
        }
        
        .password-input-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 25px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            z-index: 2;
        }
        
        .toggle-password:hover {
            color: var(--orange);
        }
        
        /* Responsive design - Mobile */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
                background-color: var(--white);
            }
            
            .login-image {
                display: none; /* Cache l'image sur mobile */
            }
            
            .login-container {
                padding: 1.5rem;
                width: 100%;
                max-width: 100%;
                background-color: var(--white);
            }
            
            .login-form {
                border: none;
                box-shadow: none;
                padding: 1.5rem;
            border: #d5d5d5 solid 1px;

            }
            
            .logo {
                width: 180px;
                margin-bottom: 1rem;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }
        }
        
        /* Responsive design - Tablette */
        @media (min-width: 769px) and (max-width: 992px) {
            body {
                flex-direction: row;
            }
            
            .login-image {
                padding: 2rem;
            }
            
            .login-container {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-image">
        
    </div>
    
    <div class="login-container">
        <div class="login-form">
            <img src="{{ asset('admin/assets/img/favicon/Logo Institut Excellentia@3x.png') }}" alt="Logo" class="logo">
            
            <div class="login-header">
                <h2>Connexion</h2>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                           class="form-control" placeholder="votre@email.com">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="password-input-container">
                        <input type="password" name="password" id="password" required 
                               class="form-control" placeholder="••••••••">
                        <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
                    </div>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login">Se connecter</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>