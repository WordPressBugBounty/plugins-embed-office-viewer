<?php
namespace EOV\Services;
use EOV\Model\Style;

class Template{

    protected static $style = [];
    protected static $uniqid = null;
    
    public static function html($data){
        self::createId();
        $style = new Style();
        $style::addStyle("#".self::$uniqid, ['position' => 'relative', 'width' => $data['width'], 'height' => $data['height'], 'margin' => '0 auto']);
        ob_start(); 
        ?>
        <style>
            <?php echo esc_html(self::style($data)); ?>
        </style>
        <?php self::pdfNotice($data['docFile'], $data['viewer']); ?>
        <div id="<?php echo esc_attr(self::$uniqid); ?>" class="eov_doc">
            <?php
            if ( $data['source'] == 'library' ) {            
                if ( $data['viewer'] == 'microsoft' ) { 
                    self::microsoftViewer($data);
                } else { 
                    self::googleViewer($data);
                }        
            }
            ?>
        </div>
    <?php 
        $output = ob_get_contents();
        ob_get_clean();
        return $output;
    }

    public static function pdfNotice($file, $viewer){
        $doc_ext = pathinfo( $file, PATHINFO_EXTENSION );
        if ( ($doc_ext == 'pdf' || $doc_ext == 'html') && $viewer == 'microsoft' ) {
            echo  "<h2>OOpssss... Please Select 'View From' Google to show ".esc_html($doc_ext)." File</h2>";
            return false;
        }
    }
    public static function googleViewer($data){
        ?>
        <iframe id="s_pdf_frame" src="//docs.google.com/gview?embedded=true&url=<?php echo esc_url($data['docFile']); ?>" style="margin:0 auto; padding:10px;<?php echo 'width:' . esc_attr($data['width']) . ';height:' . esc_attr($data['height']) ?>" frameborder="0"></iframe>
        <?php
    }

    public static function microsoftViewer($data){
        ?>
        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo esc_url($data['docFile']) ;?>" width="<?php echo esc_attr($data['width']) ;?>" height="<?php echo esc_attr($data['height']) ;?>" frameborder="0"></iframe>
        <?php
    }

    /**
     * create a unique id
     */
    public static function createId(){
        if(self::$uniqid === null){
            self::$uniqid = "eov".uniqid();
        }
    }

    public static function style($data){
        self::addStyle("#".self::$uniqid, ['position' => 'relative', 'width' => $data['width'], 'height' => $data['height'], 'margin' => '0 auto']);

        $stylesheet = '';
        foreach(self::$style as $selector => $style){
            $stylesheet .= " $selector{";
            foreach($style as $property => $value){
                $stylesheet .= $property.":".$value.";";
            }
            $stylesheet .= '}';
        }
        return $stylesheet;
    }

    public static function addStyle($selector, $style){
        if(isset(self::$style[$selector])){
            array_push(self::$style[$selector], $style);
        }else {
            self::$style[$selector] = $style;
        }
        return false;
    }

}