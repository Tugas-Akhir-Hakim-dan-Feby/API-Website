<?php

namespace App\Http\Traits;

trait UploadDocument
{
    protected function uploads($documents, $model, $folder)
    {
        foreach ($documents as $document) {
            $this->move($document, $model, $folder);
        }
    }

    protected function move($document, $model, $folder, $request = [])
    {
        $documentPath = $this->storageFile($document, $folder);
        $request['document_path'] = $documentPath;
        $request['document_name'] = $document->getClientOriginalName();
        return $model->create($request);
    }

    protected function storageFile($document, $folder)
    {
        return $document->store($folder);
    }

    protected function upload($document, $model, $folder)
    {
        return $this->move($document, $model, $folder);
    }
}
