<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Resolve a model.
 *
 * @param $modelClass
 * @param $modelOrId
 * @return Model
 */
function resolveModel($modelClass, $modelOrId): Model
{
    return $modelOrId instanceof $modelClass
        ? $modelOrId
        : $modelClass::findOrFail($modelOrId);
}
