<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const STARTER = 1;
    const FULL = 2;

    /**
     * @var array
     */

    protected $fillable = [];

    /**
     * @var array
     */
    protected $hidden = [];

    public static function paid()
    {
        return self::whereIn('id', [self::STARTER, self::FULL])
             ->orderBy('ordinal', 'asc')
             ->get();
    }

    public function priceIncent(){
        return $this->price * 100;
    }
}
