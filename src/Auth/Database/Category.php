<?php

namespace Kizi\Settings\Auth\Database;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.category_table'));

        parent::__construct($attributes);
    }
}
