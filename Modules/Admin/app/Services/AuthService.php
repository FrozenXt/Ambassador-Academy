<?php

namespace Modules\Admin\Services;

use Modules\Admin\Repositories\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // ← ADD THIS

class AuthService
{
    protected $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function attemptLogin(string $email, string $password)
    {
        $admin = $this->adminRepository->findByEmail($email);

        if (!$admin) {
            return [
                'success' => false,
                'message' => 'No admin account found with this email.',
            ];
        }

        if (!Hash::check($password, $admin->password)) {
            return [
                'success' => false,
                'message' => 'Incorrect password.',
            ];
        }

        Auth::login($admin); // ← ADD THIS

        return [
            'success' => true,
            'admin'   => $admin,
        ];
    }
}
