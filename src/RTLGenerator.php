<?php
/**
 * @author Mahdi Shad ( ramtin2025[at]yahoo[dot]com )
 * @copyright Copyright iPresta.IR
 * @link iPresta.IR
 * 
 * @version 1.0.0
 *
 */
if (!class_exists('CSSJanus')) {
	require_once 'CSSJanus.php';
}
class RTLGenerator {
    public static $definitions = array(
        'type' => 'css' // OR 'scss'
    );
    public static function generate($directory){
        $all_files = @Tools::scandir($directory, self::$definitions['type'],'',True);
        foreach ($all_files as $file)
            if(substr(rtrim($file,'.'.self::$definitions['type']), -4) != '_rtl'){
                $file_content = file_get_contents($directory.'/'.$file);
                self::make_rtl($file_content, $directory.'/'.$file);
            }
    }
    public static function make_rtl($content, $file) {
        //make rtl current file
        $rtl_content = CSSJanus::transform($content);
        $path = pathinfo($file);
        $rtl_file = $path['dirname'].'/'.$path['filename'].'_rtl.css';
        if (file_exists($rtl_file))
            unlink($rtl_file);
        file_put_contents($rtl_file, $rtl_content);
        //append rtlfix
        $rtlfix = $path['dirname'].'/'.$path['filename'].'.rtlfix';
        if (file_exists($rtlfix)){
            file_put_contents($rtl_file, file_get_contents($rtlfix) . PHP_EOL, FILE_APPEND);
        }
        @chmod($rtl_file, 0644);
    }
}
