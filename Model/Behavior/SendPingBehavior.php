<?php

App::import('Vendor', 'SendPing.IXR_Library');

class SendPingBehavior extends ModelBehavior {
    protected $_defaults = array(
        'feed_url' => null
    );

    public function setup(Model $model, $settings = null) {
        if (is_array($settings)) {
            $this->settings[$model->alias] = array_merge($this->_defaults, $settings);
        } else {
            $this->settings[$model->alias] = $this->_defaults;
        }
        $this->servers = array_unique(Configure::read('SendPing.hosts'));
        $this->settings[$model->alias]['title'] = Configure::read('SendPing.title');
        $this->settings[$model->alias]['url'] = Router::url('/', true);
        debug($this->settings);
    }
/**
 * afterSave: send ping messages for some servers
 *
 * @param Model $model
 * @param Boolean $created
 * @param Array $options
 * @return Boolen true: if it returns false, save will fail.
 */
    public function afterSave(Model $model, $created, $options = array()){
        if( $created && isset($this->servers) && isset($this->settings[$model->alias]['title']) && isset($this->settings[$model->alias]['url']) ){
            foreach($this->servers as $server){
                $Client = new IXR_Client($server);
                if(isset($this->settings[$model->alias]['feed_url'])){
                    $this->log("has feed", 'debug');
                    $this->log($this->settings, 'debug');
                    return true;
                    $result = $Client->query('weblogUpdates.extendedPing', $this->settings[$model->alias]['title'], $this->settings[$model->alias]['url'], $this->settings[$model->alias]['feed_url']);
                    if(!$result){
                        $Client->query('weblogUpdates.ping', $this->settings[$model->alias]['title'], $this->settings[$model->alias]['url']);
                    }
                }else{
                    $this->log("has no feed", 'debug');
                    $this->log($this->settings, 'debug');
                    return true;
                    $Client->query('weblogUpdates.ping', $this->settings[$model->alias]['title'], $this->settings[$model->alias]['url']);
                }
            }
        }else{
            $this->log("not save", 'debug');
            $this->log($this->settings, 'debug');
        }
    }
}