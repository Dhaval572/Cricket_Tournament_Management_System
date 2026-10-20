<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\StaffRepository;
use App\Repositories\TeamRepository;
use PDOException;

/**
 * Support staff tab: staff list and add-staff form.
 */
final class StaffController extends Controller
{
    public function index(): void
    {
        $staff = new StaffRepository();
        $teams = new TeamRepository();

        $this->view('staff', [
            'activePage' => 'staff',
            'staffList'  => $staff->all(),
            'teams'      => $teams->all(),
        ]);
    }

    public function create(): void
    {
        Csrf::verify();

        $staffName = trim($_POST['staff_name'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $teamId = trim($_POST['team_id'] ?? '');

        if ($staffName === '' || $role === '' || $country === '') {
            Flash::error('Staff Name, Role, and Country are required.');
            $this->redirect('staff');
        }

        try {
            $repository = new StaffRepository();
            $repository->create(
                $staffName,
                $role,
                $country,
                $teamId !== '' ? (int) $teamId : null
            );

            Flash::success(sprintf("Support Staff '%s' added.", $staffName));
        } catch (PDOException $e) {
            Flash::error('Database error adding support staff: ' . $e->getMessage());
        }

        $this->redirect('staff');
    }
}
