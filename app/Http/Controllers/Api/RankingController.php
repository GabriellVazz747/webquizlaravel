<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RankingEntryResource;

class RankingController
{
    public function index(Request $req)
    {
        $limit = (int) $req->query('limit', 50);

        $sub = DB::table('quizzes')
            ->select('user_id', DB::raw('MAX(score) as best_score'), DB::raw('MIN(time_seconds) as best_time'))
            ->whereNotNull('finished_at')
            ->groupBy('user_id');

        $ranking = DB::table('users')
            ->joinSub($sub, 'best', function($join) {
                $join->on('users.id', 'best.user_id');
            })
            ->select('users.id', 'users.name', 'best.best_score as score', 'best.best_time as time_seconds')
            ->orderByDesc('score')
            ->orderBy('time_seconds', 'asc')
            ->limit($limit)
            ->get();

        return response()->json(['ranking' => RankingEntryResource::collection($ranking)]);
    }

    public function myPosition(Request $req)
    {
        $userId = $req->user()->id;

        $sub = DB::table('quizzes')
            ->select('user_id', DB::raw('MAX(score) as best_score'), DB::raw('MIN(time_seconds) as best_time'))
            ->whereNotNull('finished_at')
            ->groupBy('user_id');

        $ranking = DB::table('users')
            ->joinSub($sub, 'best', function($join){
                $join->on('users.id', 'best.user_id');
            })
            ->select('users.id','users.name','best.best_score as score','best.best_time as time_seconds')
            ->orderByDesc('score')
            ->orderBy('time_seconds','asc')
            ->get();

        $position = null;

        foreach ($ranking as $idx => $row) {
            if ((int)$row->id === (int)$userId) {
                $position = $idx + 1;
                $entry = $row;
                break;
            }
        }

        if (is_null($position)) {
            return response()->json(['position' => null, 'entry' => null]);
        }

        return response()->json([
            'position' => $position,
            'entry' => new RankingEntryResource((array) $entry)
        ]);
    }
}
