<?php

namespace Jenang2\Model;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class BaseModel extends EloquentModel {
    protected $table = null; // should not be null
    public $timestamps = false; // created_at and updated_at
}
