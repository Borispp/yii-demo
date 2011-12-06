<?php
class YsaController extends CController
{
    protected $_metaTitle = array();
    
    protected $_metaTitleDelim = ' | ';
    
    protected $_metaDescription;
    
    protected $_metaKeywords;
    
    public function setNotice($message)
    {
        return Yii::app()->user->setFlash('notice', $message);
    }

    public function setError($message)
    {
        return Yii::app()->user->setFlash('error', $message);
    }
    
    public function sendJson($data)
    {
        echo CJSON::encode($data);
        Yii::app()->end();
    }
    
    public function sendJsonSuccess($data = array())
    {
        $this->sendJson(array_merge(array('success' => 1), $data));
    }
    
    public function sendJsonError($data = array())
    {
        $this->sendJson(array_merge(array('error' => 1), $data));
    }
    
    public function setMeta($meta)
    {
        $this->setMetaTitle($meta->title)
             ->setMetaKeywords($meta->keywords)
             ->setMetaDescription($meta->description);
        
        return $this;
    }
    
    public function clearMeta()
    {
        $this->_metaTitle = array();
        $this->_metaKeywords = '';
        $this->_metaDescription = '';
    }
    
    public function setMetaTitle($title, $append = false)
    {
        if ($append) {
            array_push($this->_metaTitle, $title);
        } else {
            array_unshift($this->_metaTitle, $title);
        }
        
        return $this;
    }
    
    public function setMetaDescription($description)
    {
        $this->_metaDescription = $description;
        
        return $this;
    }
    
    public function setMetaKeywords($keywords)
    {
        $this->_metaKeywords = $keywords;
        
        return $this;
    }
    
    public function setMetaTitleDelim($delim)
    {
        $this->_metaTitleDelim = $delim;
    }
    
    public function getMetaTitle($implode = true)
    {
        return $implode ? implode($this->_metaTitleDelim, $this->_metaTitle) : $this->_metaTitle;
    }
    
    public function getMetaDescription()
    {
        return $this->_metaDescription;
    }
    
    public function getMetaKeywords()
    {
        return $this->_metaKeywords;
    }
    
    public function getMetaTitleDelim()
    {
        return $this->_metaTitleDelim;
    }
}