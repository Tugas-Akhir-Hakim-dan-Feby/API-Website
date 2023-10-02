<?php

namespace App\Http\Facades\Firestore;

interface Firestore
{
    public function query($collection);

    public function all($collection);

    public function create($collection, $attributes);

    public function show($collection, $id);

    public function update($collection, $id, $attributes);

    public function delete($collection, $id);
}
