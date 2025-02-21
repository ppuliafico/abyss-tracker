<?php


	namespace App\Http\Controllers\Misc\DTO;


	use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;

    class IngameDonor {

	    /** @var int */
	    private $id;

	    /** @var int */
        private $donorId;

        /** @var string */
        private $donorName;

        /** @var float */
        private $amount;

        /** @var string */
        private $date;

        /** @var string */
        private $reason;

        /**
         * Saves the entry in the database
         */
        public function persist() {
            DB::table("donors")->insertOrIgnore([
                "ID" => $this->id,
                "CHAR_ID" => $this->donorId,
                "NAME" => $this->donorName,
                "AMOUNT" => ceil($this->amount),
                "DATE" => Carbon::make($this->date),
                "REASON" => $this->reason
            ]);
        }

        public static function fromEsiResponse(array $item): IngameDonor {
            $donor = new IngameDonor();
            $donor
                ->setId($item["id"])
                ->setAmount($item["amount"])
                ->setDate($item["date"])
                ->setDonorId($item["first_party_id"])
                ->setDonorName(str_ireplace(" deposited cash into Veetor Nara's account", "", $item["description"]))
                ->setReason($item["reason"]);
            return $donor;
        }

        /**
         * @return int
         */
        public function getId() : int {
            return $this->id;
        }

        /**
         * @param int $id
         *
         * @return IngameDonor
         */
        public function setId(int $id) : IngameDonor {
            $this->id = $id;

            return $this;
        }

        /**
         * @return int
         */
        public function getDonorId() : int {
            return $this->donorId;
        }

        /**
         * @param int $donorId
         *
         * @return IngameDonor
         */
        public function setDonorId(int $donorId) : IngameDonor {
            $this->donorId = $donorId;

            return $this;
        }

        /**
         * @return string
         */
        public function getDonorName() : string {
            return $this->donorName;
        }

        /**
         * @param string $donorName
         *
         * @return IngameDonor
         */
        public function setDonorName(string $donorName) : IngameDonor {
            $this->donorName = $donorName;

            return $this;
        }

        /**
         * @return float
         */
        public function getAmount() : float {
            return $this->amount;
        }

        /**
         * @param float $amount
         *
         * @return IngameDonor
         */
        public function setAmount(float $amount) : IngameDonor {
            $this->amount = $amount;

            return $this;
        }

        /**
         * @return string
         */
        public function getDate() : string {
            return $this->date;
        }

        /**
         * @param string $date
         *
         * @return IngameDonor
         */
        public function setDate(string $date) : IngameDonor {
            $this->date = $date;

            return $this;
        }

        /**
         * @return string
         */
        public function getReason() : string {
            return $this->reason;
        }

        /**
         * @param string $reason
         *
         * @return IngameDonor
         */
        public function setReason(string $reason) : IngameDonor {
            $this->reason = $reason;

            return $this;
        }


    }
