<?php

namespace App\Http\Facades\Firestore;

use Kreait\Firebase\Factory;

class FirestoreRepository implements Firestore
{
    protected $factory, $firestore;

    public function __construct()
    {
        $this->factory = (new Factory)->withServiceAccount(__DIR__ . "/firebase_credentials.json");
        $this->firestore = $this->factory->createFirestore();
    }

    public function query($collection)
    {
        return $this->firestore->database()->collection($collection);
    }

    public function all($collection)
    {
        $results = [];
        $firestore = $this->firestore->database()->collection($collection)->documents();

        foreach ($firestore as $index => $data) {
            $results[$data->id()] = $data->data();
        }

        return $results;
    }

    public function show($collection, $id)
    {
        $firestore = $this->firestore->database()->collection($collection)->document($id);

        return $firestore->snapshot()->data();
    }

    public function create($collection, $attributes)
    {
        $firestore = $this->firestore->database()->collection($collection)->newDocument();

        return $firestore->set($attributes);
    }

    public function update($collection, $id, $attributes)
    {
        $firestore = $this->firestore->database()->collection($collection)->document($id);

        return $firestore->set($attributes);
    }

    public function delete($collection, $id)
    {
        return $this->firestore->database()->collection($collection)->document($id)->delete();
    }
}
