<?php

    namespace Rising\Bundle\Entity;

    class Empleo {
        protected $curriculo;
        protected $mensaje;

        public function getCurriculo() {
            return $this->curriculo;
        }

        public function setCurriculo($curriculo) {
            $this->curriculo = $curriculo;
        }

        public function getMensaje() {
            return $this->mensaje;
        }

        public function setMensaje($mensaje) {
            $this->mensaje = $mensaje;
        }
    }
?>
