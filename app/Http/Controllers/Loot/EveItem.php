<?php


    namespace App\Http\Controllers\Loot;


    class EveItem
    {

        /** @var int */
        private $itemId;
        /** @var string */
        private $itemName;
        /** @var int */
        private $sellValue;
        /** @var int */
        private $buyValue;
        /** @var int */
        private $count;

        /**
         * @return int
         */
        public function getCount(): int {
            return $this->count;
        }

        /**
         * @param int $count
         * @return EveItem
         */
        public function setCount(int $count): EveItem {
            $this->count = $count;
            return $this;
        }


        /**
         * EveItem constructor.
         */
        public function __construct()
        {
        }


        /**
         * @return int
         */
        public function getItemId() : int
        {
            return $this->itemId;
        }

        /**
         * @param int $itemId
         *
         * @return EveItem
         */
        public function setItemId(int $itemId) : EveItem
        {
            $this->itemId = $itemId;

            return $this;
        }

        /**
         * @return string
         */
        public function getItemName() : string
        {
            return $this->itemName;
        }

        /**
         * @param string $itemName
         *
         * @return EveItem
         */
        public function setItemName(string $itemName) : EveItem
        {
            $this->itemName = $itemName;

            return $this;
        }

        /**
         * @return int
         */
        public function getSellValue() : int
        {
            return $this->sellValue;
        }

        /**
         * @param int $sellValue
         *
         * @return EveItem
         */
        public function setSellValue(int $sellValue) : EveItem
        {
            $this->sellValue = $sellValue;

            return $this;
        }

        public function getStackAverageValue() {
            return $this->count*(($this->buyValue+$this->sellValue)/2);
        }

        /**
         * @return int
         */
        public function getBuyValue() : int
        {
            return $this->buyValue;
        }

        /**
         * @param int $buyValue
         *
         * @return EveItem
         */
        public function setBuyValue(int $buyValue) : EveItem
        {
            $this->buyValue = $buyValue;

            return $this;
        }


    }
