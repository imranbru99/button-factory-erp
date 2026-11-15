<?php

namespace App\Services\Hr;

use App\Models\Employee;
use ImranDevBd\AttendanceHub\DTOs\AttendancePunch;
use ImranDevBd\AttendanceHub\Enums\PunchType;
use ImranDevBd\AttendanceHub\Enums\VerifyMode;
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;
use ImranDevBd\AttendanceHub\Models\AttendanceLog;

class AttendanceService
{
    public function punch(Employee $employee, string $direction = 'in'): ?AttendanceLog
    {
        $punch = new AttendancePunch(
            deviceUserId: $employee->employee_code,
            punchedAt: now(),
            verifyMode: VerifyMode::OTHER,
            punchType: $direction === 'out' ? PunchType::CHECK_OUT : PunchType::CHECK_IN,
            employeeId: $employee->employee_code,
            rawPayload: ['source' => 'button-factory', 'direction' => $direction],
        );

        return AttendanceHub::normalizer()->record($punch);
    }
}
