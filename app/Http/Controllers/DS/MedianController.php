<?php


	namespace App\Http\Controllers\DS;


	use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Validator;

    class MedianController {

        /**
         * Gets median loot for
         *
         * @param int    $tier
         * @param string $type
         * @param int    $fromInt
         * @param Carbon $to
         * @param string $hullSize
         *
         * @return ?int
         */
        public static function getLootForRange(int $tier, string $type, int $fromInt, Carbon $to, string $hullSize) : ?int {

            Validator::make(['type' => $type], [
                'type' => 'required|in:Electrical,Dark,Exotic,Firestorm,Gamma,%'
            ])->validate();

            $from = (new Carbon($to))->addDays(-$fromInt);

                $rowIndex = DB::select("
SELECT
  COUNT(*) + 1 as ROWINDEX
FROM
  runs r
    LEFT JOIN ship_lookup sl on r.SHIP_ID = sl.ID
WHERE
          r.LOOT_ISK>0
      and r.SURVIVED=1
      and r.TIER=?
      and sl.HULL_SIZE=?
      and r.TYPE like ?
      and r.RUN_DATE >=?
      and r.RUN_DATE <?
order by r.LOOT_ISK asc
", [strval($tier), $hullSize, $type, $from, $to])[0]->ROWINDEX;

            $median = DB::select("
SELECT
  AVG(i.LOOT_ISK) AS MEDIAN_ISK
FROM
  (
  SELECT
    r.LOOT_ISK LOOT_ISK,
    ROW_NUMBER() OVER (ORDER BY r.LOOT_ISK) AS rowindex
  FROM
    runs r
    LEFT JOIN ship_lookup sl on r.SHIP_ID = sl.ID
WHERE
          r.LOOT_ISK>0
      and r.SURVIVED=1
      and r.TIER=?
      and sl.HULL_SIZE=?
      and r.TYPE like ?
      and r.RUN_DATE >=?
      and r.RUN_DATE <?
order by r.LOOT_ISK asc
) as i
WHERE
  i.rowindex IN (?,?);", [strval($tier), $hullSize, $type, $from, $to, ceil($rowIndex / 2), intval($rowIndex / 2)])[0]->MEDIAN_ISK;

            return $median ?? null;

        }


        /**
         * Gets loot at a distribution threshold
         * @param int    $tier
         * @param int    $percent
         * @param string $hullSize
         *
         * @return mixed
         */
        public static function getLootAtThreshold(int $tier, int $percent, string $hullSize) {

            return Cache::remember(sprintf("aft.loot-threshold-tier.%d.%d.size.%s", $tier, $percent, $hullSize), now()->addHour(), function () use ($tier, $percent, $hullSize) {

//            DB::statement("SET SQL_MODE=''");
                $rank = DB::select("
            select count(*) as CNT from runs r WHERE
                          r.LOOT_ISK>0
                      and r.SURVIVED=1
    and r.SHIP_ID in (select ID from ship_lookup where HULL_SIZE=?)
                      and r.TIER=cast(? as char);
            ",
                    [$hullSize, strval($tier)])[0]->CNT;

                return DB::select("
SELECT a.LI FROM (
                      SELECT
                          r.LOOT_ISK as LI,
                          DENSE_RANK() OVER ( ORDER BY r.LOOT_ISK ) as RNK
                      FROM runs r
                      WHERE
                              r.LOOT_ISK>0 and
                              r.SURVIVED=1 and
                              r.SHIP_ID in (select ID from ship_lookup where HULL_SIZE=?)
                        and r.TIER=cast(? as char)) a
 WHERE a.RNK=?;

",
                        [$hullSize, strval($tier), round($rank*$percent*0.01)])[0]->LI ?? 0;
            });
        }

        /**
         * Gets loot at a distribution threshold
         *
         * @param int    $tier
         * @param string $type
         * @param int    $percent
         * @param string $hullSize
         *
         * @return mixed
         */
        public static function getLootAtThresholdWeather(int $tier, string $type, int $percent, string $hullSize) {


            $rank = DB::select("
            select count(*) as CNT from runs r WHERE
                          r.LOOT_ISK>0
                      and r.SURVIVED=1
    and r.SHIP_ID in (select ID from ship_lookup where HULL_SIZE=?)
                      and r.TIER=?
                      and r.TYPE=?;
            ", [$hullSize, strval($tier), $type])[0]->CNT;

            $ret = DB::select("
SELECT a.LI FROM (
                      SELECT
                          r.LOOT_ISK as LI,
                          DENSE_RANK() OVER ( ORDER BY r.LOOT_ISK ) as RNK
                      FROM runs r
                      WHERE
                              r.LOOT_ISK>0 and
                              r.SURVIVED=1 and
                              r.SHIP_ID in (select ID from ship_lookup where HULL_SIZE=?)
                        and r.TIER=?
                      and r.TYPE=?) a
 WHERE a.RNK=?;a

", [$hullSize, strval($tier), $type, round($rank * $percent * 0.01)])[0]->LI ?? 0;

            return $ret;
        }

        /**
         * Gets median for a
         *
         * @param int    $tier
         * @param string $hullSize
         *
         * @return int
         */
        public static function getTierMedian(int $tier, string $hullSize):int {

            // Workaround: Tier is changed to string as the SQL server incorrectly uses enum otherwise.
            return Cache::remember(sprintf("aft.loot.median.tier.%d.size.%s", $tier, $hullSize), now()->addHour(), function () use ($tier, $hullSize) {
                $rowIndex = DB::select("
SELECT
  COUNT(*) + 1 as ROWINDEX
FROM
  runs r
    LEFT JOIN ship_lookup sl on r.SHIP_ID = sl.ID
WHERE
      r.LOOT_ISK>0
      and
      r.SURVIVED=1
      and
      r.TIER=?
        and
      sl.HULL_SIZE=?
order by r.LOOT_ISK asc
", [strval($tier), $hullSize ])[0]->ROWINDEX;


                $median = DB::select("
SELECT
  AVG(i.LOOT_ISK) AS MEDIAN_ISK
FROM
  (
  SELECT
    r.LOOT_ISK LOOT_ISK,
    ROW_NUMBER() OVER (ORDER BY r.LOOT_ISK) AS rowindex
  FROM
    runs r
    LEFT JOIN ship_lookup sl on r.SHIP_ID = sl.ID
WHERE
      r.LOOT_ISK>0
      and
      r.SURVIVED=1
      and
      r.TIER=?
        and
      sl.HULL_SIZE=?
order by r.LOOT_ISK asc
) as i
WHERE
  i.rowindex IN (?,?);", [strval($tier), $hullSize, ceil($rowIndex / 2), intval($rowIndex / 2)])[0]->MEDIAN_ISK;

                return $median ?? 0;
            });
        }


        /**
         * Gets median for a
         *
         * @param int    $tier
         * @param string $hullSize
         *
         * @return int
         */
        public static function getTierTypeMedian(int $tier,  string $type, string $hullSize):int {

            $rowIndex = DB::select("
SELECT
  COUNT(*) + 1 as ROWINDEX
FROM
  runs r
    LEFT JOIN ship_lookup sl on r.SHIP_ID = sl.ID
WHERE
      r.LOOT_ISK>0
      and
      r.SURVIVED=1
      and
      r.TIER=?
        and
      sl.HULL_SIZE=?
  and r.TYPE=?

order by r.LOOT_ISK asc
", [strval($tier), $hullSize, $type])[0]->ROWINDEX;


            $median = DB::select("
SELECT
  AVG(i.LOOT_ISK) AS MEDIAN_ISK
FROM
  (
  SELECT
    r.LOOT_ISK LOOT_ISK,
    ROW_NUMBER() OVER (ORDER BY r.LOOT_ISK) AS rowindex
  FROM
    runs r
    LEFT JOIN ship_lookup sl on r.SHIP_ID = sl.ID
WHERE
      r.LOOT_ISK>0
      and
      r.SURVIVED=1
      and
      r.TIER=?
        and
      sl.HULL_SIZE=?
  and r.TYPE=?
order by r.LOOT_ISK asc
) as i
WHERE
  i.rowindex IN (?,?);", [strval($tier), $hullSize, $type, ceil($rowIndex / 2), intval($rowIndex / 2)])[0]->MEDIAN_ISK;

            return $median ?? 0;

        }
        /**
         * Gets a median value for a ship (cached for up to a minute)
         * @param int $fitId fit ID
         * @param int $tier tier
         * @param int $type type
         *
         * @return int
         */
        public static function getFitMedian(int $fitId, int $tier, string $type): int {
//            Log::info("Median calculation for $fitId $tier $type");
            return Cache::remember(sprintf("aft.median.fit.%s.%d.%s", $fitId, $tier, $type), now()->addMinute(), function () use ($fitId, $tier, $type) {
                try {
                    return intval(DB::select("select MEDIAN_FOR_FIT_TYPE_TIER(?, ?, ?) as `MEDIAN`;", [$fitId, strval($tier), $type])[0]->MEDIAN);
                }
                catch (\Exception $e) {
                    return 0;
                }
            });
        }


        /**
         * Gets a median runtime value for a ship (cached for up to a minute)
         * @param int $fitId fit ID
         * @param int $tier tier
         * @param int $type type
         *
         * @return int
         */
        public static function getFitMedianRuntime(int $fitId, int $tier, string $type): int {
            return Cache::remember(sprintf("aft.median.rutime.fit.%s.%d.%s", $fitId, $tier, $type), now()->addMinute(), function () use ($fitId, $tier, $type) {
                return intval(DB::select("select MEDIAN_FOR_FIT_TYPE_TIER_RUNTIME(?, ?, ?) as `MEDIAN`;", [$fitId, $tier, $type])[0]->MEDIAN);
            });
        }
	}
