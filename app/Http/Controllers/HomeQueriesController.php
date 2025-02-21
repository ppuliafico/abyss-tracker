<?php


    namespace App\Http\Controllers;


    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;

    class HomeQueriesController
    {


        /**
         * @return \Illuminate\Support\Collection
         */
        public function getLastRuns() : \Illuminate\Support\Collection
        {
            return Cache::remember("aft.home.lastruns", now()->addMinutes(3), function () {
               return DB::table("v_runall")->orderBy("CREATED_AT", "DESC")->limit(20)->get();
            });
        }

        /**
         * @return array
         */
        public function getCommonDrops() : array
        {
            return Cache::remember("aft.home.lastdrops", now()->addMinutes(15), function () {
                $drops = DB::select("SELECT ip.ITEM_ID,
       MAX(ip.PRICE_BUY)  as     PRICE_BUY,
       MAX(ip.PRICE_SELL) as     PRICE_SELL,
       MAX(ip.NAME)       as     NAME,
       MAX(ip.GROUP_NAME) as     GROUP_NAME,
       (SELECT SUM(drci.DROPPED_COUNT) / SUM(drci.RUNS_COUNT)
        FROM droprates_cache drci
        WHERE drci.ITEM_ID = ip.ITEM_ID
          AND drci.TYPE = 'All') DROP_CHANCE
FROM item_prices ip
         LEFT JOIN droprates_cache drc ON ip.ITEM_ID = drc.ITEM_ID
WHERE drc.TYPE = 'ALL'
GROUP BY ip.ITEM_ID
ORDER BY 6 DESC
LIMIT 10;
    ");

            return $drops;
            });
        }


        /**
         * @return array
         */
        public function getPersonalStats(): array {
            $loginId = session()->get("login_id");
            $my_runs = DB::table("runs")->where("CHAR_ID", $loginId)->count();
            $my_avg_loot = DB::table("runs")->where("CHAR_ID", $loginId)->avg('LOOT_ISK');
            $my_sum_loot = DB::table("runs")->where("CHAR_ID", $loginId)->sum('LOOT_ISK');
            $my_survival_ratio = (DB::table("runs")->where("CHAR_ID", $loginId)->where("SURVIVED", '=', true)->count()) / max(1, $my_runs) * 100;

            return [$my_runs, $my_avg_loot, $my_sum_loot, $my_survival_ratio];
        }
    }
