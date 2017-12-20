<?php

namespace libs;

class ParseTemplate
{
    public static function parse($content){
        $pf = [
            '#\{\$([a-zA-Z_][a-zA-Z_0-9].*)\}#',
            '#__ROOT__#',
            '#\{:(.*?)\}#',
            '#\{foreach name="(.*)" key="(.*)" item="(.*)"\}#',
            '#\{endforeach;\}#',
        ];
        $pt = [
            '<?= \$$1 ;?>',
            '<?= __ROOT__ ?>',
            '<?=$1 ?>',
            '<?php foreach($1 as $2=>$3): ?>',
            '<?php endforeach; ?>',
        ];

        $content = preg_replace($pf,$pt,$content);
        
        $template_parse = C('template_parse');
        if($template_parse){
            $content = str_replace(array_keys($template_parse),array_values($template_parse),$content);    
        }
        return $content;
    }

}

