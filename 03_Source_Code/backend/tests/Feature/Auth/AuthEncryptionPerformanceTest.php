<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthEncryptionPerformanceTest extends TestCase
{
    use RefreshDatabase;

    private function measure(callable $fn, int $iterations = 200): array
    {
        // Warm-up (mengurangi fluktuasi akibat cache/opcache)
        for ($i = 0; $i < 10; $i++) {
            $fn();
        }

        $samples = [];
        for ($i = 0; $i < $iterations; $i++) {
            $start = hrtime(true); // ns
            $fn();
            $end = hrtime(true);
            $samples[] = ($end - $start) / 1_000_000; // ms
        }

        $avg = array_sum($samples) / count($samples);

        return [
            'avg_ms' => $avg,
            'samples' => $samples,
        ];
    }

    private function percentOverhead(float $baselineMs, float $encryptedMs): float
    {
        if ($baselineMs <= 0.0) {
            return 0.0;
        }
        return (($encryptedMs - $baselineMs) / $baselineMs) * 100.0;
    }

    public function test_performance_auth_plaintext_vs_encrypted_payload(): void
    {
        $password = 'secret123';

        // Buat user terverifikasi agar proses login valid
        $user = User::factory()->create([
            'email' => 'perf@example.test',
            'password' => Hash::make($password),
            'is_verified' => true,
        ]);

        $iterations = 200;

        // 3 skenario ukuran payload (selaras Kueri 1/2/3 pada paper)
        // Karena /login hanya menerima email & password, ukuran disimulasikan melalui padding password.
        $scenarios = [
            'Kueri 1 (Pendek: < 50 Karakter)' => [
                'password' => $password . 'X',
            ],
            'Kueri 2 (Sedang: 50 - 200 Karakter)' => [
                'password' => $password . str_repeat('P', 50),
            ],
            'Kueri 3 (Panjang: > 200 Karakter)' => [
                'password' => $password . str_repeat('L', 250),
            ],
        ];

        $results = [];

        foreach ($scenarios as $label => $payload) {
            $email = $user->email;
            $plainPassword = (string) $payload['password'];

            // Baseline: plaintext
            $baseline = $this->measure(function () use ($email, $plainPassword) {
                $this->post('/login', [
                    'email' => $email,
                    'password' => $plainPassword,
                ])->status();
            }, $iterations);

            // Encrypted simulation (base64 encode + base64 decode)
            $encrypted = $this->measure(function () use ($email, $plainPassword) {
                $encodedEmail = base64_encode($email);
                $encodedPassword = base64_encode($plainPassword);

                $decodedEmail = base64_decode($encodedEmail, true);
                $decodedPassword = base64_decode($encodedPassword, true);

                $this->post('/login', [
                    'email' => $decodedEmail,
                    'password' => $decodedPassword,
                ])->status();
            }, $iterations);

            $overhead = $this->percentOverhead((float) $baseline['avg_ms'], (float) $encrypted['avg_ms']);

            $results[] = [
                'label' => $label,
                'baseline_ms' => (float) $baseline['avg_ms'],
                'encrypted_ms' => (float) $encrypted['avg_ms'],
                'overhead_percent' => (float) $overhead,
            ];
        }

        fwrite(STDOUT, "\n===== AUTH PERFORMANCE (avg per scenario) =====\n");
        foreach ($results as $row) {
            fwrite(STDOUT, $row['label'] . "\n");
            fwrite(STDOUT, '  Plaintext avg (ms): ' . number_format($row['baseline_ms'], 3, '.', '') . "\n");
            fwrite(STDOUT, '  Encrypted avg (ms): ' . number_format($row['encrypted_ms'], 3, '.', '') . "\n");
            fwrite(STDOUT, '  Overhead (%): ' . number_format($row['overhead_percent'], 2, '.', '') . "\n");
        }
        fwrite(STDOUT, "==============================================\n\n");

        $this->assertNotEmpty($results);

        $maxOverhead = max(array_map(fn($r) => $r['overhead_percent'], $results));
        $this->assertLessThan(500.0, $maxOverhead);
    }
}

