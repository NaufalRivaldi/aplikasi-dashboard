<?php
// ------------------------------------------------------------------------------------
namespace App\Models;
// ------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
// ------------------------------------------------------------------------------------
class Level extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'level';
    // --------------------------------------------------------------------------------
    protected $fillable = ['nama'];
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function users(){
        return $this->hasMany(User::class, 'level_id');
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------