<?php

use airymvc\app\AppController;

class IndexController extends AppController {
             
     public function shortAction() {

         $variable = "This is the demo for the short URL";
         $this->view->setVar("aVariable", $variable);
         $this->view->render();
         
     }
     
     public function inTplAction() {
     	$x1 = "Inside Test the template view";
     	$this->view->setVar("x1", $x1);
     	$this->view->render();
     }

     public function useTplAction() {
        $x1 = "This demo shows how to use a template that is define in the common.php";
        $this->view->setVar("x2", $x2);
        $this->view->render();
     }
     

     public function dbAction() {
        
        if (empty($this->params["tbl"])) {
            echo json_encode(NULL);
            return;
        }

        echo $this->model->getAll($this->params["tbl"], NULL, TRUE);
        return;
     }

}
