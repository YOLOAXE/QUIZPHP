<?php

    require 'Answer.php';
    /**
     * stock une question sous la forme d'un objet
     */
    class Question {
        private $title; // intitulé de la question
        private $answers; // tableau d'objet de type answer (réponses de la question)
        private $image; // url de l'image à afficher (optionnel)

        public function __construct($t = '', $i = '') {
            $this->title = $t;
            $this->image = $i;
            $this->answer = null;
        }

        public function getTitle() {
            return $this->title;
        }

        public function getAnswer() {
            return $this->answer;
        }

        public function getImage() {
            return $this->image;
        }

        public function setTitle($t) {
            $this->title = $t;
        }

        public function addAnswer($a) {
            $this->answer[] = $a;
        }

        public function setAnswer() {
            $this->answer = array();
        }

        public function setImage($i) {
            $this->image = $i;
        }
    }
?>
