<?php


    namespace App\Http\Controllers;


    use App\Http\Controllers\Loot\LootCacheController;
    use App\Http\Controllers\Loot\LootValueEstimator;
    use App\Http\Requests\NewRunRequest;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class RunsController {
        /** @var LootCacheController */
        private $lootCacheController;

        /**
         * RunsController constructor.
         *
         * @param LootCacheController $lootCacheController
         */
        public function __construct(LootCacheController $lootCacheController) { $this->lootCacheController = $lootCacheController; }


        /**
         * Gets the previous run of the logged in char
         *
         * @return mixed
         */
        public function getPreviousRun() {
            $prev = DB::table("runs")
                ->where("CHAR_ID", session()->get("login_id"))
                ->orderBy("CREATED_AT", "DESC")
                ->orderBy("RUN_DATE", "DESC")
                ->limit(1)
                ->first();
            return $prev;
        }


//        /**
//         * Handles the DB persisting of the new run
//         *
//         * @param Request            $request
//         * @param LootValueEstimator $lootEstimator
//         * @return int
//         */
//        public function storeNewRun(Request $request, LootValueEstimator $lootEstimator): int {
//            if ($request->get("RUN_LENGTH_M")) {
//                $runtime = (intval($request->get("RUN_LENGTH_M")) * 60) + intval($request->get("RUN_LENGTH_S"));
//            }
//            else {
//                $runtime = null;
//            }
//
//            $id = DB::table("runs")->insertGetId([
//                'CHAR_ID'           => session()->get("login_id"),
//                'PUBLIC'            => $request->get("PUBLIC"),
//                'TIER'              => min(5, $request->get("TIER")),
//                'TYPE'              => $request->get("TYPE"),
//                'LOOT_ISK'          => $request->get("SURVIVED") ? $lootEstimator->getTotalPrice() : 0,
//                'SURVIVED'          => $request->get("SURVIVED"),
//                'RUN_DATE'          => $request->get("RUN_DATE"),
//                'SHIP_ID'           => $request->get('SHIP_ID'),
//                'DEATH_REASON'      => $request->get('DEATH_REASON'),
//                'PVP_CONDUIT_USED'  => $request->get('PVP_CONDUIT_USED') === "" ? null : intval($request->get('PVP_CONDUIT_USED') == 1 ? true : false),
//                'PVP_CONDUIT_SPAWN' => $request->get('PVP_CONDUIT_SPAWN') === "" ? null : intval($request->get('PVP_CONDUIT_SPAWN') == 1 ? true : false),
//                'FILAMENT_PRICE'    => $request->get('FILAMENT_PRICE'),
//                'LOOT_TYPE'         => $request->get('LOOT_TYPE'),
//                'KILLMAIL'          => $request->get('KILLMAIL'),
//                'RUNTIME_SECONDS'   => $runtime,
//                'IS_BONUS'          => $request->get("TIER") == 6 ? 1 : 0,
//                'FIT_ID'            => ($request->get('FIT_ID') ?? 0) == 0 ? null : $request->get("FIT_ID")
//            ]);
//
//            foreach ($lootEstimator->getItems() as $item) {
//                LootValueEstimator::setItemPrice($item);
//                DB::table("detailed_loot")->insert([
//                    "RUN_ID"  => $id,
//                    "ITEM_ID" => $item->getItemId(),
//                    "COUNT"   => $item->getCount()
//                ]);
//            }
//            return $id;
//        }

        /**
         * Handles the DB persisting of the new run
         *
         * @param Request $request
         * @param array   $lootDifference
         * @return int
         */
        public function storeNewRunWithAdvancedLoot(NewRunRequest $request, array $lootDifference): int {
//    dd($request->all(), $request->input("vessel.SHIP_ID"));

            if ($request->filled("RUN_LENGTH_M") || $request->filled("RUN_LENGTH_S")) {
                $runtime_m = $request->filled("RUN_LENGTH_M") ? $request->get("RUN_LENGTH_M") : 20;
                $runtime_s = $request->filled("RUN_LENGTH_S") ? $request->get("RUN_LENGTH_S") : 0;
                $runtime = ($runtime_m*60)+$runtime_s;
            }
            else {
                $runtime = null;
            }
            $id = DB::table("runs")->insertGetId([
                'CHAR_ID'           => session()->get("login_id"),
                'PUBLIC'            => $request->get("PUBLIC"),
                'TIER'              => strval($request->isBonusRoom() ? "5" : HelperController::clamp($request->get('TIER'),0,6)),
                'TYPE'              => $request->get("TYPE"),
                'LOOT_ISK'          => $request->get("SURVIVED") ? $lootDifference['totalPrice'] : 0,
                'SURVIVED'          => $request->get("SURVIVED"),
                'RUN_DATE'          => $request->get("RUN_DATE"),
                'SHIP_ID'           => $request->getShipId(),
                'DEATH_REASON'      => $request->get('DEATH_REASON'),
                'PVP_CONDUIT_USED'  => null,
                'PVP_CONDUIT_SPAWN' => null,
                'FILAMENT_PRICE'    => $request->get('FILAMENT_PRICE'),
                'LOOT_TYPE'         => $request->get('LOOT_TYPE'),
                'KILLMAIL'          => $request->get('KILLMAIL'),
                'RUNTIME_SECONDS'   => $runtime,
                'IS_BONUS'          => $request->isBonusRoom(true),
                'FIT_ID'            => $request->getFitId()
            ]);

            foreach ($lootDifference['gainedItems'] as $item) {
                LootValueEstimator::setItemPrice($item);
                DB::table("detailed_loot")->insert([
                    "RUN_ID"  => $id,
                    "ITEM_ID" => $item->getItemId(),
                    "COUNT"   => $item->getCount()
                ]);
            }

            foreach ($lootDifference['lostItems'] as $item) {
                LootValueEstimator::setItemPrice($item);
                DB::table("lost_items")->insert([
                    "RUN_ID"  => $id,
                    "ITEM_ID" => $item->getItemId(),
                    "COUNT"   => $item->getCount()
                ]);
            }
            return $id;
        }


        /**
         * @param \Illuminate\Support\Collection $loot
         * @param                                $all_data
         */
        public function extendDropListWithRates(\Illuminate\Support\Collection $loot, $all_data): void {
            foreach ($loot as $lt) {
                $loot_stats = $this->lootCacheController->getItemStatsForTierType($lt->ITEM_ID, $all_data->TYPE, $all_data->TIER);
                try {

                    $lt->DROP_PERCENT = $loot_stats[$all_data->TYPE][$all_data->TIER]->DROPPED_COUNT / max(1, $loot_stats[$all_data->TYPE][$all_data->TIER]->RUNS_COUNT);
                    $lt->TOOLTIP = sprintf("%d / %d runs", $loot_stats[$all_data->TYPE][$all_data->TIER]->DROPPED_COUNT, $loot_stats[$all_data->TYPE][$all_data->TIER]->RUNS_COUNT);
                }
                catch (\Exception $e) {
                    $lt->DROP_PERCENT = 0;
                    $lt->TOOLTIP = "Drop rate is not yet calculated.";
                }
            }
        }

    }
