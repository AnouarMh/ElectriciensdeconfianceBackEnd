<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function update($id, array $data);
    public function find($id);
}