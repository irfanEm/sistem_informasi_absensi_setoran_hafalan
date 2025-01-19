<?php

namespace IRFANM\SIASHAF\Domain;

class Guru
{
    public string $user_id;
    public string $nama;
    public string $nik;
    public string $email;
    public string $kontak;
    public string $created_at;
    public string $updated_at;
    public ?string $deleted_at = null;
}
