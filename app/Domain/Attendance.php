<?php

namespace IRFANM\SIASHAF\Domain;

class Attendance
{
    public string $attendance_id;
    public int $user_id;
    public int $class_id;
    public string $date;
    public ?string $status = 'present';
    public ?string $remarks = null;
    public string $created_at;
    public string $updated_at;
    public ?string $deleted_at = null;
}
