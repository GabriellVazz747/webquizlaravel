<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAnswer extends Model
{
    use HasFactory;
    protected $fillable = ['quiz_id','question_id','alternative_id','is_correct','time_taken','answered_at'];
    protected $casts = ['answered_at' => 'datetime'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
