<?php
namespace App\Repositories;

use App\Models\Voyage;

class VoyageRepository
{
    public function find($id)
    {
        return Voyage::findOrFail($id);
    }

    public function create(array $data)
    {
        return Voyage::create($data);
    }

    public function update(Voyage $voyage, array $data)
    {
        $voyage->fill($data);
        $voyage->save();

        return $voyage;
    }

}
