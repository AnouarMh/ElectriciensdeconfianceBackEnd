<?php

namespace App\Repositories;

interface EntrepriseRepositoryInterface
{
    public function create(array $data);
    public function update($id, array $data);
    public function find($id);
}