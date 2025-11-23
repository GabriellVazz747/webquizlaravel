<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','total_questions','correct_count','wrong_count','score','time_seconds','started_at','finished_at','question_ids'
    ];

    protected $casts = [
        'question_ids' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }
}
