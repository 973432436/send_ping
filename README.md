# send_ping
This software is released under the MIT License, see LICENSE.md.

SendPingBehavior afterSave send weblogUpdates.ping

## Installation

### Using Git
```
git submodule add git://github.com/tksmrkm/send_ping.git app/Plugin/SendPing
```

Config/bootstrap.php
``` php:Config/bootstrap.php
// if using default settings.
CakePlugin::load('SendPing', array('bootstrap' => true));
// or not use default settings.
CakePlugin::load('SendPing');

Configure::load('configs');
```

Config/configs.php
``` php:Config/configs.php
$config['SendPing'] = array(
    'hosts' => array(
        // additional ping servers
        'http://ping.example.com/rpc/',
    ),
    'title' => 'Your App Title'
);
```

Model/Example.php
``` php:Model/Example.php
class Example extends AppModel {
    ~~

    public $actsAs = array(
        'SendPing' => array(
            'feed_url' => null
        )
    );

    ~~
}
```
