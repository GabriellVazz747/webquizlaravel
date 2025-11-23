<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['title','explanation','difficulty'];

    public function alternatives()
    {
        return $this->hasMany(Alternative::class);
    }
}
