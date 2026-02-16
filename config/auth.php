<?php

return [
    'defaults' => [
        'guard' => 'web', // Par défaut, guard classique (pour User)
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [ // Pour User (superadmin, admin, formateurs)
            'driver' => 'session',
            'provider' => 'users',
        ],

        'student' => [ // Uniquement pour Student
            'driver' => 'session',
            'provider' => 'students',
        ],
    ],

    'providers' => [
        'users' => [ // Pour User classique
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'students' => [ // Uniquement pour Student
            'driver' => 'eloquent',
            'model' => App\Models\Student::class,
        ],
    ],

    'passwords' => [
        'users' => [ // Pour la réinitialisation de mot de passe (User)
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        // Pas besoin de password reset pour Student
    ],
];