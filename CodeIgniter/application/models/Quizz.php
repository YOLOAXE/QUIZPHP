<?php

    require 'Question.php';

    /**
     * stock un quiz sous la forme d'un objet
     */
    class Quizz {

        private $name; // nom du quiz
        private $status; // état du quiz (en préparation, actif, expiré ou libre)
        private $time; // temps maximal pour répondre au quiz
        private $key; // clé d'accés au quiz
        private $questions; // tableau d'objet de type question (questions du quiz)


        public function __construct() {
            $this->status = 'p';
            $this->questions = null;
        }

        public function getQuestion() {
            return $this->questions;
        }

        public function getName() {
            return $this->name;
        }

        public function getStatus() {
            return $this->status;
        }

        public function getTime() {
            return $this->time;
        }

        public function getKey() {
            return $this->key;
        }

        public function addQuestion($q) {
            $this->questions[] = $q;
        }

        public function setQuestion() {
            $this->question = array();
        }

        public function setName($n) {
            $this->name = $n;
        }

        public function setStatus($s) {
            $this->status = $s;
        }

        public function setTime($t) {
            $this->time = $t;
        }

        public function setKey($k) {
            $this->key = $k;
        }
    }
?>