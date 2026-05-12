<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpseclib3\Net\SSH2;
use Exception;

class ServerMonitoringController extends Controller
{
    public function index()
    {
        // Tarik data dari kedua server secara bersamaan
        $server1 = $this->getServerStats(env('SERVER1_NAME'), env('SERVER1_IP'), env('SERVER1_USER'), env('SERVER1_PASS'));
        $server2 = $this->getServerStats(env('SERVER2_NAME'), env('SERVER2_IP'), env('SERVER2_USER'), env('SERVER2_PASS'));

        return view('admin.server-monitoring', compact('server1', 'server2'));
    }

    private function getServerStats($name, $ip, $user, $pass)
    {
        $data = [
            'name' => $name,
            'ip' => $ip,
            'status' => 'offline',
            'cpu' => 0, 'ram_used' => 0, 'ram_total' => 0, 'ram_percent' => 0,
            'disk_used' => 0, 'disk_total' => 0, 'disk_percent' => 0,
            'uptime' => '-', 'docker' => []
        ];

        try {
            $ssh = new SSH2($ip);
            if (!$ssh->login($user, $pass)) {
                return $data; // Return offline jika gagal login
            }

            $data['status'] = 'online';

            // 1. Ambil CPU Usage (%)
            $cpuRaw = $ssh->exec("vmstat 1 2 | tail -1 | awk '{print 100 - $15}'");
            $data['cpu'] = trim($cpuRaw);

            // 2. Ambil RAM (MB)
            $ramRaw = $ssh->exec("free -m | awk 'NR==2{print $2\",\"$3}'");
            $ramParts = explode(',', trim($ramRaw));
            if (count($ramParts) == 2) {
                $data['ram_total'] = $ramParts[0];
                $data['ram_used'] = $ramParts[1];
                $data['ram_percent'] = $ramParts[0] > 0 ? round(($ramParts[1] / $ramParts[0]) * 100) : 0;
            }

            // 3. Ambil Disk / Storage
            $diskRaw = $ssh->exec("df -h / | awk 'NR==2{print $2\",\"$3\",\"$5}'");
            $diskParts = explode(',', trim($diskRaw));
            if (count($diskParts) == 3) {
                $data['disk_total'] = $diskParts[0];
                $data['disk_used'] = $diskParts[1];
                $data['disk_percent'] = str_replace('%', '', $diskParts[2]);
            }

            // 4. Ambil Uptime
            $data['uptime'] = trim($ssh->exec("uptime -p"));

            // 5. Ambil Status Kontainer Docker
            $dockerRaw = $ssh->exec("docker ps -a --format '{{.Names}}|{{.State}}'");
            $containers = explode("\n", trim($dockerRaw));
            foreach ($containers as $c) {
                if (!empty($c)) {
                    $parts = explode('|', $c);
                    $data['docker'][] = [
                        'name' => $parts[0],
                        'state' => $parts[1] // running, exited, dll
                    ];
                }
            }

            $ssh->disconnect();
        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
        }

        return $data;
    }
}