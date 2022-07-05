<?php
/*
*   08.11.2019
*   RenderFromDatabaseData
*/

namespace App\MenuBuilder;
use App\MenuBuilder\MenuBuilder;

class RenderFromDatabaseData{

    private $mb; // MenuBuilder
    private $data;
    private $aryTitleId = [];

    public function __construct(){
        $this->mb = new MenuBuilder();
    }

    private function addTitle($data){
        $this->mb->addTitle($data['id'], $data['name'], false, 'coreui', $data['sequence']);
    }

    private function addLink($data){
        if($data['parent_id'] === NULL){
            $this->mb->addLink($data['id'], $data['name'], $data['href'], $data['icon'], 'coreui', $data['sequence']);
        }
    }

    private function dropdownLoop($id){
        for($i = 0; $i<count($this->data); $i++){
            if($this->data[$i]['parent_id'] == $id){
                if($this->data[$i]['slug'] === 'dropdown' || $this->data[$i]['slug'] === 'title-dropdown'){
                    $this->addDropdown($this->data[$i]);
                }elseif($this->data[$i]['slug'] === 'link'){
                    $this->mb->addLink($this->data[$i]['id'], $this->data[$i]['name'], $this->data[$i]['href'], $this->data[$i]['icon'], 'coreui', $this->data[$i]['sequence']);
                }else{
                    $this->addTitle($this->data[$i]);
                }
            }
        }
    }
    
    private function addDropdown($data){
        $this->mb->beginDropdown($data['id'], $data['name'], $data['icon'], 'coreui', $data['sequence'], $data['slug']);
        $this->dropdownLoop($data['id']);
        $this->mb->endDropdown();
    }

    private function mainLoop(){
        // $befTitllIdx = 0;//直前のtitleId
        for($i = 0; $i<count($this->data); $i++){
            // if (! $this->data[$i]['name'] ){//メニュー表示対象じゃない
            //     continue;
            // }
            // $titleId = $this->data[$i]['title_id'];
            // if ($this->data[$i]['slug']!='title') {
            //     if (! in_array($titleId, $this->aryTitleId)) {
            //         $this->addTitle($this->data[$befTitllIdx]);
            //         $this->aryTitleId[] = $titleId;
            //     }
            // }
            switch($this->data[$i]['slug']){
                case 'title':
                    // $befTitllIdx = $i;
                    $this->addTitle($this->data[$i]);
                 break;
                case 'link':
                    $this->addLink($this->data[$i]);
                break;
                case 'dropdown':
                case 'title-dropdown':
                    if($this->data[$i]['parent_id'] == null){
                        $this->addDropdown($this->data[$i]);
                    }
                break;
            }
        }
    }

    /***
     * $data - array
     * return - array
     */
    public function render($data){
        if(!empty($data)){
            $this->data = $data;
            $this->mainLoop();
        }
        return $this->mb->getResult();
    }

}