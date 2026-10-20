<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * Data access for support staff.
 */
final class StaffRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    public function all(): array
    {
        $stmt = $this->db->query(
            'SELECT s.staff_id,
                    s.staff_name,
                    s.role,
                    s.country,
                    t.team_name
             FROM support_staff s
             LEFT JOIN teams t ON s.team_id = t.team_id
             ORDER BY s.staff_id'
        );

        return $stmt->fetchAll();
    }

    public function create(
        string $staffName,
        string $role,
        string $country,
        ?int $teamId
    ): void {
        $stmt = $this->db->prepare(
            'INSERT INTO support_staff (staff_name, role, country, team_id)
             VALUES (:staff_name, :role, :country, :team_id)'
        );

        $stmt->execute([
            'staff_name' => $staffName,
            'role'       => $role,
            'country'    => $country,
            'team_id'    => $teamId,
        ]);
    }
}
