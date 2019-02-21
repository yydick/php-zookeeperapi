<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spool\Zookeeper\Client\Adaptor;

use Spool\Zookeeper\Client\Adaptor\BufferListT;
/**
 * Description of CompletionListT
 *
 * @author 陈浩波
 */
class CompletionListT {
    public $xid;		    //int32_t
    public $c;			    //CompletionT
    public $data;		    //void
    public $buffer;		    //obj BufferListT
    public $next;		    //obj CompletionListT
    public $watcher;		    //obj WatcherRegistrationT
}
