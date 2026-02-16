<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --orange: #e8900c;
            --white: #FFFFFF;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            margin: 0;
            background-color: var(--white);
        }
        
        .login-image {
            flex: 1;
            background-image: url('{{ asset('admin/assets/img/fille.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .login-form {
            background-color: var(--white);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            border: 1px solid #d5d5d5;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .logo {
            width: 200px;
            margin: 0 auto 1.5rem;
            display: block;
        }
        
        .login-header h2 {
            color: var(--orange);
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .btn-login {
            background-color: var(--orange);
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            transition: all 0.3s;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-login:hover {
            background-color: var(--orange);
            transform: translateY(-2px);
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #f0f0f0;
        }
        
        .form-control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 0.2rem rgba(232, 144, 12, 0.2);
        }
        
        .alert-danger {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }
        
        /* Version mobile */
        @media (max-width: 768px) {
            body {
                display: flex;
                flex-direction: column;
            }
            
            .login-image {
                display: none;
            }
            
            .login-container {
                padding: 1.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh; /* Prend toute la hauteur */
                margin: 0;
            }
            
            .login-form {
                border: none;
                box-shadow: none;
                padding: 2rem;
                margin: auto; /* Centre verticalement et horizontalement */
            border: #d5d5d5 solid 1px;

            }
            
            .logo {
                width: 180px;
                margin-bottom: 1.5rem;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }
        }
        
        /* Version tablette */
        @media (min-width: 769px) and (max-width: 992px) {
            body {
                flex-direction: row;
            }
            
            .login-image {
                min-height: 100vh;
            }
        }
    </style>

</head>
<body>
    
    <div class="login-image"></div>
    
    <div class="login-container">
        <div class="login-form">
            <img src="{{ asset('admin/assets/img/favicon/Logo Institut Excellentia@3x.png') }}" alt="Logo" class="logo">
    
            <div class="login-header">
                <h2>Connexion Étudiant</h2>
            </div>
    
            <form method="POST" action="{{ route('etudiant.login') }}">
                @csrf
    
                <div class="mb-3">
                    <label for="matricule" class="form-label">Matricule</label>
                    <input type="text" 
                           name="matricule" 
                           class="form-control" 
                           placeholder="XXX-XXXX-XXX" 
                           required
                           autofocus>
                    
                    @error('matricule')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
    
                <button type="submit" class="btn btn-login">
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</body>
</html>