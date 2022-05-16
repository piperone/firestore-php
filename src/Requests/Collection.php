<?php

namespace TorMorten\Firestore\Requests;

use Illuminate\Support\Str;
use TorMorten\Firestore\References\Collection as CollectionReference;
use TorMorten\Firestore\References\Document as DocumentReference;
use TorMorten\Firestore\Support\ServiceAccount;

class Collection extends Request
{
    public function buildPath()
    {
        $parentId = Str::replace(['/' . $this->getCollectionId(), $this->getCollectionId()], '', $this->parameters);
        return resolve(ServiceAccount::class)->getParentId() . ($parentId ? "/{$parentId}" : '');
    }

    public function getCollectionId()
    {
        return Str::afterLast($this->parameters, '/');
    }

    public function documents()
    {
        $collectionResponse = $this->resource->listDocuments($this->buildPath(), $this->getCollectionId());
        return new CollectionReference($collectionResponse, $this);
    }

    public function document($id)
    {
        return new DocumentReference($id, null, $this);
    }
}
