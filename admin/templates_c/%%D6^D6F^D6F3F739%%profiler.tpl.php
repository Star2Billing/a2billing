<?php /* Smarty version 2.6.25-dev, created on 2013-11-17 17:27:39
         compiled from profiler.tpl */ ?>
<?php  
        global $profiler;
        global $G_instance_Query_trace;

        if ($profiler->installed && $profiler->modedebug)
                $profiler->display($G_instance_Query_trace);
 ?>
