<?php
    /**
     * stock une réponse sous la forme d'un objet
     */
    class Answer {
        private $text; // texte de la réponse
        private $isCorrect; // bouléen, true si la réponse est une bonne réponse, false sinon

        public function __construct($t, $i) {
            $this->text = $t;
            $this->isCorrect = $i;
        }

        public function getText() {
            return $this->text;
        }

        public function getIsCorrect() {
            return $this->isCorrect;
        }

        public function setText($t) {
            $this->text = $t;
        }

        public function setIsCorrect($i) {
            $this->isCorrect = $i;
        }
    }
?>