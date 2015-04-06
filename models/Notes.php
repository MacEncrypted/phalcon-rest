<?php

use Phalcon\Mvc\Model;

class Notes extends Model {

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $text;

    /**
     * Get all notes.
     * @return array
     */
    public function getAll() {
        $return_notes = array();    
        foreach ($this->find() as $note) {
            $return_note = array();
            $return_note['id'] = $note->id;
            $return_note['title'] = $note->title;
            $return_notes[] = $return_note;
        }
        return $return_notes;
    }
    
    /**
     * 
     * @param integer $id
     * @return array boolean if empty
     */
    public function getSingle($id) {    
        $note = $this->findFirst("id = '" . $id . "'");
        
        if ($note != false) {
            $return_note = array();
            $return_note['id'] = $note->id;
            $return_note['title'] = $note->title;
            $return_note['text'] = $note->text;

            return $return_note;
        } 
        
        return false;
    }
    
    public function setSingle($title, $text) {
        $this->title = $title;
        $this->text = $text;
        
        $this->save();
        
        return $this->id; 
    }
}
